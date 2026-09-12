<?php
/**
 * Tabletime social controls restored from the original README + 2025 UI.
 *
 * Historical controls:
 *   - scope slider: -256..256; v/256 <= -.5 private, >= .5 global, middle public
 *   - Karma/Moksha slider: -256..256 continuous bad/good perspective
 *
 * Documented base delays:
 *   message 1m, group chat/video call 3m, registration 30m,
 *   post 5m, login attempt 10m.
 * Failed attempts double the active delay. The documented vote delay is:
 *   delay = action_delay * number_of_votes / voteban_score
 *
 * To keep neutral ratings at the documented base delay while preserving that
 * exact equation, voteban_score is the effective vote weight multiplied by
 * (1 + mean_perspective). Thus neutral => votes/score=1, negative ratings
 * increase delay, and a full -256 consensus makes score zero => infinite lock.
 * The creator's own rating receives one-to-one weight against all external
 * ratings on that post, matching the README rule.
 */

function tt_scope_from_slider($raw): string {
    $v=max(-256,min(256,(int)$raw));
    $scaled=$v/256.0;
    if($scaled<=-0.5)return 'private';
    if($scaled>=0.5)return 'global';
    return 'public';
}
function tt_scope_slider_value(string $scope): int {
    $scope=trim(strtolower($scope));
    return $scope==='private'?-256:($scope==='global'?256:0);
}
function tt_perspective_value($raw): int { return max(-256,min(256,(int)$raw)); }
function tt_perspective_normalized($raw): float { return tt_perspective_value($raw)/256.0; }
function tt_action_base_delay_seconds(string $action): int {
    return match(strtolower($action)){
        'message','dm'=>60,
        'group_chat','call','video_call'=>180,
        'register','registration'=>1800,
        'login'=>600,
        default=>300,
    };
}
function tt_action_for_post_type(string $type): string {
    return strtolower($type)==='message'?'message':'post';
}
function tt_delay_identity(): string {
    $ip=(string)($_SERVER['REMOTE_ADDR']??'unknown');
    return hash('sha256','tabletime-delay|'.$ip);
}
function tt_delay_human(?int $seconds): string {
    if($seconds===null)return '∞';
    $seconds=max(0,$seconds);
    if($seconds<60)return $seconds.'s';
    $m=(int)ceil($seconds/60);
    if($m<60)return $m.'m';
    $h=(int)floor($m/60);$rm=$m%60;
    return $h.'h'.($rm?' '.$rm.'m':'');
}

/** Return effective Karma/Moksha reputation for an account. */
function tt_account_reputation(mysqli $db,?int $accountId): array {
    if(!$accountId || $accountId<=0)return ['number_of_votes'=>1.0,'mean_perspective'=>0.0,'voteban_score'=>1.0,'slider'=>0,'ratings'=>0];
    $q=$db->prepare('SELECT p.`id` AS post_id,p.`name`,pr.`voter_account_id`,pr.`perspective` FROM `posts` p JOIN `accounts` a ON a.`username`=p.`name` LEFT JOIN `post_ratings` pr ON pr.`post_id`=p.`id` WHERE a.`id`=? AND pr.`post_id` IS NOT NULL');
    if(!$q)return ['number_of_votes'=>1.0,'mean_perspective'=>0.0,'voteban_score'=>1.0,'slider'=>0,'ratings'=>0];
    $q->bind_param('i',$accountId);$q->execute();$r=$q->get_result();$byPost=[];$ratings=0;
    while($r&&($row=$r->fetch_assoc())){
        $pid=(int)$row['post_id'];if(!isset($byPost[$pid]))$byPost[$pid]=['ext_n'=>0,'ext_sum'=>0.0,'owner'=>null];
        $n=tt_perspective_normalized((int)$row['perspective']);$ratings++;
        if((int)$row['voter_account_id']===$accountId)$byPost[$pid]['owner']=$n;
        else{$byPost[$pid]['ext_n']++;$byPost[$pid]['ext_sum']+=$n;}
    }
    if($r)$r->free();$q->close();
    $weight=0.0;$sum=0.0;
    foreach($byPost as $p){
        $n=(int)$p['ext_n'];$weight+=$n;$sum+=(float)$p['ext_sum'];
        if($p['owner']!==null){$ow=max(1,$n);$weight+=$ow;$sum+=(float)$p['owner']*$ow;}
    }
    if($weight<=0.0)return ['number_of_votes'=>1.0,'mean_perspective'=>0.0,'voteban_score'=>1.0,'slider'=>0,'ratings'=>0];
    $mean=max(-1.0,min(1.0,$sum/$weight));
    $votes=max(1.0,$weight);
    $score=$votes*(1.0+$mean);
    return ['number_of_votes'=>$votes,'mean_perspective'=>$mean,'voteban_score'=>$score,'slider'=>(int)round($mean*256),'ratings'=>$ratings];
}
function tt_post_rating_summary(mysqli $db,int $postId,int $ownerAccountId=0): array {
    $q=$db->prepare('SELECT `voter_account_id`,`perspective` FROM `post_ratings` WHERE `post_id`=?');if(!$q)return ['value'=>0,'count'=>0];
    $q->bind_param('i',$postId);$q->execute();$r=$q->get_result();$extN=0;$extSum=0.0;$owner=null;$count=0;
    while($r&&($row=$r->fetch_assoc())){$count++;$v=tt_perspective_normalized((int)$row['perspective']);if($ownerAccountId>0&&(int)$row['voter_account_id']===$ownerAccountId)$owner=$v;else{$extN++;$extSum+=$v;}}
    if($r)$r->free();$q->close();$weight=$extN;$sum=$extSum;if($owner!==null){$ow=max(1,$extN);$weight+=$ow;$sum+=$owner*$ow;}
    $mean=$weight>0?max(-1.0,min(1.0,$sum/$weight)):0.0;return ['value'=>(int)round($mean*256),'count'=>$count];
}
function tt_user_rating_for_post(mysqli $db,int $postId,int $voterId): int {
    if($voterId<=0)return 0;$q=$db->prepare('SELECT `perspective` FROM `post_ratings` WHERE `post_id`=? AND `voter_account_id`=? LIMIT 1');if(!$q)return 0;$q->bind_param('ii',$postId,$voterId);$q->execute();$q->bind_result($v);$ok=$q->fetch();$q->close();return $ok?(int)$v:0;
}

/** Apply the documented formula; null means infinite. */
function tt_formula_delay_seconds(string $action,array $rep,int $failures=0): ?int {
    $base=tt_action_base_delay_seconds($action);$votes=max(1.0,(float)($rep['number_of_votes']??1.0));$score=(float)($rep['voteban_score']??$votes);
    if($score<=0.000000001)return null;
    $seconds=$base*$votes/$score;
    $seconds*=pow(2,min(20,max(0,$failures)));
    if(!is_finite($seconds)||$seconds>3153600000)return null;
    return max($base,(int)ceil($seconds));
}
function tt_delay_row(mysqli $db,string $action): array {
    $identity=tt_delay_identity();$q=$db->prepare('SELECT `failures`,`last_attempt_at`,`last_success_at`,`account_id` FROM `action_locks` WHERE `identity_hash`=? AND `action`=? LIMIT 1');
    if(!$q)return ['identity'=>$identity,'failures'=>0,'last_attempt_at'=>null,'last_success_at'=>null,'account_id'=>null];
    $q->bind_param('ss',$identity,$action);$q->execute();$r=$q->get_result()?->fetch_assoc();$q->close();return array_merge(['identity'=>$identity,'failures'=>0,'last_attempt_at'=>null,'last_success_at'=>null,'account_id'=>null],$r?:[]);
}
function tt_delay_status(mysqli $db,string $action,?int $accountId=null): array {
    $action=strtolower($action);$row=tt_delay_row($db,$action);$failures=(int)$row['failures'];$rep=tt_account_reputation($db,$accountId);$duration=tt_formula_delay_seconds($action,$rep,$failures);
    if($duration===null)return ['allowed'=>false,'infinite'=>true,'remaining'=>null,'duration'=>null,'failures'=>$failures,'base'=>tt_action_base_delay_seconds($action),'reputation'=>$rep];
    $anchor=$failures>0?($row['last_attempt_at']??$row['last_success_at']):($row['last_success_at']??$row['last_attempt_at']);
    if(!$anchor)return ['allowed'=>true,'infinite'=>false,'remaining'=>0,'duration'=>$duration,'failures'=>$failures,'base'=>tt_action_base_delay_seconds($action),'reputation'=>$rep];
    $until=strtotime($anchor.' UTC')+$duration;$remaining=max(0,$until-time());
    return ['allowed'=>$remaining<=0,'infinite'=>false,'remaining'=>$remaining,'duration'=>$duration,'failures'=>$failures,'base'=>tt_action_base_delay_seconds($action),'reputation'=>$rep,'until'=>$until];
}
function tt_delay_mark_failure(mysqli $db,string $action,?int $accountId=null): void {
    $identity=tt_delay_identity();$aid=$accountId&&$accountId>0?$accountId:null;
    $q=$db->prepare('INSERT INTO `action_locks` (`identity_hash`,`action`,`account_id`,`failures`,`last_attempt_at`,`updated_at`) VALUES (?,?,?,1,UTC_TIMESTAMP(),UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE `account_id`=COALESCE(VALUES(`account_id`),`account_id`),`failures`=LEAST(`failures`+1,20),`last_attempt_at`=UTC_TIMESTAMP(),`updated_at`=UTC_TIMESTAMP()');if(!$q)return;$q->bind_param('ssi',$identity,$action,$aid);@$q->execute();$q->close();
}
function tt_delay_mark_success(mysqli $db,string $action,?int $accountId=null): void {
    $identity=tt_delay_identity();$aid=$accountId&&$accountId>0?$accountId:null;
    $q=$db->prepare('INSERT INTO `action_locks` (`identity_hash`,`action`,`account_id`,`failures`,`last_attempt_at`,`last_success_at`,`updated_at`) VALUES (?,?,?,0,UTC_TIMESTAMP(),UTC_TIMESTAMP(),UTC_TIMESTAMP()) ON DUPLICATE KEY UPDATE `account_id`=COALESCE(VALUES(`account_id`),`account_id`),`failures`=0,`last_attempt_at`=UTC_TIMESTAMP(),`last_success_at`=UTC_TIMESTAMP(),`updated_at`=UTC_TIMESTAMP()');if(!$q)return;$q->bind_param('ssi',$identity,$action,$aid);@$q->execute();$q->close();
}
function tt_delay_assert(mysqli $db,string $action,?int $accountId=null): array {
    $s=tt_delay_status($db,$action,$accountId);if($s['allowed'])return $s;
    $why=$s['infinite']?'∞ (full negative Karma/Moksha / voteban score)':'another '.tt_delay_human((int)$s['remaining']);
    throw new RuntimeException('Tabletime delay lock: '.$why.' before this '.$action.' action. Base '.tt_delay_human($s['base']).'; failed attempts double it, and Karma/Moksha applies the documented vote/voteban formula.');
}
?>
