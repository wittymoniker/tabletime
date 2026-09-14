<?php
require_once __DIR__.'/core.php';
header('Content-Type: application/json; charset=utf-8');
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);echo json_encode(['ok'=>false,'error'=>'POST required']);exit;}
$postId=max(0,(int)($_POST['post_id']??0));if($postId<=0){http_response_code(400);echo json_encode(['ok'=>false,'error'=>'invalid post']);exit;}
$db=tt_db_open(false);if(!tt_schema_table_exists($db,'post_impressions')){$db->close();echo json_encode(['ok'=>false,'ready'=>false]);exit;}
// Count only posts the current visitor is allowed to see.
$logged=!empty($_SESSION['loggedin']);if($logged){[$vis,$params,$types]=tt_visible_where('p');$q=tt_prepare($db,'SELECT p.`id` FROM `posts` p WHERE p.`id`=? AND '.$vis.' LIMIT 1');$bindTypes='i'.$types;$bindParams=array_merge([$postId],$params);$q->bind_param($bindTypes,...$bindParams);}else{$q=tt_prepare($db,"SELECT p.`id` FROM `posts` p WHERE p.`id`=? AND p.`scope` IN ('global','public','public ') LIMIT 1");$q->bind_param('i',$postId);}$q->execute();$q->store_result();$visible=$q->num_rows>0;$q->close();if(!$visible){$db->close();http_response_code(404);echo json_encode(['ok'=>false,'error'=>'not visible']);exit;}
$viewer=(int)($_SESSION['id']??0);$identity=$viewer>0?'acct:'.$viewer:'browser:'.tt_delay_client_token();$bucket=(int)floor(time()/600);$key=hash('sha256','post-hit|'.$postId.'|'.$identity.'|'.$bucket);$viewerDb=$viewer>0?$viewer:null;
$s=tt_prepare($db,'INSERT IGNORE INTO `post_impressions` (`post_id`,`viewer_account_id`,`impression_key`) VALUES (?,?,?)');$s->bind_param('iis',$postId,$viewerDb,$key);$s->execute();$counted=$s->affected_rows>0;$s->close();$db->close();echo json_encode(['ok'=>true,'counted'=>$counted]);
?>