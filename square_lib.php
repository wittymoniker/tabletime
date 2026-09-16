<?php
require_once __DIR__.'/ad_lib.php';
function tt_square_env():string{$v=strtolower(trim((string)(getenv('SQUARE_ENV')?:'production')));return $v==='production'?'production':'sandbox';}
function tt_square_base_url():string{return tt_square_env()==='production'?'https://connect.squareup.com':'https://connect.squareupsandbox.com';}
function tt_square_api_version():string{return (string)(getenv('SQUARE_API_VERSION')?:'2026-08-19');}
function tt_square_application_id():string{return (string)(getenv('SQUARE_APPLICATION_ID')?:'');}
function tt_square_location_id():string{return (string)(getenv('SQUARE_LOCATION_ID')?:'');}
function tt_square_ad_variation_id():string{return (string)(getenv('SQUARE_AD_VARIATION_ID')?:'');}
function tt_square_access_token():string{return trim((string)(getenv('SQUARE_ACCESS_TOKEN')?:''));}
function tt_square_webhook_signature_key():string{return trim((string)(getenv('SQUARE_WEBHOOK_SIGNATURE_KEY')?:''));}
function tt_square_webhook_url():string{return (string)(getenv('SQUARE_WEBHOOK_URL')?:'https://eski-web.org/tabletime/square_webhook.php');}
function tt_square_ready():bool{return tt_square_access_token()!==''&&tt_square_location_id()!==''&&tt_square_ad_variation_id()!=='';}
function tt_square_request(string $method,string $path,?array $body=null):array{
 $token=tt_square_access_token();if($token==='')throw new RuntimeException('Square access token is not configured.');if(!function_exists('curl_init'))throw new RuntimeException('PHP cURL is required for Square checkout.');
 $ch=curl_init(tt_square_base_url().$path);$headers=['Authorization: Bearer '.$token,'Square-Version: '.tt_square_api_version(),'Content-Type: application/json','Accept: application/json'];curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPHEADER=>$headers,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>20]);if($body!==null)curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($body,JSON_UNESCAPED_SLASHES));$raw=curl_exec($ch);$err=curl_error($ch);$code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);curl_close($ch);if($raw===false||$err!=='')throw new RuntimeException('Square request failed.');$data=json_decode((string)$raw,true);if(!is_array($data))$data=[];if($code<200||$code>=300){$msg=(string)($data['errors'][0]['detail']??$data['errors'][0]['code']??'Square API rejected the request.');throw new RuntimeException($msg);}return $data;
}
function tt_square_verify_webhook(string $raw,string $signature):bool{$key=tt_square_webhook_signature_key();if($key===''||$signature==='')return false;$calc=base64_encode(hash_hmac('sha256',tt_square_webhook_url().$raw,$key,true));return hash_equals($calc,$signature);}
function tt_square_ad_package_credits():int{return 1000;}
function tt_square_ad_package_cents():int{return 500;}
?>
