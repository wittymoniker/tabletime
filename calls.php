<?php
require_once __DIR__.'/core.php';
tt_require_login();
$db=tt_db_open(true);$me=(string)$_SESSION['name'];$uid=(int)$_SESSION['id'];
$groups=[];$events=[];$forums=[];$tags=[];
$r=$db->query('SELECT `id`,`title`,`members` FROM `groups` ORDER BY `title`');if($r){while($x=$r->fetch_assoc())if(in_array($me,tt_member_list((string)$x['members']),true))$groups[]=$x;$r->free();}
$r=$db->query('SELECT `id`,`title`,`members` FROM `events` ORDER BY `title`');if($r){while($x=$r->fetch_assoc())if(in_array($me,tt_member_list((string)$x['members']),true))$events[]=$x;$r->free();}
$r=$db->query('SELECT `id`,`tag` FROM `forums` ORDER BY `tag` LIMIT 200');if($r){while($x=$r->fetch_assoc())$forums[]=['id'=>(int)$x['id'],'title'=>(string)$x['tag']];$r->free();}
$r=$db->query('SELECT `value` FROM `tags` ORDER BY `value` LIMIT 200');if($r){while($x=$r->fetch_assoc())$tags[]=['id'=>0,'title'=>(string)$x['value']];$r->free();}
$authHash=(string)($_SESSION['tt_auth_hash']??'');if($authHash===''){ $ar=tt_account_restore_query($db,$uid);if($ar){$authHash=(string)$ar['password'];$_SESSION['tt_auth_hash']=$authHash;}}
$callApiToken=tt_call_api_token_build($uid,$authHash,14400);$db->close();
$pref=trim((string)($_GET['scope']??'default'));if(!in_array($pref,['default','private','group','event','forum','tag'],true))$pref='default';
?><!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · Calls</title></head><body><?php tt_nav('calls.php');?><main class="content">
<section class="card" id="call-device-setup">
<h2>Call devices</h2>
<p>Choose and preview your camera and microphone <strong>before</strong> Tabletime creates or joins a call. Speaker/output selection is shown when the browser supports it.</p>
<div class="call-device-grid">
<label>Camera<select id="call-camera-device"><option value="">Default camera</option></select></label>
<label>Microphone<select id="call-mic-device"><option value="">Default microphone</option></select></label>
<label>Speaker / output<select id="call-speaker-device"><option value="">System default</option></select></label>
</div>
<div class="actions"><button id="call-device-preview" type="button">Enable / preview devices</button><button id="call-device-stop" type="button" disabled>Stop preview</button></div>
<p id="call-device-status" class="meta">Device labels may appear after the first camera/microphone permission prompt.</p>
<figure class="participant-tile is-local device-preview"><video id="setup-local-video" autoplay muted playsinline webkit-playsinline></video><figcaption><strong>Your preview</strong><span id="setup-media-state" class="participant-state">Devices not enabled yet</span></figcaption></figure>
</section>
<section class="card"><h2>Calls</h2><p>Start an ad-hoc, private, Group, Event, Forum or Tag call. A blank/default call is valid and starts as your own broadcast room. You can participate in up to three calls at once, with up to 86 participants in each room. Starting the same target reuses the existing active room by default.</p><p class="meta">Direct WebRTC is used first. TURN remains optional fallback. Your device preview is independent of signaling, so your own camera should remain visible even if a remote connection has not started yet.</p>
<form id="call-create"><label>Call type</label><select id="call-scope" name="scope_type"><?php foreach(['default'=>'Ad-hoc / broadcast','private'=>'Private','group'=>'Group','event'=>'Event','forum'=>'Forum','tag'=>'Tag'] as $v=>$l):?><option value="<?=$v?>" <?=$pref===$v?'selected':''?>><?=tt_h($l)?></option><?php endforeach;?></select><label id="private-label">People (semicolon separated usernames)</label><input id="call-members" name="members" type="text" placeholder="friend1;friend2"><label id="scope-label" hidden>Scope</label><select id="call-scope-id" name="scope_id" hidden></select><input id="call-scope-ref" name="scope_ref" type="hidden"><label>Call title</label><input id="call-title" name="title" type="text" maxlength="255" value="Tabletime call"><button id="call-create-submit" type="submit" disabled>Preview devices first</button></form><div id="call-status" class="notice" hidden></div></section>
<section id="my-calls" class="card"><h2>My active / incoming calls</h2><div id="call-dashboard"><p class="meta">Loading calls…</p></div></section>
<section id="call-room" class="card" hidden><h2 id="call-heading">Call</h2><p>Participant cells come from the room roster first. Video fills those cells after each participant joins and media negotiation succeeds.</p><div class="actions"><button id="call-join" type="button" disabled>Preview devices first</button><button id="call-camera" type="button" disabled>Camera on/off</button><button id="call-mic" type="button" disabled>Mic on/off</button><button id="call-share" type="button" disabled>Share screen</button><button id="call-leave" type="button" disabled>Leave</button></div><div class="video-grid" id="participant-grid" aria-live="polite"><figure id="participant-self" class="participant-tile is-local"><video id="local-video" autoplay muted playsinline webkit-playsinline></video><figcaption><strong>You</strong><span id="local-media-state" class="participant-state">Preview devices first</span></figcaption></figure></div></section>
</main>
<?php require_once __DIR__.'/turn_lib.php';$turnCfg=tt_turn_ice_config();?><script>window.TT_CALL_CONTEXT={user:<?=json_encode($me)?>,uid:<?=$uid?>,groups:<?=json_encode($groups)?>,events:<?=json_encode($events)?>,forums:<?=json_encode($forums)?>,tags:<?=json_encode($tags)?>,iceServers:<?=json_encode($turnCfg['servers'])?>,turnConfigured:<?=!empty($turnCfg['turn_configured'])?'true':'false'?>,turnSource:<?=json_encode($turnCfg['source'])?>,turnError:<?=json_encode($turnCfg['error'])?>,apiToken:<?=json_encode($callApiToken)?>};</script><script src="calls.js?v=20260916auth12devices1"></script><script src="nsfw-blur.js?v=20260915c" defer></script></body></html>
