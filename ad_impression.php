<?php
require_once __DIR__.'/core.php';require_once __DIR__.'/ad_lib.php';header('Content-Type: application/json; charset=utf-8');
$boost=max(0,(int)($_POST['boost_id']??0));$pageOwner=max(0,(int)($_POST['page_owner_id']??0));if($boost<=0){http_response_code(400);echo '{"ok":false}';exit;}
$viewer=(int)($_SESSION['id']??0);$bucket=(int)floor(time()/600);$key=hash('sha256',$boost.'|'.$viewer.'|'.session_id().'|'.$pageOwner.'|'.$bucket);$db=tt_db_open(false);tt_ad_require_schema($db);
try{$db->begin_transaction();
 $s=tt_prepare($db,'SELECT `budget_mills`,`spent_mills`,`status`,`buyer_account_id` FROM `ad_boosts` WHERE `id`=? FOR UPDATE');$s->bind_param('i',$boost);$s->execute();$row=$s->get_result()->fetch_assoc();$s->close();$cost=tt_ad_mills_per_credit();
 if(!$row||$row['status']!=='active'||((int)$row['budget_mills']-(int)$row['spent_mills'])<$cost){$db->rollback();$db->close();echo '{"ok":true,"charged":false}';exit;}
 // Legacy monetary columns are retained for database compatibility, but are zeroed in natural-credit mode.
 $zero=0;$s=tt_prepare($db,'INSERT IGNORE INTO `ad_impressions` (`boost_id`,`viewer_account_id`,`page_owner_account_id`,`impression_key`,`cost_mills`,`owner_mills`,`platform_mills`,`viewer_micros`) VALUES (?,?,?,?,?,?,?,?)');$viewerNull=$viewer?:null;$ownerNull=$pageOwner?:null;$s->bind_param('iiisiiii',$boost,$viewerNull,$ownerNull,$key,$cost,$zero,$zero,$zero);$s->execute();$new=$s->affected_rows===1;$s->close();
 if($new){$s=tt_prepare($db,'UPDATE `ad_boosts` SET `spent_mills`=`spent_mills`+?,`status`=IF(`spent_mills`+?>=`budget_mills`,\'spent\',`status`),`updated_at`=UTC_TIMESTAMP() WHERE `id`=?');$s->bind_param('iii',$cost,$cost,$boost);$s->execute();$s->close();
   // Natural exchange: the owner of the page hosting somebody else's impression earns exactly one credit.
   if($pageOwner>0 && $pageOwner!==(int)$row['buyer_account_id']){$s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`+? WHERE `id`=?');$s->bind_param('ii',$cost,$pageOwner);$s->execute();$s->close();}
 }
 $db->commit();$db->close();echo json_encode(['ok'=>true,'charged'=>$new,'credits_spent'=>$new?1:0,'credits_earned'=>($new&&$pageOwner>0&&$pageOwner!==(int)$row['buyer_account_id'])?1:0]);
}catch(Throwable $e){@$db->rollback();$db->close();http_response_code(500);echo '{"ok":false}';}
