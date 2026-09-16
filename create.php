<?php
require_once __DIR__.'/core.php';tt_require_login();
$username=(string)$_SESSION['name'];$num1=random_int(1,500);$num2=random_int(1,500);
$allowedModes=['post','ad','message','comment','event','group','forum','profile'];
$mode=strtolower(trim((string)($_GET['mode']??'post')));if($mode==='media')$mode='post';if(!in_array($mode,$allowedModes,true))$mode='post';
$intent=strtolower(trim((string)($_GET['intent']??'')));if($intent!=='comment')$intent='';
$targetPost=max(0,(int)($_GET['target_post']??0));
$targetUser=trim(preg_replace('/[\r\n;]+/',' ',(string)($_GET['target_user']??''))??'');$targetUser=substr($targetUser,0,191);
$targetType=tt_reply_mode_for_post_type((string)($_GET['target_type']??'post'));
$targetTitle=trim(preg_replace('/[\r\n]+/',' ',(string)($_GET['target_title']??''))??'');$targetTitle=substr($targetTitle,0,220);
$targetScope=strtolower(trim((string)($_GET['target_scope']??'public')));if(!in_array($targetScope,['private','public','global'],true))$targetScope='public';
if($targetPost>0 && $intent==='comment')$mode='comment';
$prefillRecipients=$mode==='comment'&&$targetPost>0?(string)$targetPost:$targetUser;
$baseTitle=preg_replace('/^(?:re|comment)\s*:\s*/i','',$targetTitle)??$targetTitle;
$prefillTitle=$targetTitle!==''?('Comment: '.$baseTitle):'';
$tagParts=[];if($targetPost>0&&$intent==='comment'){$tagParts[]='comment';if($targetUser!=='')$tagParts[]='target-user:'.$targetUser;$tagParts[]='target-type:'.$targetType;}
$prefillTags=implode('; ',$tagParts);
$initialScope=$mode==='message'?'private':$targetScope;$initialScopeSlider=tt_scope_slider_value($initialScope);
$selectModes=$allowedModes;
$db=tt_db_open(true);$uid=(int)$_SESSION['id'];$postDelay=tt_delay_status($db,'post',$uid);$messageDelay=tt_delay_status($db,'message',$uid);$db->close();
?>
<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · Create</title></head><body>
<?php tt_nav('create.php'); ?>
<main class="content"><h2>Create as <?=tt_h($username)?></h2>
<?php if($targetPost>0):?><section class="card compose-context"><h3>Commenting on post #<?=$targetPost?></h3><p class="meta">Target <?= $targetUser!==''?'@'.tt_h($targetUser).' · ':'' ?>source mode <?=tt_h($targetType)?> · composer mode <strong><?=tt_h($mode)?></strong></p><p>The target is carried automatically. Comments attach directly to the selected post; Tabletime no longer creates a separate reply-as-message/reply-as-post item.</p><p><a href="post.php#post-<?=$targetPost?>">Back to target post</a></p></section><?php endif;?>
<section class="card"><h3>Tabletime timing</h3><p class="meta">Posts start at 5 minutes; messages start at 1 minute. Failed attempts double the current delay. Profile Moksha uses the documented <code>action delay × number of votes ÷ voteban score</code> rule and can move the lock toward ∞.</p><p id="delay-readout"></p></section>
<form method="post" action="createnew.php" enctype="multipart/form-data" id="create-form">
<input type="hidden" name="reply_to_post" value="<?=$targetPost?>"><input type="hidden" name="reply_to_user" value="<?=tt_h($targetUser)?>"><input type="hidden" name="reply_to_type" value="<?=tt_h($targetType)?>"><input type="hidden" name="reply_intent" value="<?=tt_h($intent)?>">
<label>Type</label><select name="type" id="post-type"><?php foreach($selectModes as $t):?><option value="<?=tt_h($t)?>" <?=$mode===$t?'selected':''?>><?=$t==='profile'?'update profile / picture':tt_h($t)?></option><?php endforeach;?></select>
<label for="scope-view">Post scope: <output id="scope-label"><?=tt_h($initialScope)?></output></label>
<input class="scope-slider" type="range" name="view" id="scope-view" min="-256" max="256" step="1" value="<?=$initialScopeSlider?>" aria-describedby="scope-help">
<input type="hidden" name="scope" id="scope-value" value="<?=tt_h($initialScope)?>">
<div class="slider-labels" id="scope-help"><span>−256 private</span><span>0 public</span><span>+256 global</span></div>
<p class="meta">Original Tabletime mapping: slider ÷ 256; ≤ −0.5 private, ≥ +0.5 global, middle public.</p>
<label>File (optional, maximum 1 MB; images, audio, video, and other attachments are part of a normal Post; an image on a Profile update becomes your profile picture)</label><input type="hidden" name="MAX_FILE_SIZE" value="1048576"><input type="file" name="file">
<label>Title</label><input type="text" name="title" maxlength="255" value="<?=tt_h($prefillTitle)?>" required>
<label>Content</label><textarea name="content" maxlength="32768" required></textarea>
<label>Tags</label><input type="text" name="tags" maxlength="4096" value="<?=tt_h($prefillTags)?>" placeholder="food=0.5; table; fork<=7500; late=-15000-7500; -angry"><label class="inline-check"><input type="checkbox" name="nsfw" value="1"> Mark this post NSFW / mature (18+ and click-to-reveal blur)</label><p class="meta">Tabletime accepts ordinary topic tags and numeric tags. Comments attach directly to the selected post. Message remains a normal post type. Comments are the reply mechanism for existing posts; reply-as-message is not used.</p>
<label>Recipients / comment target post id</label><input type="text" name="recipients" maxlength="4096" value="<?=tt_h($prefillRecipients)?>" placeholder="user1;user2 or post id">
<fieldset class="ad-credit-box"><legend>Apply Ad Credits</legend><label>Boost amount (ad credits / impressions)</label><input type="number" name="boost_credits" min="0" step="1" value="0"><label>Target user/page (optional)</label><input type="text" name="ad_target_user" maxlength="191" autocomplete="off" placeholder="optional existing username, @username, or profile link"><label>Targeting tags (especially useful for Ad subtype)</label><input type="text" name="ad_target_tags" maxlength="1024" placeholder="music; math; local; crystals"><p class="meta">Every post type can be boosted. 1 natural ad credit funds exactly 1 counted impression. Square-funded impressions are purchased separately; Square-funded advertising must be SFW. Target user/page is optional; an unrecognized target safely falls back to untargeted delivery instead of blocking the spend. The Ad subtype is the targeted advertising post type; its image attachment is framed and links to the post.</p><p><a href="adcredits.php">View natural ad-credit balance</a></p></fieldset>
<label><?= $num1 ?> + <?= $num2 ?></label><input type="hidden" name="no1" value="<?= $num1 ?>"><input type="hidden" name="no2" value="<?= $num2 ?>"><input type="text" name="test" inputmode="numeric" required>
<input type="submit" name="enter" id="publish-button" value="Publish">
</form></main>
<script>
const scope=document.getElementById('scope-view'),scopeValue=document.getElementById('scope-value'),scopeLabel=document.getElementById('scope-label'),typeSelect=document.getElementById('post-type');
function scopeName(v){v=Number(v)/256;return v<=-.5?'private':(v>=.5?'global':'public')}
function syncScope(){const s=scopeName(scope.value);scopeValue.value=s;scopeLabel.value=s;scopeLabel.textContent=s;}
scope.addEventListener('input',syncScope);syncScope();
const delayState={post:<?=json_encode($postDelay,JSON_UNESCAPED_SLASHES)?>,message:<?=json_encode($messageDelay,JSON_UNESCAPED_SLASHES)?>};
function delayForType(){return typeSelect.value==='message'?delayState.message:delayState.post}
function fmt(n){if(n===null)return '∞';if(n<60)return n+'s';let m=Math.ceil(n/60);if(m<60)return m+'m';return Math.floor(m/60)+'h '+(m%60)+'m'}
function paintDelay(){const s=delayForType(),b=document.getElementById('publish-button'),r=document.getElementById('delay-readout');if(s.infinite){b.disabled=true;r.textContent='Locked: ∞ until the full-negative/voteban balance is relieved (for example by deleting the rated content).';return}const elapsed=Math.floor((Date.now()/1000)-(window.__ttLoaded||Date.now()/1000)),left=Math.max(0,(s.remaining||0)-elapsed);b.disabled=left>0;r.textContent=left>0?'Locked for another '+fmt(left)+'. Current delay '+fmt(s.duration)+'.':'Ready. Current action delay '+fmt(s.duration)+'.';}
window.__ttLoaded=Date.now()/1000;typeSelect.addEventListener('change',()=>{if(typeSelect.value==='message'){scope.value=-256;syncScope()}paintDelay()});paintDelay();setInterval(paintDelay,1000);
</script>
<!-- TABLETIME_NSFW_BLUR_20260915 -->
<script src="nsfw-blur.js?v=20260915c" defer></script>
</body></html>
