<?php
require_once __DIR__ . '/db.php';
require_once __DIR__.'/session_bootstrap.php';
$postId=max(0,(int)($_GET['post']??0));
if($postId<=0){http_response_code(404);exit('Attachment not found.');}
$db=new mysqli($DATABASE_HOST,$DATABASE_USER,$DATABASE_PASS,$DATABASE_NAME);
if($db->connect_errno){http_response_code(503);exit('Database unavailable.');}
$db->set_charset('utf8mb4');
$q=$db->prepare("SELECT p.scope,p.name,p.recipients,a.filename,a.mime_type,a.size_bytes,a.data FROM posts p JOIN post_attachments a ON a.post_id=p.id WHERE p.id=? LIMIT 1");
$q->bind_param('i',$postId);$q->execute();$q->store_result();
if($q->num_rows!==1){$q->close();$db->close();http_response_code(404);exit('Attachment not found.');}
$q->bind_result($scope,$owner,$recipients,$filename,$mime,$size,$data);$q->fetch();$q->close();$db->close();
$scope=trim((string)$scope);$viewer=(string)($_SESSION['name']??'');$allowed=in_array($scope,['public','global'],true);
if(!$allowed && !empty($_SESSION['loggedin'])){
  if(hash_equals((string)$owner,$viewer))$allowed=true;
  else {$list=preg_split('/[;,\s]+/',(string)$recipients,-1,PREG_SPLIT_NO_EMPTY);$allowed=in_array($viewer,$list,true);}
}
if(!$allowed){http_response_code(403);exit('This attachment is private.');}
$filename=preg_replace('/[\r\n"\\]+/','_',basename((string)$filename));if($filename==='')$filename='attachment.bin';
header('Content-Type: '.((string)$mime!==''?(string)$mime:'application/octet-stream'));
header('Content-Length: '.strlen((string)$data));
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, max-age=300');
echo $data;
