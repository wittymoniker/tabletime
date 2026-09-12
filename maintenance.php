<?php
require_once __DIR__ . '/db.php';

// Wasmer Hobby managed-DB policy: use almost all of the 100 MB allowance,
// but never intentionally write across it.  The small margin is for InnoDB
// page/index/transaction overhead, not a 25 MB reserve.
function tt_db_limit_bytes(): int { $v=(int)(getenv('TT_DB_LIMIT_BYTES') ?: 100000000); return max(20000000,$v); }
function tt_db_soft_bytes(): int { return (int)(getenv('TT_DB_SOFT_BYTES') ?: min(tt_db_limit_bytes()-4000000,96000000)); }
function tt_db_target_bytes(): int { return (int)(getenv('TT_DB_TARGET_BYTES') ?: min(tt_db_limit_bytes()-8000000,92000000)); }
function tt_db_hard_bytes(): int { return (int)(getenv('TT_DB_HARD_BYTES') ?: min(tt_db_limit_bytes()-1000000,99000000)); }
function tt_upload_limit_bytes(): int { return 250*1024; }
function tt_db_size_bytes(mysqli $db): int {
    $r=$db->query("SELECT COALESCE(SUM(data_length+index_length),0) AS n FROM information_schema.tables WHERE table_schema=DATABASE()");
    if(!$r)return 0;$row=$r->fetch_assoc();$r->free();return (int)($row['n']??0);
}
function tt_setting_int(mysqli $db,string $key,int $default=0): int {
    $s=$db->prepare('SELECT setting_value FROM node_settings WHERE setting_key=?');if(!$s)return $default;$s->bind_param('s',$key);$s->execute();$s->bind_result($v);$ok=$s->fetch();$s->close();return $ok?(int)$v:$default;
}
function tt_set_setting_int(mysqli $db,string $key,int $value): void {
    $v=(string)max(0,$value);$s=$db->prepare('INSERT INTO node_settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');if(!$s)return;$s->bind_param('ss',$key,$v);@$s->execute();$s->close();
}
/**
 * InnoDB often reuses pages freed by DELETE without immediately reducing
 * information_schema.data_length.  Track a deliberately conservative recycle
 * credit so Tabletime doesn't repeatedly delete content merely because the
 * physical allocation hasn't shrunk yet.
 */
function tt_capacity_state(mysqli $db): array {
    $physical=tt_db_size_bytes($db);$last=tt_setting_int($db,'capacity_last_physical',$physical);$credit=tt_setting_int($db,'capacity_recycle_credit',0);
    $delta=$physical-$last;
    if($delta!==0){$credit=max(0,$credit-abs($delta));}
    $credit=min($credit,max(0,$physical));
    tt_set_setting_int($db,'capacity_last_physical',$physical);tt_set_setting_int($db,'capacity_recycle_credit',$credit);
    return ['physical'=>$physical,'credit'=>$credit,'effective'=>max(0,$physical-$credit)];
}
function tt_add_recycle_credit(mysqli $db,int $logicalBytes): void {
    if($logicalBytes<=0)return;$state=tt_capacity_state($db);$credit=$state['credit']+(int)floor($logicalBytes*0.60); // conservative reuse assumption
    tt_set_setting_int($db,'capacity_recycle_credit',min($credit,$state['physical']));
}
function tt_consume_recycle_credit(mysqli $db,int $estimatedWrite): void {
    $state=tt_capacity_state($db);if($state['credit']<=0)return;tt_set_setting_int($db,'capacity_recycle_credit',max(0,$state['credit']-max(0,$estimatedWrite)));
}
function tt_remove_attachment_path(string $path): void {
    if($path==='' || preg_match('~^https?://~i',$path))return;
    $base=realpath(__DIR__.'/files');if(!$base)return;
    $candidate=realpath(__DIR__.'/'.$path);if(!$candidate || !str_starts_with($candidate,$base.DIRECTORY_SEPARATOR))return;
    if(is_file($candidate))@unlink($candidate);$dir=dirname($candidate);if($dir!==$base && is_dir($dir))@rmdir($dir);
}
function tt_delete_post(mysqli $db,int $id,bool $tombstone=true): array {
    $q=$db->prepare("SELECT p.file,p.origin_host,p.origin_post_id,(COALESCE(OCTET_LENGTH(p.title),0)+COALESCE(OCTET_LENGTH(p.content),0)+COALESCE(OCTET_LENGTH(p.tags),0)+COALESCE(OCTET_LENGTH(p.comments),0)+COALESCE(OCTET_LENGTH(p.recipients),0)+COALESCE(OCTET_LENGTH(p.votes),0)+COALESCE((SELECT SUM(a.size_bytes) FROM post_attachments a WHERE a.post_id=p.id),0)+8192) AS logical_bytes FROM posts p WHERE p.id=?");$q->bind_param('i',$id);$q->execute();$r=$q->get_result();$row=$r->fetch_assoc();$q->close();if(!$row)return ['deleted'=>false,'bytes'=>0];
    tt_remove_attachment_path((string)$row['file']);
    $d=$db->prepare('DELETE FROM post_attachments WHERE post_id=?');if($d){$d->bind_param('i',$id);$d->execute();$d->close();}
    $d=$db->prepare('DELETE FROM post_tags WHERE post_id=?');$d->bind_param('i',$id);$d->execute();$d->close();
    $d=$db->prepare('DELETE FROM tag_index WHERE post_id=?');$d->bind_param('i',$id);$d->execute();$d->close();
    if($tombstone && empty($row['origin_host'])){$origin=(string)$id;$now=gmdate('Y-m-d H:i:s');$ins=$db->prepare('INSERT INTO federation_tombstones(origin_host,origin_post_id,deleted_at) VALUES(NULL,?,?)');if($ins){$ins->bind_param('ss',$origin,$now);@$ins->execute();$ins->close();}}
    $d=$db->prepare('DELETE FROM posts WHERE id=?');$d->bind_param('i',$id);$ok=$d->execute();$d->close();if($ok){$sid=(string)$id;$u=$db->prepare("UPDATE `accounts` SET `posts`=TRIM(BOTH ';' FROM REPLACE(CONCAT(';',`posts`,';'),CONCAT(';',?,';'),';')) WHERE FIND_IN_SET(?,REPLACE(`posts`,';',','))>0");if($u){$u->bind_param('ss',$sid,$sid);@$u->execute();$u->close();}}return ['deleted'=>$ok,'bytes'=>$ok?(int)$row['logical_bytes']:0];
}
function tt_cleanup_orphans(mysqli $db): int {
    $n=0;$db->query('DELETE pt FROM post_tags pt LEFT JOIN posts p ON p.id=pt.post_id WHERE p.id IS NULL');$n+=$db->affected_rows;$db->query('DELETE ti FROM tag_index ti LEFT JOIN posts p ON p.id=ti.post_id WHERE p.id IS NULL');$n+=$db->affected_rows;$db->query("DELETE FROM federation_tombstones WHERE deleted_at < (UTC_TIMESTAMP() - INTERVAL 180 DAY)");$n+=$db->affected_rows;return $n;
}
function tt_delete_candidates(mysqli $db,string $where,int $limit=25): array {
    $ids=[];$sql="SELECT p.id FROM posts p LEFT JOIN accounts a ON a.username=p.name WHERE p.pinned=0 AND ($where) ORDER BY p.dt ASC,p.id ASC LIMIT ".max(1,min(100,$limit));$r=$db->query($sql);if(!$r)return ['count'=>0,'bytes'=>0];while($x=$r->fetch_assoc())$ids[]=(int)$x['id'];$r->free();$n=0;$bytes=0;foreach($ids as $id){$x=tt_delete_post($db,$id,true);if($x['deleted']){$n++;$bytes+=(int)$x['bytes'];}}return ['count'=>$n,'bytes'=>$bytes];
}
function tt_prune_inactive_accounts(mysqli $db,int $limit=20): array {
    $days=max(30,(int)(getenv('TT_ACCOUNT_RETENTION_DAYS') ?: 365));
    $sel="SELECT id,(COALESCE(OCTET_LENGTH(username),0)+COALESCE(OCTET_LENGTH(password),0)+COALESCE(OCTET_LENGTH(email),0)+COALESCE(OCTET_LENGTH(friends),0)+COALESCE(OCTET_LENGTH(posts),0)+COALESCE(OCTET_LENGTH(`groups`),0)+COALESCE(OCTET_LENGTH(`events`),0)+COALESCE(OCTET_LENGTH(`forums`),0)+COALESCE(OCTET_LENGTH(tags),0)+COALESCE(OCTET_LENGTH(`messages`),0)+COALESCE(OCTET_LENGTH(`files`),0)+4096) logical_bytes FROM `accounts` WHERE `hostmode`=0 AND `protected_account`=0 AND COALESCE(last_login_at,created_at) < (UTC_TIMESTAMP() - INTERVAL {$days} DAY) ORDER BY COALESCE(last_login_at,created_at) ASC LIMIT ".max(1,min(100,$limit));
    $r=$db->query($sel);if(!$r)return ['count'=>0,'bytes'=>0];$ids=[];$bytes=0;while($x=$r->fetch_assoc()){$ids[]=(int)$x['id'];$bytes+=(int)$x['logical_bytes'];}$r->free();if(!$ids)return ['count'=>0,'bytes'=>0];$list=implode(',',array_map('intval',$ids));$db->query("DELETE FROM `accounts` WHERE `id` IN ($list)");return ['count'=>$db->affected_rows,'bytes'=>$bytes];
}
function tt_prune_database(mysqli $db,int $targetBytes=0): array {
    $targetBytes=$targetBytes>0?$targetBytes:tt_db_target_bytes();$initial=tt_capacity_state($db);$removed=['orphans'=>0,'private'=>0,'federated'=>0,'accounts'=>0,'posts'=>0];$freed=0;$removed['orphans']+=tt_cleanup_orphans($db);$loops=0;
    while($loops++<80){$state=tt_capacity_state($db);$effective=max(0,$state['effective']-(int)floor($freed*0.60));if($effective<=$targetBytes)break;$x=['count'=>0,'bytes'=>0];
        $x=tt_delete_candidates($db,"(p.scope='private' OR p.type='message') AND p.dt < (UTC_TIMESTAMP() - INTERVAL 180 DAY)",25);$removed['private']+=$x['count'];
        if($x['count']===0){$x=tt_delete_candidates($db,"p.federated=1 AND p.dt < (UTC_TIMESTAMP() - INTERVAL 120 DAY)",25);$removed['federated']+=$x['count'];}
        if($x['count']===0){$x=tt_prune_inactive_accounts($db,25);$removed['accounts']+=$x['count'];}
        if($x['count']===0){$x=tt_delete_candidates($db,"TRIM(p.scope) IN ('public','global') AND (a.id IS NULL OR COALESCE(a.last_login_at,a.created_at) < (UTC_TIMESTAMP() - INTERVAL 180 DAY))",25);$removed['posts']+=$x['count'];}
        if($x['count']===0){$x=tt_delete_candidates($db,"TRIM(p.scope) IN ('public','global')",10);$removed['posts']+=$x['count'];}
        if($x['count']===0)break;$freed+=(int)$x['bytes'];
    }
    tt_add_recycle_credit($db,$freed);$after=tt_capacity_state($db);
    return ['before'=>$initial,'after'=>$after,'estimated_logical_bytes_removed'=>$freed,'removed'=>$removed,'limit'=>tt_db_limit_bytes(),'soft'=>tt_db_soft_bytes(),'hard'=>tt_db_hard_bytes(),'target'=>$targetBytes];
}
function tt_preflight_write(mysqli $db,int $estimatedBytes=8192,bool $throw=true): bool {
    $estimate=max(4096,$estimatedBytes);$state=tt_capacity_state($db);
    if($state['effective']+$estimate >= tt_db_soft_bytes()){$r=tt_prune_database($db,tt_db_target_bytes());$state=$r['after'];}
    // Always respect the real physical host edge too. If physical allocation is
    // already above the guard, allow only when conservative recycle credit is ample.
    $safeByEffective=$state['effective']+$estimate < tt_db_hard_bytes();
    $safeByPhysical=$state['physical']+$estimate < tt_db_limit_bytes() || $state['credit'] >= ($estimate*4);
    if(!$safeByEffective || !$safeByPhysical){if($throw)throw new RuntimeException('Tabletime is at its database capacity guard. Old content could not be recycled safely, so this write was refused before the 100 MB host limit was exceeded.');return false;}
    tt_consume_recycle_credit($db,$estimate);return true;
}
function tt_opportunistic_maintenance(mysqli $db): void {$state=tt_capacity_state($db);if($state['effective']>=tt_db_soft_bytes())tt_prune_database($db,tt_db_target_bytes());}
?>
