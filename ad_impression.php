<?php
require_once __DIR__.'/core.php';require_once __DIR__.'/ad_lib.php';header('Content-Type: application/json; charset=utf-8');
$boost=max(0,(int)($_POST['boost_id']??0));$pageOwner=max(0,(int)($_POST['page_owner_id']??0));if($boost<=0){http_response_code(400);echo '{"ok":false}';exit;}
$viewer=(int)($_SESSION['id']??0);$bucket=(int)floor(time()/600);$key=hash('sha256',$boost.'|'.$viewer.'|'.session_id().'|'.$pageOwner.'|'.$bucket);$db=tt_db_open(false);tt_ad_require_schema($db);
try{$db->begin_transaction();$paidReady=tt_ad_paid_schema_ready($db);
 $sql=$paidReady?'SELECT `budget_mills`,`spent_mills`,`status`,`buyer_account_id`,`paid_budget_mills`,`paid_spent_mills` FROM `ad_boosts` WHERE `id`=? FOR UPDATE':'SELECT `budget_mills`,`spent_mills`,`status`,`buyer_account_id`,0 AS `paid_budget_mills`,0 AS `paid_spent_mills` FROM `ad_boosts` WHERE `id`=? FOR UPDATE';
 $s=tt_prepare($db,$sql);$s->bind_param('i',$boost);$s->execute();$row=$s->get_result()->fetch_assoc();$s->close();$cost=tt_ad_mills_per_credit();
 if(!$row||$row['status']!=='active'||((int)$row['budget_mills']-(int)$row['spent_mills'])<$cost){$db->rollback();$db->close();echo '{"ok":true,"charged":false}';exit;}
 if($viewer>0&&$viewer===(int)$row['buyer_account_id']){$db->rollback();$db->close();echo '{"ok":true,"charged":false,"reason":"own_ad"}';exit;}
 $cashPaid=$paidReady&&((int)$row['paid_spent_mills']+$cost<=(int)$row['paid_budget_mills']);
 if($cashPaid&&($pageOwner<=0||$pageOwner===(int)$row['buyer_account_id'])){$db->rollback();$db->close();echo '{"ok":true,"charged":false,"reason":"paid_requires_display_subject"}';exit;}
 $ownerCashMicros=($cashPaid&&$pageOwner>0&&$pageOwner!==(int)$row['buyer_account_id'])?4000:0; // $0.004 = 4/5 of $5/1000
 $platformCashMicros=$cashPaid?1000:0; // $0.001 = 1/5 of $5/1000
 $zero=0;$s=tt_prepare($db,'INSERT IGNORE INTO `ad_impressions` (`boost_id`,`viewer_account_id`,`page_owner_account_id`,`impression_key`,`cost_mills`,`owner_mills`,`platform_mills`,`viewer_micros`) VALUES (?,?,?,?,?,?,?,?)');$viewerNull=$viewer?:null;$ownerNull=$pageOwner?:null;$s->bind_param('iiisiiii',$boost,$viewerNull,$ownerNull,$key,$cost,$zero,$zero,$zero);$s->execute();$new=$s->affected_rows===1;$impId=(int)$db->insert_id;$s->close();
 if($new){
   if($paidReady&&$cashPaid){$s=tt_prepare($db,'UPDATE `ad_boosts` SET `spent_mills`=`spent_mills`+?,`paid_spent_mills`=`paid_spent_mills`+?,`status`=IF(`spent_mills`+?>=`budget_mills`,\'spent\',`status`),`updated_at`=UTC_TIMESTAMP() WHERE `id`=?');$s->bind_param('iiii',$cost,$cost,$cost,$boost);$s->execute();$s->close();}
   else{$s=tt_prepare($db,'UPDATE `ad_boosts` SET `spent_mills`=`spent_mills`+?,`status`=IF(`spent_mills`+?>=`budget_mills`,\'spent\',`status`),`updated_at`=UTC_TIMESTAMP() WHERE `id`=?');$s->bind_param('iii',$cost,$cost,$boost);$s->execute();$s->close();}
   // Natural exchange remains: display subject earns one natural ad credit for hosting another user's ad.
   if($pageOwner>0 && $pageOwner!==(int)$row['buyer_account_id']){$s=tt_prepare($db,'UPDATE `accounts` SET `ad_credit_mills`=`ad_credit_mills`+? WHERE `id`=?');$s->bind_param('ii',$cost,$pageOwner);$s->execute();$s->close();}
   // Square-funded impressions: 80% accrues to the display subject, 20% to Tabletime.
   if($paidReady&&$cashPaid){
     if($ownerCashMicros>0){$kind='display_owner';$s=tt_prepare($db,'INSERT IGNORE INTO `ad_money_ledger` (`beneficiary_account_id`,`boost_id`,`impression_id`,`kind`,`amount_micros`,`created_at`) VALUES (?,?,?,?,?,UTC_TIMESTAMP())');$s->bind_param('iiisi',$pageOwner,$boost,$impId,$kind,$ownerCashMicros);$s->execute();$s->close();}
     $kind='platform';$s=tt_prepare($db,'INSERT IGNORE INTO `ad_money_ledger` (`beneficiary_account_id`,`boost_id`,`impression_id`,`kind`,`amount_micros`,`created_at`) VALUES (NULL,?,?,?, ?,UTC_TIMESTAMP())');$s->bind_param('iisi',$boost,$impId,$kind,$platformCashMicros);$s->execute();$s->close();
   }
 }
 $db->commit();$db->close();echo json_encode(['ok'=>true,'charged'=>$new,'credits_spent'=>$new?1:0,'credits_earned'=>($new&&$pageOwner>0&&$pageOwner!==(int)$row['buyer_account_id'])?1:0,'cash_earning_micros'=>$new?$ownerCashMicros:0]);
}catch(Throwable $e){@$db->rollback();$db->close();http_response_code(500);echo '{"ok":false}';}
