<?php
require_once __DIR__.'/core.php';tt_require_login();
$db=tt_db_open(false);$id=max(0,(int)($_GET['id']??0));if($id<=0)tt_fail('Profile media not found.',404,'Profile media');
if(!tt_schema_table_exists($db,'profile_media')){$db->close();tt_fail('Profile media is not enabled on this host yet.',404,'Profile media');}
$s=tt_prepare($db,'SELECT `filename`,`mime_type`,`size_bytes`,`data` FROM `profile_media` WHERE `account_id`=? LIMIT 1');$s->bind_param('i',$id);$s->execute();$s->bind_result($filename,$mime,$size,$data);if(!$s->fetch()){$s->close();$db->close();tt_fail('Profile media not found.',404,'Profile media');}$s->close();$db->close();
$mime=(string)$mime;if(!preg_match('#^(image|video|audio)/[A-Za-z0-9.+-]+$#',$mime))$mime='application/octet-stream';header('Content-Type: '.$mime);header('Content-Length: '.strlen((string)$data));header('Cache-Control: private, max-age=300');header('X-Content-Type-Options: nosniff');$disp=(str_starts_with($mime,'image/')||str_starts_with($mime,'video/')||str_starts_with($mime,'audio/'))?'inline':'attachment';header('Content-Disposition: '.$disp.'; filename="'.str_replace(['"','\r','\n'],'',(string)$filename).'"');echo $data;
?>