<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/schema_compat.php';
require_once __DIR__.'/session_bootstrap.php';

function tt_h($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function tt_fail(string $message, int $status=500, string $title='Tabletime'): void {
    http_response_code($status);
    echo '<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>'.tt_h($title).'</title></head><body><main class="content"><h1><a href="/">TABLETIME</a></h1><section class="card"><h2>'.tt_h($title).'</h2><p>'.tt_h($message).'</p><p><a href="home.php">Tabletime home</a> · <a href="setup.php">Database setup</a> · <a href="/">Eski home</a></p></section></main></body></html>';
    exit;
}
function tt_db_open(bool $upgrade=false): mysqli {
    global $TT_DB_CONFIGURED,$DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME;
    if (empty($TT_DB_CONFIGURED)) tt_fail('Database is not configured. Open Database setup.',503,'Database unavailable');
    if (!class_exists('mysqli')) tt_fail('PHP mysqli support is not loaded.',503,'Database unavailable');
    $db=@new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);
    if ($db->connect_errno) tt_fail('Could not connect to the Tabletime database.',503,'Database unavailable');
    if (!$db->set_charset('utf8mb4')) { $db->close(); tt_fail('Could not enable utf8mb4 on the database connection.',503,'Database unavailable'); }
    if ($upgrade) {
        $notes=[];
        if (!tt_upgrade_runtime_schema($db,$notes)) { $db->close(); tt_fail('The existing Tabletime schema could not be upgraded safely. Open Database setup.',503,'Database schema'); }
    }
    return $db;
}
function tt_prepare(mysqli $db,string $sql): mysqli_stmt {
    $stmt=$db->prepare($sql);
    if (!$stmt) throw new RuntimeException('Database query could not be prepared: '.$db->error);
    return $stmt;
}
function tt_require_login(): void {
    if (empty($_SESSION['loggedin']) || empty($_SESSION['id'])) { header('Location: login.php'); exit; }
}
function tt_nav(string $active=''): void {
    $links=['home.php'=>'Home','post.php'=>'Posts','create.php'=>'Create','tags.php'=>'Tags','messages.php'=>'Messages','event.php'=>'Events','forum.php'=>'Forums','group.php'=>'Groups','profile.php'=>'People','file.php'=>'Files','calls.php'=>'Calls','account.php'=>'Account','extend.php'=>'Extend'];
    echo '<nav class="navtop"><div><h1><a href="/">TABLETIME</a></h1>';
    foreach($links as $href=>$label) echo '<a href="'.$href.'"'.($active===$href?' aria-current="page"':'').'>'.tt_h($label).'</a>';
    echo '<a href="logout.php">Logout</a></div></nav><script src="tabletime.js" defer></script>';
}

function tt_member_list(string $raw): array { $parts=preg_split('/[;,\\n\\r]+/',trim($raw))?:[];$out=[];foreach($parts as $v){$v=trim($v);if($v!==''&&!in_array($v,$out,true))$out[]=$v;}return $out; }
function tt_member_string(array $members): string { return implode(';',array_values(array_unique(array_filter(array_map('trim',$members),fn($v)=>$v!=='')))); }
function tt_scope_members(mysqli $db,string $type,?int $scopeId,string $privateMembers=''): array {
    $me=(string)($_SESSION['name']??'');$members=[$me];
    if($type==='private')$members=array_merge($members,tt_member_list($privateMembers));
    elseif(in_array($type,['group','event'],true)&&$scopeId){$table=$type==='group'?'groups':'events';$q=$db->prepare('SELECT `members` FROM `'.$table.'` WHERE `id`=?');if($q){$q->bind_param('i',$scopeId);$q->execute();$q->bind_result($raw);if($q->fetch())$members=array_merge($members,tt_member_list((string)$raw));$q->close();}}
    return array_values(array_unique(array_filter($members,fn($v)=>$v!=='')));
}
function tt_user_ids(mysqli $db,array $names): array {if(!$names)return[];$out=[];foreach($names as $n){$q=$db->prepare('SELECT `id` FROM `accounts` WHERE `username`=? LIMIT 1');if(!$q)continue;$q->bind_param('s',$n);$q->execute();$q->bind_result($id);if($q->fetch())$out[$n]=(int)$id;$q->close();}return $out;}
function tt_notify(mysqli $db,int $accountId,string $kind,string $title,string $body,string $url=''): void {$q=$db->prepare('INSERT INTO `notifications` (`account_id`,`kind`,`title`,`body`,`url`) VALUES (?,?,?,?,?)');if(!$q)return;$q->bind_param('issss',$accountId,$kind,$title,$body,$url);$q->execute();$q->close();}

function tt_visible_where(string $alias='p'): array {
    $user=(string)($_SESSION['name']??'');
    $scope="$alias.`scope`";$name="$alias.`name`";$rec="$alias.`recipients`";
    $where="($scope IN ('global','public','public ') OR $name=? OR ($scope='private' AND ($rec=? OR $rec LIKE ? OR $rec LIKE ? OR $rec LIKE ?)))";
    return [$where,[$user,$user,$user.';%','%;'.$user.';%','%;'.$user],'sssss'];
}
?>