<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/maintenance.php';
require_once __DIR__ . '/federation_lib.php';
require_once __DIR__ . '/schema_compat.php';
require_once __DIR__.'/session_bootstrap.php';
function tt_registration_page(string $message,int $status=400):void{http_response_code($status);$safe=htmlspecialchars($message,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');echo '<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · Registration</title></head><body><main class="content"><h1><a href="/">TABLETIME</a></h1><section class="card"><h2>Registration</h2><p>'.$safe.'</p><p><a href="register.php">Back to registration</a> · <a href="setup.php">Database setup</a> · <a href="/">Home</a></p></section></main></body></html>';exit;}
function tt_registration_schema_ready(mysqli $db):bool{$notes=[];if(!tt_upgrade_runtime_schema($db,$notes))return false;return count(tt_verify_accounts_runtime_columns($db))===0;}
if($_SERVER['REQUEST_METHOD']!=='POST'||!isset($_POST['submit'])){header('Location: register.php');exit;}
if((int)($_POST['test']??-1)!==((int)($_POST['no1']??0)+(int)($_POST['no2']??0)))tt_registration_page('Invalid captcha entered.');
$username=trim((string)($_POST['username']??''));$passwordRaw=(string)($_POST['password']??'');$email=trim((string)($_POST['email']??''));
if($username===''||$passwordRaw===''||!filter_var($email,FILTER_VALIDATE_EMAIL))tt_registration_page('Please provide a username, password, and valid email address.');
if(strlen($username)>50)tt_registration_page('Username is too long.');
if(!$TT_DB_CONFIGURED)tt_registration_page('The database is not attached to this deployment yet. Open Database setup.',503);if(!class_exists('mysqli'))tt_registration_page('PHP started without the mysqli extension.',503);
$con=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);if($con->connect_errno)tt_registration_page('Tabletime could not connect to MySQL. Open Database setup for diagnostics.',503);$con->set_charset('utf8mb4');
if(!tt_registration_schema_ready($con)){ $con->close();tt_registration_page('The accounts schema is not compatible with this Tabletime build. Open Database setup once, then retry.',503); }
try{if(!tt_preflight_write($con,12288,true)){throw new RuntimeException('capacity guard');}}catch(Throwable $e){$con->close();tt_registration_page('Database capacity check failed or the database is full.',503);}
$stmt=$con->prepare('SELECT `id` FROM `accounts` WHERE `username`=? LIMIT 1');if(!$stmt){$e=$con->error;$con->close();tt_registration_page('Could not query accounts: '.$e,503);}$stmt->bind_param('s',$username);if(!$stmt->execute()){$e=$stmt->error;$stmt->close();$con->close();tt_registration_page('Could not check username: '.$e,503);}$stmt->store_result();if($stmt->num_rows>0){$stmt->close();$con->close();tt_registration_page('Name exists, please choose another.');}$stmt->close();
$r=$con->query('SELECT COUNT(*) AS `c` FROM `accounts`');if(!$r){$e=$con->error;$con->close();tt_registration_page('Could not inspect accounts: '.$e,503);}$count=(int)($r->fetch_assoc()['c']??0);$r->free();
$hash=password_hash($passwordRaw,PASSWORD_DEFAULT);if($hash===false){$con->close();tt_registration_page('Password hashing is unavailable.',503);}$empty='';$hostmode=$count===0?tt_node_tier_level():0;$protected=$count===0?1:0;$delay='00:00:00';$ip=substr((string)($_SERVER['REMOTE_ADDR']??''),0,255);
$sql='INSERT INTO `accounts` (`username`,`password`,`email`,`friends`,`posts`,`groups`,`events`,`forums`,`tags`,`messages`,`colors`,`votes`,`files`,`hostips`,`hostmode`,`aboutcontent`,`delay`,`ip`,`created_at`,`last_login_at`,`protected_account`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),UTC_TIMESTAMP(),?)';
$stmt=$con->prepare($sql);if(!$stmt){$e=$con->error;$con->close();tt_registration_page('The accounts insert could not be prepared after schema migration. Database says: '.$e,503);}$stmt->bind_param('ssssssssssssssisssi',$username,$hash,$email,$empty,$empty,$empty,$empty,$empty,$empty,$empty,$empty,$empty,$empty,$empty,$hostmode,$empty,$delay,$ip,$protected);
if(!$stmt->execute()){$errno=$stmt->errno;$e=$stmt->error;$stmt->close();$con->close();tt_registration_page($errno===1062?'That username is already registered.':'The account could not be written: '.$e,$errno===1062?400:503);}$stmt->close();$con->close();header('Location: login.php?registered=1');exit;
?>