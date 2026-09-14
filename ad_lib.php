<?php
/* Tabletime natural ad-credit ledger.
   One ad credit equals exactly one counted impression. Credits are earned by
   hosting another user's counted ad impression and spent to serve your own. */
function tt_ad_schema_ready(mysqli $db):bool{return tt_schema_column_exists($db,'accounts','ad_credit_mills')&&tt_schema_table_exists($db,'ad_boosts')&&tt_schema_table_exists($db,'ad_impressions');}
function tt_ad_require_schema(mysqli $db):void{if(!tt_ad_schema_ready($db))tt_fail('Advertising tables are not installed yet. Temporarily enable TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE=1 and run Tabletime Database setup/maintenance once, then turn it back off.',503,'Advertising setup');}
function tt_ad_mills_per_credit():int{return 1000;} // internal storage only: 1000 legacy mills = 1 ad credit
function tt_ad_csrf():string{if(empty($_SESSION['tt_ad_csrf']))$_SESSION['tt_ad_csrf']=bin2hex(random_bytes(24));return (string)$_SESSION['tt_ad_csrf'];}
function tt_ad_check_csrf():void{if(!hash_equals((string)($_SESSION['tt_ad_csrf']??''),(string)($_POST['csrf']??'')))tt_fail('Invalid form token.',400,'Advertising');}
function tt_ad_credits(mysqli $db,int $uid):int{$s=tt_prepare($db,'SELECT `ad_credit_mills` FROM `accounts` WHERE `id`=?');$s->bind_param('i',$uid);$s->execute();$s->bind_result($m);$s->fetch();$s->close();return intdiv(max(0,(int)$m),tt_ad_mills_per_credit());}
function tt_ad_grant_post_credits(mysqli $db,int $uid,int $credits=3):int{
 $credits=max(0,$credits);if($uid<1||$credits<1||!tt_schema_column_exists($db,'accounts','ad_credit_mills'))return 0;$mills=$credits*tt_ad_mills_per_credit();
 $s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`+? WHERE `id`=?');$s->bind_param('ii',$mills,$uid);if(!$s->execute()){ $e=$s->error;$s->close();throw new RuntimeException('Could not grant post ad credits: '.$e);}$ok=$s->affected_rows===1;$s->close();return $ok?$credits:0;
}
function tt_ad_apply_boost(mysqli $db,int $uid,int $postId,int $credits,string $targetUser='',string $targetScope='public',string $targetTags=''):int{
 $credits=max(0,$credits);if($credits<1)return 0;$mills=$credits*tt_ad_mills_per_credit();
 $s=tt_prepare($db,'SELECT `name` FROM `posts` WHERE `id`=? LIMIT 1');$s->bind_param('i',$postId);$s->execute();$s->bind_result($name);if(!$s->fetch()||!hash_equals((string)($_SESSION['name']??''),(string)$name)){$s->close();throw new RuntimeException('You may boost only your own post.');}$s->close();
 $s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`-? WHERE `id`=? AND `ad_credit_mills`>=?');$s->bind_param('iii',$mills,$uid,$mills);$s->execute();if($s->affected_rows!==1){$s->close();throw new RuntimeException('Not enough ad credits.');}$s->close();
 $targetUser=substr(trim($targetUser),0,50);$targetScope=in_array($targetScope,['private','public','global'],true)?$targetScope:'public';$targetTags=substr(trim($targetTags),0,1024);
 $s=tt_prepare($db,'INSERT INTO `ad_boosts` (`post_id`,`buyer_account_id`,`target_username`,`target_scope`,`target_tags`,`budget_mills`,`spent_mills`,`status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,0,\'active\',UTC_TIMESTAMP(),UTC_TIMESTAMP())');$s->bind_param('iisssi',$postId,$uid,$targetUser,$targetScope,$targetTags,$mills);if(!$s->execute())throw new RuntimeException($s->error);$id=(int)$db->insert_id;$s->close();return $id;
}
function tt_ad_attachment_html(array $r):string{
 $file=(string)($r['file']??'');if($file==='')return '';$mime=(string)($r['attachment_mime']??'');$pid=(int)($r['id']??0);$u=htmlspecialchars($file,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');
 if(str_starts_with(strtolower($mime),'image/'))return '<a class="post-image-link" href="post.php#post-'.$pid.'"><img class="post-image" src="'.$u.'" alt="Attached image for post '.htmlspecialchars((string)($r['title']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" loading="lazy"></a>';
 return '<p><a href="'.$u.'" rel="noopener">Attachment</a></p>';
}
?>