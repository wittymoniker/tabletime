<?php
require_once __DIR__.'/session_bootstrap.php';
if(!empty($_COOKIE['tt_remember'])){
    require_once __DIR__.'/db.php';require_once __DIR__.'/schema_compat.php';
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if(!empty($TT_DB_CONFIGURED)&&class_exists('mysqli')){$db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if(!$db->connect_errno){$db->set_charset('utf8mb4');$notes=[];if(tt_upgrade_runtime_schema($db,$notes))tt_remember_revoke($db,(string)$_COOKIE['tt_remember']);$db->close();}}
}
tt_remember_cookie_set('');$_SESSION=[];if(ini_get('session.use_cookies')){$p=session_get_cookie_params();setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);}session_destroy();header('Location: index.php');exit;
?>