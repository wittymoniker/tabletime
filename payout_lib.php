<?php
/** Tabletime payout wallet + Square seller OAuth helpers. */
function tt_payout_schema_ready(mysqli $db):bool{
    $required=[
        'payout_requests'=>['id','account_id','amount_micros','method','destination_ref','status','provider_reference','note','created_at','updated_at','paid_at'],
        'square_seller_connections'=>['account_id','environment','merchant_id','access_token_enc','refresh_token_enc','scopes','expires_at','status','created_at','updated_at'],
        'payout_dispatches'=>['id','payout_request_id','attempt','status','http_status','response_excerpt','created_at'],
        'ad_money_ledger'=>['id','beneficiary_account_id','boost_id','impression_id','kind','amount_micros','created_at'],
    ];
    foreach($required as $table=>$columns){
        if(!tt_schema_table_exists($db,$table))return false;
        foreach($columns as $column)if(!tt_schema_column_exists($db,$table,$column))return false;
    }
    return true;
}
function tt_payout_crypto_secret():string{
    $v=trim((string)(getenv('TABLETIME_TOKEN_ENCRYPTION_KEY')?:''));
    if($v!=='')return $v;
    $v=trim((string)(getenv('SQUARE_OAUTH_APPLICATION_SECRET')?:getenv('SQUARE_APPLICATION_SECRET')?:''));
    return $v;
}
function tt_payout_crypto_key():string{
    $secret=tt_payout_crypto_secret();
    if($secret==='')throw new RuntimeException('Token encryption is not configured. Set TABLETIME_TOKEN_ENCRYPTION_KEY or SQUARE_OAUTH_APPLICATION_SECRET.');
    return hash('sha256','tabletime-square-oauth-v1|'.$secret,true);
}
function tt_secret_encrypt(string $plain):string{
    if($plain==='')return '';
    if(!function_exists('openssl_encrypt'))throw new RuntimeException('PHP OpenSSL is required for Square OAuth token storage.');
    $iv=random_bytes(12);$tag='';
    $cipher=openssl_encrypt($plain,'aes-256-gcm',tt_payout_crypto_key(),OPENSSL_RAW_DATA,$iv,$tag,'tabletime-square-oauth-v1',16);
    if($cipher===false)throw new RuntimeException('Could not encrypt Square OAuth token.');
    return 'v1.'.rtrim(strtr(base64_encode($iv),'+/','-_'),'=').'.'.rtrim(strtr(base64_encode($tag),'+/','-_'),'=').'.'.rtrim(strtr(base64_encode($cipher),'+/','-_'),'=');
}
function tt_payout_b64url_decode(string $v):string{
    $v=strtr($v,'-_','+/');$v.=str_repeat('=',(4-strlen($v)%4)%4);$out=base64_decode($v,true);return $out===false?'':$out;
}
function tt_secret_decrypt(string $packed):string{
    if($packed==='')return '';$p=explode('.',$packed,4);if(count($p)!==4||$p[0]!=='v1')return '';
    $iv=tt_payout_b64url_decode($p[1]);$tag=tt_payout_b64url_decode($p[2]);$cipher=tt_payout_b64url_decode($p[3]);
    if($iv===''||$tag===''||$cipher===''||!function_exists('openssl_decrypt'))return '';
    $plain=openssl_decrypt($cipher,'aes-256-gcm',tt_payout_crypto_key(),OPENSSL_RAW_DATA,$iv,$tag,'tabletime-square-oauth-v1');
    return $plain===false?'':$plain;
}
function tt_square_oauth_app_id():string{return trim((string)(getenv('SQUARE_OAUTH_APPLICATION_ID')?:getenv('SQUARE_APPLICATION_ID')?:''));}
function tt_square_oauth_app_secret():string{return trim((string)(getenv('SQUARE_OAUTH_APPLICATION_SECRET')?:getenv('SQUARE_APPLICATION_SECRET')?:''));}
function tt_square_oauth_redirect_url():string{
    $set=trim((string)(getenv('SQUARE_OAUTH_REDIRECT_URL')?:''));if($set!=='')return $set;
    $host=(string)($_SERVER['HTTP_HOST']??'eski-web.org');return 'https://'.$host.'/tabletime/square_oauth_callback.php';
}
function tt_square_oauth_scopes():string{
    $set=trim((string)(getenv('SQUARE_OAUTH_SCOPES')?:''));
    return $set!==''?$set:'MERCHANT_PROFILE_READ BANK_ACCOUNTS_READ PAYOUTS_READ';
}
function tt_square_oauth_ready():bool{return tt_square_oauth_app_id()!==''&&tt_square_oauth_app_secret()!==''&&tt_payout_crypto_secret()!=='';}
function tt_square_oauth_base():string{
    if(function_exists('tt_square_base_url'))return tt_square_base_url();
    $env=strtolower(trim((string)(getenv('SQUARE_ENV')?:'production')));return $env==='sandbox'?'https://connect.squareupsandbox.com':'https://connect.squareup.com';
}
function tt_square_oauth_http(string $method,string $path,array $body=[],string $bearer=''):array{
    if(!function_exists('curl_init'))throw new RuntimeException('PHP cURL is required for Square OAuth.');
    $headers=['Square-Version: '.(function_exists('tt_square_api_version')?tt_square_api_version():'2026-08-19'),'Accept: application/json','Content-Type: application/json'];
    if($bearer!=='')$headers[]='Authorization: Bearer '.$bearer;
    $ch=curl_init(tt_square_oauth_base().$path);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>20]);
    if($method!=='GET')curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body,JSON_UNESCAPED_SLASHES));
    $raw=curl_exec($ch);$err=curl_error($ch);$code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);curl_close($ch);
    if($raw===false||$err!=='')throw new RuntimeException('Square OAuth request failed.');$data=json_decode((string)$raw,true);if(!is_array($data))$data=[];
    if($code<200||$code>=300){$msg=(string)($data['errors'][0]['detail']??$data['errors'][0]['code']??$data['error_description']??$data['error']??'Square rejected the OAuth request.');throw new RuntimeException($msg);}return $data;
}
function tt_square_state_issue(int $uid):string{
    $nonce=bin2hex(random_bytes(16));$exp=time()+600;$payload=$uid.'.'.$exp.'.'.$nonce;$secret=tt_payout_crypto_secret();if($secret==='')throw new RuntimeException('OAuth state signing is not configured.');
    $sig=hash_hmac('sha256',$payload,$secret);$_SESSION['tt_square_oauth_nonce']=$nonce;$_SESSION['tt_square_oauth_exp']=$exp;return $payload.'.'.$sig;
}
function tt_square_state_verify(string $state,int $uid):bool{
    $p=explode('.',$state);if(count($p)!==4)return false;[$sid,$exp,$nonce,$sig]=$p;if((int)$sid!==$uid||(int)$exp<time()||(int)$exp>time()+900)return false;
    $secret=tt_payout_crypto_secret();if($secret==='')return false;$payload=$sid.'.'.$exp.'.'.$nonce;$ok=hash_equals(hash_hmac('sha256',$payload,$secret),$sig);
    $sessionNonce=(string)($_SESSION['tt_square_oauth_nonce']??'');if($sessionNonce!==''&&!hash_equals($sessionNonce,$nonce))$ok=false;return $ok;
}
function tt_square_seller_connection(mysqli $db,int $uid):?array{
    if(!tt_payout_schema_ready($db))return null;$s=tt_prepare($db,'SELECT `environment`,`merchant_id`,`scopes`,`expires_at`,`status`,`created_at`,`updated_at` FROM `square_seller_connections` WHERE `account_id`=? LIMIT 1');$s->bind_param('i',$uid);$s->execute();$r=$s->get_result()->fetch_assoc();$s->close();return $r?:null;
}
function tt_square_seller_store(mysqli $db,int $uid,array $token):void{
    $merchant=substr((string)($token['merchant_id']??''),0,191);$access=(string)($token['access_token']??'');$refresh=(string)($token['refresh_token']??'');if($merchant===''||$access==='')throw new RuntimeException('Square did not return a seller merchant ID and access token.');
    $accessEnc=tt_secret_encrypt($access);$refreshEnc=tt_secret_encrypt($refresh);$scopes=$token['scopes']??tt_square_oauth_scopes();if(is_array($scopes))$scopes=implode(' ',$scopes);$scopes=substr((string)$scopes,0,1024);$expires=(string)($token['expires_at']??'');$expiresSql=$expires!==''?gmdate('Y-m-d H:i:s',strtotime($expires)?:time()):null;$env=function_exists('tt_square_env')?tt_square_env():'production';$status='connected';
    $sql='INSERT INTO `square_seller_connections` (`account_id`,`environment`,`merchant_id`,`access_token_enc`,`refresh_token_enc`,`scopes`,`expires_at`,`status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,?, ?,UTC_TIMESTAMP(),UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE `environment`=VALUES(`environment`),`merchant_id`=VALUES(`merchant_id`),`access_token_enc`=VALUES(`access_token_enc`),`refresh_token_enc`=VALUES(`refresh_token_enc`),`scopes`=VALUES(`scopes`),`expires_at`=VALUES(`expires_at`),`status`=VALUES(`status`),`updated_at`=UTC_TIMESTAMP()';
    $s=tt_prepare($db,$sql);$s->bind_param('isssssss',$uid,$env,$merchant,$accessEnc,$refreshEnc,$scopes,$expiresSql,$status);if(!$s->execute()){ $e=$s->error;$s->close();throw new RuntimeException($e);}$s->close();
}
function tt_payout_summary(mysqli $db,int $uid):array{
    $gross=0;$reserved=0;$paid=0;$requests=[];if(!tt_payout_schema_ready($db))return compact('gross','reserved','paid','requests')+['available'=>0];
    $s=tt_prepare($db,"SELECT COALESCE(SUM(`amount_micros`),0) FROM `ad_money_ledger` WHERE `beneficiary_account_id`=? AND `kind`='display_owner'");$s->bind_param('i',$uid);$s->execute();$s->bind_result($gross);$s->fetch();$s->close();$gross=(int)$gross;
    $s=tt_prepare($db,"SELECT COALESCE(SUM(CASE WHEN `status` IN ('pending','queued','approved','processing','dispatched') THEN `amount_micros` ELSE 0 END),0),COALESCE(SUM(CASE WHEN `status`='paid' THEN `amount_micros` ELSE 0 END),0) FROM `payout_requests` WHERE `account_id`=?");$s->bind_param('i',$uid);$s->execute();$s->bind_result($reserved,$paid);$s->fetch();$s->close();$reserved=(int)$reserved;$paid=(int)$paid;
    $s=tt_prepare($db,'SELECT `id`,`amount_micros`,`method`,`destination_ref`,`status`,`provider_reference`,`note`,`created_at`,`updated_at`,`paid_at` FROM `payout_requests` WHERE `account_id`=? ORDER BY `id` DESC LIMIT 50');$s->bind_param('i',$uid);$s->execute();$requests=$s->get_result()->fetch_all(MYSQLI_ASSOC);$s->close();
    return ['gross'=>$gross,'reserved'=>$reserved,'paid'=>$paid,'available'=>max(0,$gross-$reserved-$paid),'requests'=>$requests];
}
function tt_payout_request(mysqli $db,int $uid,int $amountMicros,string $method='square'):int{
    if(!tt_payout_schema_ready($db))throw new RuntimeException('Payout schema is not installed.');if($amountMicros<10000)throw new RuntimeException('Minimum payout request is $0.01.');
    $conn=tt_square_seller_connection($db,$uid);if($method==='square'&&(!$conn||($conn['status']??'')!=='connected'))throw new RuntimeException('Connect a Square seller account before requesting a Square payout.');
    $db->begin_transaction();try{$sum=tt_payout_summary($db,$uid);if($amountMicros>$sum['available'])throw new RuntimeException('Requested amount exceeds your available balance.');$dest=$method==='square'?(string)$conn['merchant_id']:'';$status='pending';$s=tt_prepare($db,'INSERT INTO `payout_requests` (`account_id`,`amount_micros`,`method`,`destination_ref`,`status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,UTC_TIMESTAMP(),UTC_TIMESTAMP())');$s->bind_param('iisss',$uid,$amountMicros,$method,$dest,$status);if(!$s->execute())throw new RuntimeException($s->error);$id=(int)$db->insert_id;$s->close();$db->commit();return $id;}catch(Throwable $e){@$db->rollback();throw $e;}
}
function tt_payout_dispatch_optional(mysqli $db,int $requestId,int $uid,int $amountMicros,string $merchantId):bool{
    $url=trim((string)(getenv('TABLETIME_PAYOUT_DISPATCH_URL')?:''));$secret=trim((string)(getenv('TABLETIME_PAYOUT_DISPATCH_SECRET')?:''));if($url===''||$secret===''||!function_exists('curl_init'))return false;
    $payload=['payout_request_id'=>$requestId,'account_id'=>$uid,'amount_micros'=>$amountMicros,'method'=>'square','square_merchant_id'=>$merchantId,'created_at'=>gmdate('c')];$raw=json_encode($payload,JSON_UNESCAPED_SLASHES);$sig=hash_hmac('sha256',$raw,$secret);$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_POST=>true,CURLOPT_POSTFIELDS=>$raw,CURLOPT_HTTPHEADER=>['Content-Type: application/json','X-Tabletime-Signature: sha256='.$sig],CURLOPT_CONNECTTIMEOUT=>5,CURLOPT_TIMEOUT=>12]);$resp=curl_exec($ch);$code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);curl_close($ch);$ok=$resp!==false&&$code>=200&&$code<300;$status=$ok?'queued':'failed';$excerpt=substr((string)$resp,0,1000);$attempt=1;$s=tt_prepare($db,'INSERT INTO `payout_dispatches` (`payout_request_id`,`attempt`,`status`,`http_status`,`response_excerpt`,`created_at`) VALUES (?,?,?,?,?,UTC_TIMESTAMP())');$s->bind_param('iisis',$requestId,$attempt,$status,$code,$excerpt);@$s->execute();$s->close();if($ok){$s=tt_prepare($db,"UPDATE `payout_requests` SET `status`='queued',`updated_at`=UTC_TIMESTAMP() WHERE `id`=? AND `account_id`=?");$s->bind_param('ii',$requestId,$uid);@$s->execute();$s->close();}return $ok;
}
?>
