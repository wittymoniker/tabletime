<?php
/**
 * Tabletime browser/session bootstrap.
 *
 * PHP's native session remains a fast cache, but authentication does not depend
 * on one PHP worker retaining its session files. A signed first-party tt_login12
 * cookie can reconstruct the account session from MySQL after worker rotation.
 */
$ttSessionMode=strtolower(trim((string)(getenv('TABLETIME_SESSION_MODE') ?: '72h')));
$ttSessionHours=(int)(getenv('TABLETIME_SESSION_HOURS') ?: 72);
if($ttSessionMode==='infinite'){
    // Browsers do not support a literal never-expiring cookie consistently. Use
    // a browser-safe long expiry and renew it only near expiry.
    $ttSessionLifetime=34560000; // 400 days, rolling = effectively infinite.
}else{
    $ttSessionHours=max(1,min(9600,$ttSessionHours));
    $ttSessionLifetime=$ttSessionHours*3600;
}
if (!defined('TT_SESSION_LIFETIME')) define('TT_SESSION_LIFETIME', $ttSessionLifetime);
if (!defined('TT_SESSION_NAME')) define('TT_SESSION_NAME', 'TTSESSID12');
if (!defined('TT_AUTH_COOKIE')) define('TT_AUTH_COOKIE', 'tt_login12');
if (!defined('TT_AUTH_LEGACY_COOKIE')) define('TT_AUTH_LEGACY_COOKIE', 'tt_auth');

// Tabletime responses vary by authentication cookies and must never be replayed
// from a browser/edge cache as a stale pre-login redirect or page.
if(!headers_sent()){header('Cache-Control: private, no-store, max-age=0, must-revalidate');header('Pragma: no-cache');header('Vary: Cookie',false);}

function tt_request_is_https(): bool {
    $host=strtolower(trim((string)($_SERVER['HTTP_HOST']??'')));$host=preg_replace('/:\d+$/','',$host)??$host;
    if(in_array($host,['eski-web.org','www.eski-web.org'],true)) return true;
    if (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') return true;
    $xfp=trim((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''));
    if ($xfp!=='') {
        $first=strtolower(trim(explode(',',$xfp,2)[0]));
        if ($first==='https') return true;
    }
    $forwarded=strtolower((string)($_SERVER['HTTP_FORWARDED'] ?? ''));
    if ($forwarded!=='' && preg_match('/(?:^|[;,\s])proto\s*=\s*"?https"?(?:[;,\s]|$)/i',$forwarded)) return true;
    if (strtolower((string)($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '')) === 'on') return true;
    // Wasmer / reverse proxies may only expose the public port/host.
    if ((string)($_SERVER['SERVER_PORT'] ?? '') === '443') return true;
    return false;
}

function tt_enforce_canonical_host(): void {
    $host=strtolower(trim((string)($_SERVER['HTTP_HOST'] ?? '')));
    $host=preg_replace('/:\d+$/','',$host) ?? $host;
    if($host!=='www.eski-web.org' || headers_sent()) return;
    $method=strtoupper((string)($_SERVER['REQUEST_METHOD'] ?? 'GET'));
    // Never rewrite an authentication/action POST.  A GET/HEAD can be safely
    // canonicalized before a form is shown; POSTs are accepted on the host that
    // received them and the next GET is canonicalized instead.
    if(!in_array($method,['GET','HEAD'],true)) return;
    $uri=(string)($_SERVER['REQUEST_URI'] ?? '/');
    if($uri==='' || !str_starts_with($uri,'/')) $uri='/';
    header('Cache-Control: private, no-store, max-age=0, must-revalidate');
    header('Location: https://eski-web.org'.$uri,true,302);
    exit;
}
tt_enforce_canonical_host();

function tt_cookie_domain(): string { return ''; }
function tt_host_cookie_options(int $expires,bool $httpOnly=true): array {
    return ['expires'=>$expires,'path'=>'/','secure'=>tt_request_is_https(),'httponly'=>$httpOnly,'samesite'=>'Lax'];
}
function tt_cookie_options(int $expires,bool $httpOnly=true): array {
    $o=[
        'expires'=>$expires,
        'path'=>'/',
        'secure'=>tt_request_is_https(),
        'httponly'=>$httpOnly,
        'samesite'=>'Lax'
    ];
    $domain=tt_cookie_domain();if($domain!=='')$o['domain']=$domain;
    return $o;
}
function tt_cookie_expire_both_scopes(string $name,bool $httpOnly=true): void {
    if(headers_sent())return;
    $base=['expires'=>time()-3600,'path'=>'/','secure'=>tt_request_is_https(),'httponly'=>$httpOnly,'samesite'=>'Lax'];
    // Clear the old host-only scope first.
    setcookie($name,'',$base);
    $domain=tt_cookie_domain();if($domain!==''){ $base['domain']=$domain; setcookie($name,'',$base); }
}

function tt_cookie_expire_legacy_scopes(string $name,bool $httpOnly=true): void {
    if(headers_sent())return;
    $base=['expires'=>time()-3600,'path'=>'/','secure'=>tt_request_is_https(),'httponly'=>$httpOnly,'samesite'=>'Lax'];
    setcookie($name,'',$base);
    $host=strtolower((string)($_SERVER['HTTP_HOST']??''));$host=preg_replace('/:\d+$/','',$host)??$host;
    if($host==='eski-web.org'||$host==='www.eski-web.org'){$domainBase=$base;$domainBase['domain']='eski-web.org';setcookie($name,'',$domainBase);}
    unset($_COOKIE[$name]);
}
function tt_clear_legacy_browser_auth_state(): void {
    static $done=false;if($done)return;$done=true;
    $legacy=[
        'TTSESSID','TTSESSID2','TTSESSID3','TTSESSID4','TTSESSID5','TTSESSID6','TTSESSID7','TTSESSID8','TTSESSID9','TTSESSID10',
        'tt_auth','tt_auth2','tt_auth3','tt_auth4','tt_auth5','tt_auth6','tt_auth7','tt_auth8','tt_auth9','tt_auth9_host','tt_auth10','tt_auth10_host','tt_remember',
        'tt_age18_passed','tabletime_age18','age18_confirmed','tt_age18_v2','tt_age18_v3','tt_age18_v4','tt_age18_v5','tt_age18_v6','tt_age18_v7','tt_age18_v8','tt_age18_v9','tt_age18_v10'
    ];
    foreach($legacy as $name)if(isset($_COOKIE[$name]))tt_cookie_expire_legacy_scopes($name,true);
}

// Legacy cookies are intentionally ignored, not expired on every request.
// Repeated bulk Set-Cookie deletion can exceed reverse-proxy header budgets and
// prevent the new persistent login cookie from reaching the browser.
if (session_status() !== PHP_SESSION_ACTIVE) {
    // Avoid collisions with another PHP application hosted on eski-web.org.
    if (session_name() !== TT_SESSION_NAME) @session_name(TT_SESSION_NAME);
    @ini_set('session.gc_maxlifetime', (string)TT_SESSION_LIFETIME);
    @ini_set('session.cookie_lifetime', (string)TT_SESSION_LIFETIME);
    @ini_set('session.use_strict_mode','1');
    $ttSessionCookie=[
        'lifetime'=>TT_SESSION_LIFETIME,
        'path'=>'/',
        'secure'=>tt_request_is_https(),
        'httponly'=>true,
        'samesite'=>'Lax'
    ];
    $ttSessionDomain=tt_cookie_domain();if($ttSessionDomain!=='')$ttSessionCookie['domain']=$ttSessionDomain;
    session_set_cookie_params($ttSessionCookie);
    @session_start();
    if (!isset($_SESSION) || !is_array($_SESSION)) $_SESSION=[];
}

function tt_b64url_encode(string $raw): string { return rtrim(strtr(base64_encode($raw),'+/','-_'),'='); }
function tt_b64url_decode(string $raw): string|false {
    if(!preg_match('/^[A-Za-z0-9_-]+$/',$raw))return false;
    $pad=strlen($raw)%4;if($pad)$raw.=str_repeat('=',4-$pad);
    return base64_decode(strtr($raw,'-_','+/'),true);
}

function tt_auth_secret(): string {
    $explicit=(string)(getenv('TABLETIME_AUTH_SECRET') ?: '');
    if($explicit!=='') return hash('sha256','tabletime-auth-secret-v1|'.$explicit,true);
    // Portable deterministic fallback: tied to the database credential set. This
    // keeps auth cookies valid across Wasmer workers without adding a mandatory
    // new setting. Changing DB credentials intentionally invalidates old logins.
    require_once __DIR__.'/db.php';
    global $DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    return hash('sha256','tabletime-auth-fallback-v1|'.$DATABASE_HOST.'|'.$DATABASE_NAME.'|'.$DATABASE_USER.'|'.$DATABASE_PASS,true);
}


/**
 * Stable cross-worker form token.
 *
 * Do not derive CSRF tokens from the rolling auth cookie: the auth cookie is
 * intentionally renewed on every authenticated request, which would make a
 * form token stale between render and POST.  Bind instead to the account id,
 * a purpose string, and the server-side auth secret.  This survives PHP worker
 * rotation and cookie renewal but remains unforgeable without the server key.
 */
function tt_form_token(string $purpose='form'): string {
    $uid=(int)($_SESSION['id']??0);
    if($uid>0){
        return hash_hmac('sha256','tabletime-form-v1|'.$purpose.'|'.$uid,tt_auth_secret());
    }
    // Public/non-account fallback for the rare form that does not require login.
    $key='tt_form_token_'.preg_replace('/[^a-z0-9_\-]/i','_',strtolower($purpose));
    if(empty($_SESSION[$key])){
        try{$_SESSION[$key]=bin2hex(random_bytes(24));}
        catch(Throwable $e){$_SESSION[$key]=hash('sha256',session_id().'|'.$purpose.'|'.microtime(true));}
    }
    return (string)$_SESSION[$key];
}
function tt_form_token_valid(string $posted,string $purpose='form'): bool {
    return $posted!=='' && hash_equals(tt_form_token($purpose),$posted);
}

function tt_auth_cookie_key(string $passwordHash): string {
    // Per-account DB state is the stable secret. This survives Wasmer worker
    // rotation and provider environment rematerialization; changing a password
    // immediately invalidates every outstanding cookie for that account.
    return hash('sha256',"tabletime-auth-v2\0".$passwordHash,true);
}
function tt_auth_cookie_build(int $accountId,string $passwordHash,int $expires=0): string {
    if($accountId<=0||$passwordHash==='')return '';
    if($expires<=0)$expires=time()+TT_SESSION_LIFETIME;
    try{$nonce=bin2hex(random_bytes(12));}catch(Throwable $e){$nonce=substr(hash('sha256',microtime(true).'|'.session_id()),0,24);}
    $payload=$accountId.'.'.$expires.'.'.$nonce;
    $sig=hash_hmac('sha256',$payload,tt_auth_cookie_key($passwordHash));
    return 'v12.'.tt_b64url_encode($payload).'.'.$sig;
}

function tt_auth_cookie_parse(string $cookie): ?array {
    $parts=explode('.',$cookie,3);
    if(count($parts)!==3||!in_array($parts[0],['v1','v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12'],true)||!preg_match('/^[a-f0-9]{64}$/',$parts[2]))return null;
    $decoded=tt_b64url_decode($parts[1]);if($decoded===false)return null;
    $p=explode('.',$decoded,3);if(count($p)!==3)return null;
    $uid=(int)$p[0];$exp=(int)$p[1];$nonce=(string)$p[2];
    if($uid<=0||$exp<=time()||$exp>time()+TT_SESSION_LIFETIME+86400||!preg_match('/^[a-f0-9]{24}$/',$nonce))return null;
    return ['version'=>$parts[0],'uid'=>$uid,'expires'=>$exp,'nonce'=>$nonce,'payload'=>$decoded,'sig'=>$parts[2]];
}

function tt_auth_cookie_set(string $value,int $expires=0): void {
    if($expires<=0)$expires=$value===''?time()-3600:time()+TT_SESSION_LIFETIME;
    if($value===''){
        if(!headers_sent())setcookie(TT_AUTH_COOKIE,'',tt_host_cookie_options(time()-3600,true));
        unset($_COOKIE[TT_AUTH_COOKIE]);return;
    }
    // One canonical host-only persistent cookie. The site canonicalizes GET/HEAD
    // to eski-web.org before protected content, so cross-host duplicate cookies
    // are unnecessary and can race/proxy-truncate Set-Cookie headers.
    if(!headers_sent())setcookie(TT_AUTH_COOKIE,$value,tt_host_cookie_options($expires,true));
    $_COOKIE[TT_AUTH_COOKIE]=$value;
}

function tt_auth_cookie_issue(int $accountId,string $passwordHash): bool {
    $expires=time()+TT_SESSION_LIFETIME;$cookie=tt_auth_cookie_build($accountId,$passwordHash,$expires);
    if($cookie==='')return false;tt_auth_cookie_set($cookie,$expires);return true;
}

function tt_auth_cookie_should_renew(string $cookie,int $uid,string $passwordHash): bool {
    $p=tt_auth_cookie_parse($cookie);if(!$p)return true;
    if((int)$p['uid']!==$uid||!tt_auth_cookie_verify($p,$passwordHash))return true;
    if((string)($p['version']??'')!=='v12')return true;
    $window=min(43200,max(900,(int)(TT_SESSION_LIFETIME/4)));
    return ((int)$p['expires']-time()) <= $window;
}

function tt_auth_cookie_verify(array $parsed,string $passwordHash): bool {
    if($passwordHash==='')return false;
    if(in_array((string)($parsed['version']??''),['v2','v3','v4','v5','v6','v7','v8','v9','v10','v11','v12'],true))$sig=hash_hmac('sha256',(string)$parsed['payload'],tt_auth_cookie_key($passwordHash));
    else $sig=hash_hmac('sha256',(string)$parsed['payload']."\0".$passwordHash,tt_auth_secret());
    return hash_equals($sig,(string)$parsed['sig']);
}

function tt_login_handoff_build(int $accountId,string $passwordHash,int $lifetime=120): string {
    if($accountId<=0||$passwordHash==='')return '';
    $expires=time()+max(30,min(300,$lifetime));
    try{$nonce=bin2hex(random_bytes(12));}catch(Throwable $e){$nonce=substr(hash('sha256',microtime(true).'|'.session_id().'|handoff'),0,24);}
    $payload=$accountId.'.'.$expires.'.'.$nonce;
    $sig=hash_hmac('sha256',"tabletime-login-handoff-v1\0".$payload,tt_auth_cookie_key($passwordHash));
    return 'h1.'.tt_b64url_encode($payload).'.'.$sig;
}
function tt_login_handoff_parse(string $ticket): ?array {
    $parts=explode('.',$ticket,3);
    if(count($parts)!==3||$parts[0]!=='h1'||!preg_match('/^[a-f0-9]{64}$/',$parts[2]))return null;
    $decoded=tt_b64url_decode($parts[1]);if($decoded===false)return null;
    $p=explode('.',$decoded,3);if(count($p)!==3)return null;
    $uid=(int)$p[0];$exp=(int)$p[1];$nonce=(string)$p[2];$now=time();
    if($uid<=0||$exp<$now-5||$exp>$now+305||!preg_match('/^[a-f0-9]{24}$/',$nonce))return null;
    return ['uid'=>$uid,'expires'=>$exp,'payload'=>$decoded,'sig'=>$parts[2]];
}
function tt_login_handoff_verify(array $parsed,string $passwordHash): bool {
    if($passwordHash==='')return false;
    $sig=hash_hmac('sha256',"tabletime-login-handoff-v1\0".(string)$parsed['payload'],tt_auth_cookie_key($passwordHash));
    return hash_equals($sig,(string)$parsed['sig']);
}

/** Short-lived bearer used only by the Calls page so an already-open Safari
 * call can recover from a lost cookie without dropping media/signaling. */
function tt_call_api_token_build(int $accountId,string $passwordHash,int $lifetime=14400): string {
    if($accountId<=0||$passwordHash==='')return '';$expires=time()+max(300,min(86400,$lifetime));
    $payload=$accountId.'.'.$expires;$sig=hash_hmac('sha256',"tabletime-call-api-v1\0".$payload,tt_auth_cookie_key($passwordHash));
    return 'ca1.'.tt_b64url_encode($payload).'.'.$sig;
}
function tt_call_api_token_parse(string $token): ?array {
    $parts=explode('.',$token,3);if(count($parts)!==3||$parts[0]!=='ca1'||!preg_match('/^[a-f0-9]{64}$/',$parts[2]))return null;
    $raw=tt_b64url_decode($parts[1]);if($raw===false)return null;$p=explode('.',$raw,2);if(count($p)!==2)return null;
    $uid=(int)$p[0];$exp=(int)$p[1];$now=time();if($uid<=0||$exp<$now||$exp>$now+86460)return null;
    return ['uid'=>$uid,'expires'=>$exp,'payload'=>$raw,'sig'=>$parts[2]];
}
function tt_restore_call_api_token(string $token): bool {
    $parsed=tt_call_api_token_parse($token);if(!$parsed)return false;require_once __DIR__.'/db.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;if(empty($TT_DB_CONFIGURED)||!class_exists('mysqli'))return false;
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno)return false;$db->set_charset('utf8mb4');
    $row=tt_account_restore_query($db,(int)$parsed['uid']);if(!$row){$db->close();return false;}
    $sig=hash_hmac('sha256',"tabletime-call-api-v1\0".(string)$parsed['payload'],tt_auth_cookie_key((string)$row['password']));
    if(!hash_equals($sig,(string)$parsed['sig'])){$db->close();return false;}
    tt_session_fill_from_account($row);$db->close();return true;
}

function tt_remember_cookie_set(string $value): void {
    $expires=$value==='' ? time()-3600 : time()+TT_SESSION_LIFETIME;
    if($value===''){tt_cookie_expire_both_scopes('tt_remember',true);unset($_COOKIE['tt_remember']);return;}
    if(!headers_sent())setcookie('tt_remember',$value,tt_cookie_options($expires,true));
    $_COOKIE['tt_remember']=$value;
}

function tt_remember_schema_ready(mysqli $db,bool $allowRepair=false): bool {
    $s=$db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='auth_tokens'");
    if(!$s || !$s->execute()) { if($s)$s->close(); return false; }
    $s->bind_result($n);$s->fetch();$s->close();
    if((int)$n<1) {
        if(!$allowRepair)return false;
        $sql="CREATE TABLE IF NOT EXISTS `auth_tokens` (`id` bigint unsigned NOT NULL AUTO_INCREMENT,`account_id` int unsigned NOT NULL,`selector` char(24) NOT NULL,`token_hash` char(64) NOT NULL,`created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`last_used_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,`revoked_at` datetime DEFAULT NULL,PRIMARY KEY(`id`),UNIQUE KEY `uq_auth_selector`(`selector`),KEY `idx_auth_account`(`account_id`,`revoked_at`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        if(!$db->query($sql)) return false;
    }
    foreach(['account_id','selector','token_hash','created_at','last_used_at','revoked_at'] as $col){
        $q=$db->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='auth_tokens' AND column_name=?");
        if(!$q)return false;$q->bind_param('s',$col);if(!$q->execute()){$q->close();return false;}$q->bind_result($c);$q->fetch();$q->close();if((int)$c<1)return false;
    }
    return true;
}

function tt_remember_issue(mysqli $db,int $accountId): bool {
    // DB-backed remember tokens are retained for explicit revocation and backward
    // compatibility, but failure to create/use this optional table no longer
    // prevents login because tt_auth is schema-independent.
    $allowRepair=((string)getenv('TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE')==='1');
    if($accountId<=0 || !tt_remember_schema_ready($db,$allowRepair)) return false;
    $selector=bin2hex(random_bytes(12));$token=bin2hex(random_bytes(32));$hash=hash('sha256',$token);
    $s=$db->prepare('INSERT INTO `auth_tokens` (`account_id`,`selector`,`token_hash`) VALUES (?,?,?)');
    if(!$s)return false;$s->bind_param('iss',$accountId,$selector,$hash);$ok=$s->execute();$s->close();
    if($ok)tt_remember_cookie_set($selector.'.'.$token);
    return $ok;
}

function tt_remember_revoke(mysqli $db,string $cookie): void {
    $selector=strtok($cookie,'.');if(!$selector||strlen($selector)!==24||!tt_remember_schema_ready($db,false))return;
    $s=$db->prepare('UPDATE `auth_tokens` SET `revoked_at`=UTC_TIMESTAMP() WHERE `selector`=?');if(!$s)return;$s->bind_param('s',$selector);$s->execute();$s->close();
}

require_once __DIR__.'/age_gate_session.php';

function tt_session_fill_from_account(array $row): void {
    @session_regenerate_id(true);
    $_SESSION['loggedin']=true;$_SESSION['id']=(int)$row['id'];$_SESSION['name']=(string)$row['username'];$_SESSION['tt_auth_hash']=(string)($row['password']??'');
    foreach(['friends','posts','groups','events','colors','votes','forums','tags','aboutcontent','files'] as $k)$_SESSION[$k]=$row[$k]??'';
    // Authentication itself includes 18+ attestation, so logged-in restoration
    // must never bounce through a second age gate.
    tt_age_session_set(true);
}

function tt_account_restore_query(mysqli $db,int $uid): ?array {
    $s=$db->prepare('SELECT `id`,`username`,`password`,`friends`,`posts`,`groups`,`events`,`colors`,`votes`,`forums`,`tags`,`aboutcontent`,`files` FROM `accounts` WHERE `id`=? LIMIT 1');
    if(!$s)return null;$s->bind_param('i',$uid);if(!$s->execute()){$s->close();return null;}$r=$s->get_result()?->fetch_assoc();$s->close();return is_array($r)?$r:null;
}

function tt_restore_login_handoff(string $ticket): bool {
    $parsed=tt_login_handoff_parse($ticket);if(!$parsed)return false;
    require_once __DIR__.'/db.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if(empty($TT_DB_CONFIGURED)||!class_exists('mysqli'))return false;
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno)return false;$db->set_charset('utf8mb4');
    $row=tt_account_restore_query($db,(int)$parsed['uid']);
    if(!$row||!tt_login_handoff_verify($parsed,(string)$row['password'])){$db->close();return false;}
    tt_session_fill_from_account($row);
    $existing=(string)($_COOKIE[TT_AUTH_COOKIE]??'');
    $existingParsed=$existing!==''?tt_auth_cookie_valid_for($existing,(int)$row['id'],(string)$row['password']):null;
    if(!is_array($existingParsed))tt_auth_cookie_issue((int)$row['id'],(string)$row['password']);
    tt_age_cookie_set(true);
    @$db->query('UPDATE `accounts` SET `last_login_at`=UTC_TIMESTAMP() WHERE `id`='.(int)$row['id']);
    $db->close();
    return true;
}

function tt_auth_cookie_valid_for(string $cookie,int $uid,string $passwordHash): ?array {
    $p=tt_auth_cookie_parse($cookie);if(!$p)return null;
    if((int)$p['uid']!==$uid||!tt_auth_cookie_verify($p,$passwordHash))return null;
    return $p;
}

function tt_auth_cookie_near_expiry(array $p): bool {
    $window=min(43200,max(900,(int)(TT_SESSION_LIFETIME/4)));
    return ((int)$p['expires']-time()) <= $window;
}

function tt_restore_signed_auth(): bool {
    $candidates=[];$v=(string)($_COOKIE[TT_AUTH_COOKIE]??'');if($v!=='')$candidates[]=$v;
    if(!$candidates)return false;
    require_once __DIR__.'/db.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if(empty($TT_DB_CONFIGURED)||!class_exists('mysqli'))return false;
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno)return false;$db->set_charset('utf8mb4');
    foreach($candidates as $cookie){
        $parsed=tt_auth_cookie_parse($cookie);if(!$parsed)continue;
        $row=tt_account_restore_query($db,(int)$parsed['uid']);
        if(!$row||!tt_auth_cookie_verify($parsed,(string)$row['password']))continue;
        tt_session_fill_from_account($row);
        $db->close();
        // Keep one authoritative byte-for-byte token for the whole browser session.
        // Only mint a new token near expiry; otherwise heal both cookie scopes with
        // the exact verified value so parallel page/API requests cannot race.
        if((string)($parsed['version']??'')!=='v12'||tt_auth_cookie_near_expiry($parsed)){
            tt_auth_cookie_issue((int)$row['id'],(string)$row['password']);
        }
        return true;
    }
    $db->close();
    // Only clear after both independent cookies failed verification.
    tt_auth_cookie_set('');
    return false;
}

function tt_restore_legacy_remember(): bool {
    if(empty($_COOKIE['tt_remember']))return false;
    $parts=explode('.',(string)$_COOKIE['tt_remember'],2);
    if(count($parts)!==2||strlen($parts[0])!==24||strlen($parts[1])!==64)return false;
    require_once __DIR__.'/db.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if(empty($TT_DB_CONFIGURED)||!class_exists('mysqli'))return false;
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno)return false;$db->set_charset('utf8mb4');
    if(!tt_remember_schema_ready($db,false)){$db->close();return false;}
    $s=$db->prepare('SELECT t.`account_id`,t.`token_hash`,a.`id`,a.`username`,a.`password`,a.`friends`,a.`posts`,a.`groups`,a.`events`,a.`colors`,a.`votes`,a.`forums`,a.`tags`,a.`aboutcontent`,a.`files` FROM `auth_tokens` t JOIN `accounts` a ON a.`id`=t.`account_id` WHERE t.`selector`=? AND t.`revoked_at` IS NULL LIMIT 1');
    if(!$s){$db->close();return false;}$s->bind_param('s',$parts[0]);if(!$s->execute()){$s->close();$db->close();return false;}
    $r=$s->get_result()?->fetch_assoc();$s->close();
    if(!$r || !hash_equals((string)$r['token_hash'],hash('sha256',$parts[1]))){$db->close();tt_remember_cookie_set('');return false;}
    tt_session_fill_from_account($r);
    $u=$db->prepare('UPDATE `auth_tokens` SET `last_used_at`=UTC_TIMESTAMP() WHERE `selector`=?');if($u){$u->bind_param('s',$parts[0]);$u->execute();$u->close();}
    @$db->query('UPDATE `accounts` SET `last_login_at`=UTC_TIMESTAMP() WHERE `id`='.(int)$r['id']);$db->close();
    // Migrate an old remember-token login onto the new browser-independent cookie.
    tt_auth_cookie_issue((int)$r['id'],(string)$r['password']);
    return true;
}

function tt_refresh_authenticated_session(): void {
    if(empty($_SESSION['loggedin'])||empty($_SESSION['id']))return;
    $uid=(int)$_SESSION['id'];$hash=(string)($_SESSION['tt_auth_hash']??'');
    if($hash===''){
        require_once __DIR__.'/db.php';
        global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
        if(!empty($TT_DB_CONFIGURED)&&class_exists('mysqli')){
            $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);
            if(!$db->connect_errno){$db->set_charset('utf8mb4');$row=tt_account_restore_query($db,$uid);$db->close();if($row){$hash=(string)$row['password'];$_SESSION['tt_auth_hash']=$hash;}}
        }
    }
    if($hash!==''){
        $current=(string)($_COOKIE[TT_AUTH_COOKIE]??'');
        $parsed=$current!==''?tt_auth_cookie_valid_for($current,$uid,$hash):null;
        if(!is_array($parsed))tt_auth_cookie_issue($uid,$hash);
        elseif((string)($parsed['version']??'')!=='v12'||tt_auth_cookie_near_expiry($parsed))tt_auth_cookie_issue($uid,$hash);
    }
    tt_age_session_set(true);if((string)($_COOKIE[TT_AGE_COOKIE]??'')!=='1')tt_age_cookie_set(true);
}

function tt_restore_persistent_session(): void {
    // The signed persistent cookie is authoritative. Native PHP session files are
    // only a cache and may disappear when a Wasmer worker is recycled.
    if(tt_restore_signed_auth()){tt_refresh_authenticated_session();return;}
    // A just-authenticated request may still have a valid native session before
    // the persistent cookie is observed by the next request; issue it once.
    if(!empty($_SESSION['loggedin'])&&!empty($_SESSION['id'])){tt_refresh_authenticated_session();return;}
    if(tt_restore_legacy_remember())tt_refresh_authenticated_session();
}

// The first protected GET can consume the signed login ticket directly.
$ttBootstrapTicket=(string)($_GET['tt_login']??'');
$ttBootstrapTicketAccepted=false;
if($ttBootstrapTicket!==''){
    $ttBootstrapTicketAccepted=tt_restore_login_handoff($ttBootstrapTicket);
    if($ttBootstrapTicketAccepted){
        $GLOBALS['TT_LOGIN_HANDOFF_CONSUMED']=true;
        header('Referrer-Policy: no-referrer');
    }
}
if(!$ttBootstrapTicketAccepted)tt_restore_persistent_session();
?>
