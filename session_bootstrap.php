<?php
/** Long-lived Tabletime sessions. The opaque remember token is hashed server-side. */
const TT_SESSION_LIFETIME = 315576000; // ~10 years; renewed on activity.
if (session_status() !== PHP_SESSION_ACTIVE) {
    @ini_set('session.gc_maxlifetime', (string)TT_SESSION_LIFETIME);
    @ini_set('session.cookie_lifetime', (string)TT_SESSION_LIFETIME);
    $secure = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off');
    session_set_cookie_params(['lifetime'=>TT_SESSION_LIFETIME,'path'=>'/','secure'=>$secure,'httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
function tt_remember_cookie_set(string $value): void {
    $secure = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off');
    setcookie('tt_remember',$value,['expires'=>time()+TT_SESSION_LIFETIME,'path'=>'/','secure'=>$secure,'httponly'=>true,'samesite'=>'Lax']);
}
function tt_remember_issue(mysqli $db,int $accountId): void {
    $selector=bin2hex(random_bytes(12));$token=bin2hex(random_bytes(32));$hash=hash('sha256',$token);
    $s=$db->prepare('INSERT INTO `auth_tokens` (`account_id`,`selector`,`token_hash`) VALUES (?,?,?)');
    if(!$s)return;$s->bind_param('iss',$accountId,$selector,$hash);if($s->execute())tt_remember_cookie_set($selector.'.'.$token);$s->close();
}
function tt_remember_revoke(mysqli $db,string $cookie): void {
    $selector=strtok($cookie,'.');if(!$selector||strlen($selector)!==24)return;
    $s=$db->prepare('UPDATE `auth_tokens` SET `revoked_at`=UTC_TIMESTAMP() WHERE `selector`=?');if(!$s)return;$s->bind_param('s',$selector);$s->execute();$s->close();
}
function tt_restore_persistent_session(): void {
    if(!empty($_SESSION['loggedin'])||empty($_COOKIE['tt_remember']))return;
    $parts=explode('.',(string)$_COOKIE['tt_remember'],2);if(count($parts)!==2||strlen($parts[0])!==24||strlen($parts[1])!==64)return;
    require_once __DIR__.'/db.php';require_once __DIR__.'/schema_compat.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if(empty($TT_DB_CONFIGURED)||!class_exists('mysqli'))return;
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($db->connect_errno)return;$db->set_charset('utf8mb4');$notes=[];if(!tt_upgrade_runtime_schema($db,$notes)){$db->close();return;}
    $s=$db->prepare('SELECT t.`account_id`,t.`token_hash`,a.`username`,a.`friends`,a.`posts`,a.`groups`,a.`events`,a.`colors`,a.`votes`,a.`forums`,a.`tags`,a.`aboutcontent`,a.`files` FROM `auth_tokens` t JOIN `accounts` a ON a.`id`=t.`account_id` WHERE t.`selector`=? AND t.`revoked_at` IS NULL LIMIT 1');
    if(!$s){$db->close();return;}$s->bind_param('s',$parts[0]);$s->execute();$row=$s->get_result()?->fetch_assoc();$s->close();
    if(!$row||!hash_equals((string)$row['token_hash'],hash('sha256',$parts[1]))){$db->close();tt_remember_cookie_set('');return;}
    session_regenerate_id(true);$_SESSION['loggedin']=true;$_SESSION['id']=(int)$row['account_id'];$_SESSION['name']=(string)$row['username'];foreach(['friends','posts','groups','events','colors','votes','forums','tags','aboutcontent','files'] as $k)$_SESSION[$k]=$row[$k]??'';
    $uid=(int)$row['account_id'];$sel=$parts[0];$u=$db->prepare('UPDATE `auth_tokens` SET `last_used_at`=UTC_TIMESTAMP() WHERE `selector`=?');if($u){$u->bind_param('s',$sel);$u->execute();$u->close();}$db->query('UPDATE `accounts` SET `last_login_at`=UTC_TIMESTAMP() WHERE `id`='.$uid);$db->close();tt_remember_cookie_set((string)$_COOKIE['tt_remember']);
}
tt_restore_persistent_session();
?>