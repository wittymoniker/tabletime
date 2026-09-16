<?php
/* Tabletime natural ad-credit ledger.
   One ad credit equals exactly one counted impression. Credits are earned by
   hosting another user's counted ad impression and spent to serve your own. */
function tt_ad_schema_ready(mysqli $db):bool{return tt_schema_column_exists($db,'accounts','ad_credit_mills')&&tt_schema_table_exists($db,'ad_boosts')&&tt_schema_table_exists($db,'ad_impressions');}
function tt_ad_paid_schema_ready(mysqli $db):bool{return tt_schema_column_exists($db,'accounts','ad_paid_credit_mills')&&tt_schema_column_exists($db,'ad_boosts','paid_budget_mills')&&tt_schema_column_exists($db,'ad_boosts','paid_spent_mills')&&tt_schema_table_exists($db,'square_ad_purchases')&&tt_schema_table_exists($db,'ad_money_ledger');}
function tt_ad_require_schema(mysqli $db):void{if(!tt_ad_schema_ready($db))tt_fail('Advertising tables are not installed yet. Temporarily enable TABLETIME_ALLOW_RUNTIME_SCHEMA_UPGRADE=1 and run Tabletime Database setup/maintenance once, then turn it back off.',503,'Advertising setup');}
function tt_ad_mills_per_credit():int{return 1000;} // internal storage only: 1000 legacy mills = 1 ad credit
function tt_ad_csrf():string{
 // Stable across the rolling 72-hour/infinite auth-cookie renewal and Wasmer
 // worker changes.  The old implementation hashed the auth cookie itself,
 // which changed at the start of the POST and invalidated its own form token.
 if(function_exists('tt_form_token'))return tt_form_token('advertising');
 if(empty($_SESSION['tt_ad_csrf']))$_SESSION['tt_ad_csrf']=bin2hex(random_bytes(24));
 return (string)$_SESSION['tt_ad_csrf'];
}
function tt_ad_check_csrf():void{
 $posted=(string)($_POST['csrf']??'');
 if(function_exists('tt_form_token_valid')&&tt_form_token_valid($posted,'advertising'))return;
 // Backward compatibility with a form that was rendered immediately before
 // deploying this release, when its session-local token is still available.
 $legacy=(string)($_SESSION['tt_ad_csrf']??'');
 if($posted!==''&&$legacy!==''&&hash_equals($legacy,$posted))return;
 tt_fail('Invalid form token. Refresh this page once and try again.',400,'Advertising');
}
function tt_ad_credit_balances(mysqli $db,int $uid):array{
 $paidReady=tt_schema_column_exists($db,'accounts','ad_paid_credit_mills');$sql=$paidReady?'SELECT `ad_credit_mills`,`ad_paid_credit_mills` FROM `accounts` WHERE `id`=?':'SELECT `ad_credit_mills`,0 FROM `accounts` WHERE `id`=?';
 $s=tt_prepare($db,$sql);$s->bind_param('i',$uid);$s->execute();$s->bind_result($natural,$paid);$s->fetch();$s->close();$natural=max(0,(int)$natural);$paid=max(0,(int)$paid);return ['natural'=>intdiv($natural,tt_ad_mills_per_credit()),'paid'=>intdiv($paid,tt_ad_mills_per_credit()),'total'=>intdiv($natural+$paid,tt_ad_mills_per_credit())];
}
function tt_ad_credits(mysqli $db,int $uid):int{return tt_ad_credit_balances($db,$uid)['total'];}
function tt_ad_grant_post_credits(mysqli $db,int $uid,int $credits=3):int{
 $credits=max(0,$credits);if($uid<1||$credits<1||!tt_schema_column_exists($db,'accounts','ad_credit_mills'))return 0;$mills=$credits*tt_ad_mills_per_credit();
 $s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`+? WHERE `id`=?');$s->bind_param('ii',$mills,$uid);if(!$s->execute()){ $e=$s->error;$s->close();throw new RuntimeException('Could not grant post ad credits: '.$e);}$ok=$s->affected_rows===1;$s->close();return $ok?$credits:0;
}
function tt_ad_normalize_target(mysqli $db,string $targetUser,string $targetScope):array{
 $targetUser=trim($targetUser);$targetScope=strtolower(trim($targetScope));
 // Scope words accidentally entered in the username field are treated as scope selectors.
 $scopeAlias=strtolower(ltrim($targetUser,'@'));
 if(in_array($scopeAlias,['private','public','global'],true)){ $targetScope=$scopeAlias;$targetUser=''; }
 if(!in_array($targetScope,['private','public','global'],true))$targetScope='public';
 if($targetUser==='')return ['', $targetScope];
 // Accept @username plus the current and older Tabletime profile URL forms.
 $raw=$targetUser;
 if(preg_match('/[?&](?:user|username)=([^&#]+)/i',$raw,$m))$targetUser=urldecode($m[1]);
 elseif(preg_match('~/(?:profile|people)(?:\.php)?/([^/?#]+)~i',$raw,$m))$targetUser=urldecode($m[1]);
 $targetUser=ltrim(trim($targetUser),'@');
 // Strip fragments/query residue from a pasted partial URL and keep usernames in schema range.
 $targetUser=preg_split('/[?#\s]/',$targetUser,2)[0]??'';$targetUser=substr(trim($targetUser),0,50);
 if($targetUser==='')return ['', $targetScope];
 // Resolve case-insensitively to the canonical username. If it does not resolve, do NOT
 // reject the spend: fall back to untargeted delivery. Targeting is optional metadata,
 // never a condition for being allowed to spend a natural ad credit.
 $s=tt_prepare($db,'SELECT `username` FROM `accounts` WHERE LOWER(`username`)=LOWER(?) LIMIT 1');$s->bind_param('s',$targetUser);$s->execute();$s->bind_result($canonical);$ok=$s->fetch();$s->close();
 if(!$ok)return ['', $targetScope];
 return [(string)$canonical,$targetScope];
}
function tt_ad_apply_boost(mysqli $db,int $uid,int $postId,int $credits,string $targetUser='',string $targetScope='public',string $targetTags=''):int{
 $credits=max(0,$credits);if($credits<1)return 0;$mills=$credits*tt_ad_mills_per_credit();
 $s=tt_prepare($db,'SELECT a.`id`,p.`title`,p.`tags` FROM `posts` p JOIN `accounts` a ON a.`username`=p.`name` WHERE p.`id`=? LIMIT 1');$s->bind_param('i',$postId);$s->execute();$s->bind_result($ownerId,$postTitle,$postTags);if(!$s->fetch()||(int)$ownerId!==$uid){$s->close();throw new RuntimeException('You may boost only your own post.');}$s->close();$postNsfw=(bool)(preg_match('/^(?:\[nsfw\]|nsfw\s*:|mature\s*:|18\+\s*:)/i',(string)$postTitle)||preg_match('/(?:^|[;,\s])(?:nsfw|mature|adult|18\+)(?:$|[;,\s=])/i',(string)$postTags));
 [$targetUser,$targetScope]=tt_ad_normalize_target($db,$targetUser,$targetScope);$targetTags=substr(trim($targetTags),0,1024);
 // Validate targeting before touching balances. If Square-funded schema exists, spend funded impression balance first
 // so cash-backed impressions can be accounted for deterministically.
 $paidMills=0;
 if(tt_ad_paid_schema_ready($db)){
  $s=tt_prepare($db,'SELECT `ad_credit_mills`,`ad_paid_credit_mills` FROM `accounts` WHERE `id`=? FOR UPDATE');$s->bind_param('i',$uid);$s->execute();$s->bind_result($naturalBal,$paidBal);if(!$s->fetch()){$s->close();throw new RuntimeException('Account not found.');}$s->close();
  $naturalBal=max(0,(int)$naturalBal);$paidBal=max(0,(int)$paidBal);if($postNsfw){if($naturalBal<$mills)throw new RuntimeException('Square-funded impressions are SFW-only. This NSFW/mature post needs enough natural ad credits for the full boost.');$paidMills=0;$naturalMills=$mills;}else{if($naturalBal+$paidBal<$mills)throw new RuntimeException('Not enough impressions/credits.');$paidMills=min($paidBal,$mills);$naturalMills=$mills-$paidMills;}
  $s=tt_prepare($db,'UPDATE `accounts` SET `ad_paid_credit_mills`=`ad_paid_credit_mills`-?,`ad_credit_mills`=`ad_credit_mills`-? WHERE `id`=?');$s->bind_param('iii',$paidMills,$naturalMills,$uid);if(!$s->execute())throw new RuntimeException($s->error);$s->close();
  $s=tt_prepare($db,'INSERT INTO `ad_boosts` (`post_id`,`buyer_account_id`,`target_username`,`target_scope`,`target_tags`,`budget_mills`,`spent_mills`,`paid_budget_mills`,`paid_spent_mills`,`status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,0,?,0,\'active\',UTC_TIMESTAMP(),UTC_TIMESTAMP())');$s->bind_param('iisssii',$postId,$uid,$targetUser,$targetScope,$targetTags,$mills,$paidMills);if(!$s->execute())throw new RuntimeException($s->error);$id=(int)$db->insert_id;$s->close();return $id;
 }
 $s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`-? WHERE `id`=? AND `ad_credit_mills`>=?');$s->bind_param('iii',$mills,$uid,$mills);$s->execute();if($s->affected_rows!==1){$s->close();throw new RuntimeException('Not enough ad credits.');}$s->close();
 $s=tt_prepare($db,'INSERT INTO `ad_boosts` (`post_id`,`buyer_account_id`,`target_username`,`target_scope`,`target_tags`,`budget_mills`,`spent_mills`,`status`,`created_at`,`updated_at`) VALUES (?,?,?,?,?,?,0,\'active\',UTC_TIMESTAMP(),UTC_TIMESTAMP())');$s->bind_param('iisssi',$postId,$uid,$targetUser,$targetScope,$targetTags,$mills);if(!$s->execute())throw new RuntimeException($s->error);$id=(int)$db->insert_id;$s->close();return $id;
}
function tt_ad_attachment_html(array $r):string{
 $file=(string)($r['file']??'');if($file==='')return '';$mime=(string)($r['attachment_mime']??'');$pid=(int)($r['id']??0);$u=htmlspecialchars($file,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');
 if(str_starts_with(strtolower($mime),'image/'))return '<a class="post-image-link" href="post.php#post-'.$pid.'"><img class="post-image" src="'.$u.'" alt="Attached image for post '.htmlspecialchars((string)($r['title']??''),ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8').'" loading="lazy"></a>';
 return '<p><a href="'.$u.'" rel="noopener">Attachment</a></p>';
}
?>