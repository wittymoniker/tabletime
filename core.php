<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/schema_compat.php';
require_once __DIR__ . '/social_controls.php';
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

function tt_reply_mode_for_post_type(string $type): string {
    $type=strtolower(trim($type));
    return in_array($type,['post','media','message','event','group','forum'],true)?$type:'post';
}
function tt_reply_identity_tag(int $postId): string { return 'ID#:'.max(0,$postId); }
function tt_reply_parent_id_from_tags(string $tags): int {
    if(preg_match('/(?:^|[;,\r\n]\s*)ID#:\s*(\d+)\b/i',$tags,$m))return (int)$m[1];
    // Legacy Tabletime reply tags are recognized so old data can be migrated.
    if(preg_match('/(?:^|[;,\r\n]\s*)(?:reply[-_ ]to[-_ ]post|target[-_ ]post)\s*(?:=|:)\s*(\d+)\b/i',$tags,$m))return (int)$m[1];
    return 0;
}
function tt_reply_affinity_for_view(int $view,bool $allScopes=false): float {
    // Continuous 0..1 blend: private edge = ordinary order, public = half thread
    // affinity, global edge = strongest reply-chain clustering. All-scopes defaults
    // to public-strength clustering until the user moves the scope slider.
    if($allScopes && !isset($_GET['view']))return 0.5;
    return max(0.0,min(1.0,($view+256)/512.0));
}
function tt_sort_reply_threads(array $rows,float $affinity): array {
    $n=count($rows);if($n<2||$affinity<=0.000001)return $rows;
    $byId=[];$orig=[];$parent=[];$latestByRoot=[];
    foreach($rows as $i=>$r){$id=(int)($r['id']??0);if($id<=0)continue;$byId[$id]=$r;$orig[$id]=$i;$parent[$id]=tt_reply_parent_id_from_tags((string)($r['tags']??''));}
    $rootOf=[];
    $resolve=function(int $id) use (&$resolve,&$rootOf,$parent,$byId):int{
        if(isset($rootOf[$id]))return $rootOf[$id];$seen=[];$cur=$id;
        for($depth=0;$depth<64;$depth++){
            if(isset($seen[$cur]))break;$seen[$cur]=true;$p=(int)($parent[$cur]??0);
            if($p<=0)return $rootOf[$id]=$cur;
            // Even if the parent is outside the current result set, its numeric id
            // remains the stable thread key so siblings still group together.
            if(!isset($byId[$p]))return $rootOf[$id]=$p;
            $cur=$p;
        }
        return $rootOf[$id]=$cur;
    };
    $groups=[];
    foreach($rows as $r){$id=(int)($r['id']??0);if($id<=0)continue;$root=$resolve($id);$r['_reply_parent']=(int)($parent[$id]??0);$r['_thread_root']=$root;$groups[$root][]=$r;$ts=strtotime((string)($r['dt']??''))?:0;$latestByRoot[$root]=max($latestByRoot[$root]??0,$ts);}
    uasort($groups,function($a,$b)use($latestByRoot){$ra=(int)($a[0]['_thread_root']??0);$rb=(int)($b[0]['_thread_root']??0);$c=($latestByRoot[$rb]??0)<=>($latestByRoot[$ra]??0);return $c?:($rb<=>$ra);});
    $threadPos=[];$pos=0;
    foreach($groups as &$g){usort($g,function($a,$b){$pa=(int)($a['_reply_parent']??0);$pb=(int)($b['_reply_parent']??0);if($pa===0&&$pb!==0)return -1;if($pb===0&&$pa!==0)return 1;$ta=strtotime((string)($a['dt']??''))?:0;$tb=strtotime((string)($b['dt']??''))?:0;return ($ta<=>$tb)?:((int)$a['id']<=>(int)$b['id']);});foreach($g as $r)$threadPos[(int)$r['id']]=$pos++;}unset($g);
    foreach($rows as &$r){$id=(int)($r['id']??0);$o=(float)($orig[$id]??0);$t=(float)($threadPos[$id]??$o);$r['_reply_parent']=(int)($parent[$id]??0);$r['_thread_root']=$resolve($id);$r['_blend_order']=(1.0-$affinity)*$o+$affinity*$t;}unset($r);
    usort($rows,function($a,$b){$d=((float)($a['_blend_order']??0))<=>((float)($b['_blend_order']??0));if($d!==0)return $d;return strcmp((string)($b['dt']??''),(string)($a['dt']??''));});
    return $rows;
}
function tt_reply_target_user(string $author,string $recipients,string $viewer=''): string {
    $author=trim($author);$viewer=trim($viewer);
    if($author!=='' && ($viewer==='' || !hash_equals($viewer,$author)))return $author;
    foreach(tt_member_list($recipients) as $name){if($name!=='' && ($viewer==='' || !hash_equals($viewer,$name)))return $name;}
    return $author;
}
function tt_compose_url(string $intent,int $postId,string $author,string $type,string $title='',string $scope='public',string $recipient=''): string {
    $intent=strtolower(trim($intent))==='comment'?'comment':'reply';
    $mode=$intent==='comment'?'comment':tt_reply_mode_for_post_type($type);
    $targetUser=trim($recipient)!==''?trim($recipient):trim($author);
    $params=[
        'mode'=>$mode,
        'intent'=>$intent,
        'target_post'=>max(0,$postId),
        'target_user'=>$targetUser,
        'target_type'=>tt_reply_mode_for_post_type($type),
        'target_title'=>trim($title),
        'target_scope'=>trim($scope),
    ];
    return 'create.php?'.http_build_query($params,'','&',PHP_QUERY_RFC3986);
}

function tt_visible_where(string $alias='p'): array {
    $user=(string)($_SESSION['name']??'');
    $scope="$alias.`scope`";$name="$alias.`name`";$rec="$alias.`recipients`";
    $where="($scope IN ('global','public','public ') OR $name=? OR ($scope='private' AND ($rec=? OR $rec LIKE ? OR $rec LIKE ? OR $rec LIKE ?)))";
    return [$where,[$user,$user,$user.';%','%;'.$user.';%','%;'.$user],'sssss'];
}
?>