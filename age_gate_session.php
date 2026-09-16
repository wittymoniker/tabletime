<?php
/** Tabletime persistent 18+ self-attestation gate. */
if (!defined('TT_AGE_COOKIE')) define('TT_AGE_COOKIE', 'tt_age18_v12');
if (!defined('TT_AGE_COOKIE_LIFETIME')) define('TT_AGE_COOKIE_LIFETIME', defined('TT_SESSION_LIFETIME') ? TT_SESSION_LIFETIME : 315576000);

function tt_age_secure_cookie(): bool {
    if(function_exists('tt_request_is_https')) return tt_request_is_https();
    if(!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') return true;
    $xfp=trim((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    if(strtolower(trim(explode(',',$xfp,2)[0] ?? ''))==='https')return true;
    return (string)($_SERVER['SERVER_PORT']??'')==='443';
}
function tt_age_cookie_options(int $expires): array {$o=['expires'=>$expires,'path'=>'/','secure'=>tt_age_secure_cookie(),'httponly'=>true,'samesite'=>'Lax'];if(function_exists('tt_cookie_domain')){$d=tt_cookie_domain();if($d!=='')$o['domain']=$d;}return $o;}
function tt_age_cookie_set(bool $passed): void {
    $expires=$passed ? time()+TT_AGE_COOKIE_LIFETIME : time()-3600;$value=$passed?'1':'';
    // One canonical cookie prevents reverse proxies/browser stacks from having to
    // preserve several equivalent Set-Cookie headers during the age redirect.
    if(!headers_sent())setcookie(TT_AGE_COOKIE,$value,tt_age_cookie_options($expires));
    if($passed)$_COOKIE[TT_AGE_COOKIE]='1';else unset($_COOKIE[TT_AGE_COOKIE]);
}
function tt_age_clear_legacy_cookies():void{
    foreach(['tt_age18_passed','tabletime_age18','age18_confirmed','tt_age18_v2','tt_age18_v3','tt_age18_v4','tt_age18_v5','tt_age18_v6','tt_age18_v7','tt_age18_v8','tt_age18_v9','tt_age18_v10','tt_age18_v11'] as $name){if(isset($_COOKIE[$name])&&!headers_sent())setcookie($name,'',tt_age_cookie_options(time()-3600));unset($_COOKIE[$name]);}
}
function tt_age_session_set(bool $passed): void {foreach(['tt_age18_passed','age_verified','age18_confirmed','over18'] as $key){if($passed)$_SESSION[$key]=1;else unset($_SESSION[$key]);}}
function tt_age_is_passed(): bool {
    // Every authenticated Tabletime session was created by an 18+ attestation.
    if(!empty($_SESSION['loggedin'])&&!empty($_SESSION['id'])){tt_age_session_set(true);return true;}
    foreach(['tt_age18_passed','age_verified','age18_confirmed','over18'] as $key)if(!empty($_SESSION[$key]))return true;
    if(hash_equals('1',(string)($_COOKIE[TT_AGE_COOKIE]??''))){tt_age_session_set(true);return true;}
    // Accept old cookies long enough to migrate them to the canonical cookie.
    foreach(['tt_age18_passed','tabletime_age18','age18_confirmed'] as $name){if(hash_equals('1',(string)($_COOKIE[$name]??''))){tt_age_session_set(true);tt_age_cookie_set(true);return true;}}
    return false;
}
function tt_age_column_exists(mysqli $db,string $column):bool{$sql="SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='accounts' AND COLUMN_NAME=? LIMIT 1";$s=$db->prepare($sql);if(!$s)return false;$s->bind_param('s',$column);if(!$s->execute()){$s->close();return false;}$s->store_result();$ok=$s->num_rows>0;$s->close();return $ok;}
function tt_age_persist_optional(mysqli $db,int $accountId):void{if($accountId<=0)return;foreach(['age18_attested','age_attested','adult_attested','is_18_plus'] as $column){if(!tt_age_column_exists($db,$column))continue;$sql='UPDATE `accounts` SET `'.$column.'`=1 WHERE `id`=?';$s=$db->prepare($sql);if($s){$s->bind_param('i',$accountId);@$s->execute();$s->close();}break;}foreach(['age18_attested_at','age_attested_at','adult_attested_at'] as $column){if(!tt_age_column_exists($db,$column))continue;$sql='UPDATE `accounts` SET `'.$column.'`=COALESCE(`'.$column.'`,UTC_TIMESTAMP()) WHERE `id`=?';$s=$db->prepare($sql);if($s){$s->bind_param('i',$accountId);@$s->execute();$s->close();}break;}}
function tt_age_mark_passed(?mysqli $db=null,?int $accountId=null):void{tt_age_session_set(true);tt_age_cookie_set(true);if($db instanceof mysqli&&(int)$accountId>0){try{tt_age_persist_optional($db,(int)$accountId);}catch(Throwable $e){}}}
function tt_age_clear():void{tt_age_session_set(false);tt_age_cookie_set(false);tt_age_clear_legacy_cookies();}
function tt_age_is_gate_path(string $path):bool{$base=strtolower(basename((string)(parse_url($path,PHP_URL_PATH)??$path)));return in_array($base,['age_check.php','age_gate.php','agecheck.php'],true);}
function tt_age_safe_return(string $raw,string $fallback='home.php'):string{$raw=trim(str_replace(["\r","\n","\0"],'',$raw));if($raw===''||str_contains($raw,'://')||str_starts_with($raw,'//'))return $fallback;$p=@parse_url($raw);if($p===false||isset($p['scheme'])||isset($p['host'])||isset($p['user'])||isset($p['pass']))return $fallback;$path=(string)($p['path']??'');if($path==='')$path=$fallback;foreach(explode('/',str_replace('\\','/',$path)) as $seg)if($seg==='..')return $fallback;if(tt_age_is_gate_path($path))return $fallback;$out=$path;if(isset($p['query'])&&$p['query']!=='')$out.='?'.$p['query'];if(isset($p['fragment'])&&$p['fragment']!=='')$out.='#'.$p['fragment'];return $out;}
function tt_age_return_with_confirmation(string $target):string{$target=tt_age_safe_return($target,'post.php');$frag='';$hash=strpos($target,'#');if($hash!==false){$frag=substr($target,$hash);$target=substr($target,0,$hash);}return $target.(str_contains($target,'?')?'&':'?').'tt_age_confirmed=1'.$frag;}
function tt_age_current_return():string{$uri=(string)($_SERVER['REQUEST_URI']??'');if(tt_age_is_gate_path($uri))return 'home.php';return tt_age_safe_return($uri,'home.php');}
function tt_age_route_is_exempt():bool{$path=(string)(parse_url((string)($_SERVER['REQUEST_URI']??''),PHP_URL_PATH)??'');$base=strtolower(basename($path));if($base==='')return false;if(str_ends_with($base,'_webhook.php'))return true;return in_array($base,['age_check.php','age_gate.php','agecheck.php','login.php','register.php','authenticate.php','auth_handoff.php','newuser.php','logout.php','style.php','captcha verify.php','health.php','runtime-probe.php','square_oauth_callback.php'],true);}
function tt_age_gate_page(string $returnTo,int $status=428):void{http_response_code($status);header('Cache-Control: no-store, max-age=0');$safe=htmlspecialchars($returnTo,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');echo '<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · 18+ confirmation</title></head><body><main class="content"><h1><a href="/">TABLETIME</a></h1><section class="card"><h2>18+ confirmation required</h2><p>This action was not performed. Confirm age first, then retry it.</p><p><a href="age_check.php?return='.rawurlencode($returnTo).'">Confirm 18+ and continue</a></p><p class="meta">Return target: '.$safe.'</p></section></main></body></html>';exit;}
function tt_age_route_requires_login():bool{$path=(string)(parse_url((string)($_SERVER['REQUEST_URI']??''),PHP_URL_PATH)??'');$base=strtolower(basename($path));return in_array($base,['home.php','account.php','adcredits.php','boost.php','calls.php','call_signal.php','call_presence.php','notifications.php','create.php','createnew.php','delete.php','earnings.php','event.php','federation_admin.php','file.php','forum.php','friend.php','group.php','messages.php','person_rate.php','profile_media.php','rate.php','square_checkout.php','square_oauth_start.php','square_setup.php','statsmap.php','updateusr.php','usrchange.php','colorpick.php','maintenance_status.php'],true);}
function tt_age_require_access():void{
    if(tt_age_route_is_exempt())return;
    $returnTo=tt_age_current_return();$method=strtoupper((string)($_SERVER['REQUEST_METHOD']??'GET'));
    // Login always wins over age on protected routes. This makes a lost worker
    // session deterministic instead of bouncing login -> age -> login.
    if(tt_age_route_requires_login()&&(empty($_SESSION['loggedin'])||empty($_SESSION['id']))){header('Cache-Control: no-store, max-age=0');header('Location: login.php?return='.rawurlencode($returnTo),true,($method==='GET'||$method==='HEAD')?302:303);exit;}
    // The age confirmation redirect carries one self-attestation marker. It is
    // deliberately equivalent to checking the box, and guarantees that Public
    // Posts cannot redirect back to age_check if a browser drops the cookie on
    // the immediate 303 navigation.
    if((string)($_GET['tt_age_confirmed']??'')==='1'){tt_age_mark_passed();return;}
    if(tt_age_is_passed())return;
    if($method==='GET'||$method==='HEAD'){header('Cache-Control: no-store, max-age=0');header('Location: age_check.php?return='.rawurlencode($returnTo),true,302);exit;}
    tt_age_gate_page($returnTo,428);
}
?>
