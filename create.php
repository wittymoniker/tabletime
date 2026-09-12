<?php
require_once __DIR__.'/core.php';tt_require_login();
$username=(string)$_SESSION['name'];$num1=random_int(1,500);$num2=random_int(1,500);
$db=tt_db_open(true);$uid=(int)$_SESSION['id'];$postDelay=tt_delay_status($db,'post',$uid);$messageDelay=tt_delay_status($db,'message',$uid);$db->close();
?>
<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · Create</title></head><body>
<?php tt_nav('create.php'); ?>
<main class="content"><h2>Create as <?=tt_h($username)?></h2>
<section class="card"><h3>Tabletime timing</h3><p class="meta">Posts start at 5 minutes; messages start at 1 minute. Failed attempts double the current delay. Karma/Moksha uses the documented <code>action delay × number of votes ÷ voteban score</code> rule and can move the lock toward ∞.</p><p id="delay-readout"></p></section>
<form method="post" action="createnew.php" enctype="multipart/form-data" id="create-form">
<label>Type</label><select name="type" id="post-type"><option value="post">post</option><option value="media">media</option><option value="message">message</option><option value="comment">comment</option><option value="event">event</option><option value="group">group</option><option value="forum">forum</option><option value="profile">update your description</option></select>
<label for="scope-view">Post scope: <output id="scope-label">public</output></label>
<input class="scope-slider" type="range" name="view" id="scope-view" min="-256" max="256" step="1" value="0" aria-describedby="scope-help">
<input type="hidden" name="scope" id="scope-value" value="public">
<div class="slider-labels" id="scope-help"><span>−256 private</span><span>0 public</span><span>+256 global</span></div>
<p class="meta">Original Tabletime mapping: slider ÷ 256; ≤ −0.5 private, ≥ +0.5 global, middle public.</p>
<label>File (optional, maximum 250 KB)</label><input type="hidden" name="MAX_FILE_SIZE" value="256000"><input type="file" name="file">
<label>Title</label><input type="text" name="title" maxlength="255" required>
<label>Content</label><textarea name="content" maxlength="32768" required></textarea>
<label>Tags</label><input type="text" name="tags" maxlength="4096" placeholder="food=0.5; table; fork<=7500; late=-15000-7500; -angry"><p class="meta">Tabletime accepts ordinary topic tags and numeric tags. Numeric arithmetic (+ − × ÷ parentheses), comparison tags (&lt; &lt;= &gt; &gt;=), and +/- polarity are normalized into the searchable tag index.</p>
<label>Recipients / comment target post id</label><input type="text" name="recipients" maxlength="4096" placeholder="user1;user2 or post id">
<label><?= $num1 ?> + <?= $num2 ?></label><input type="hidden" name="no1" value="<?= $num1 ?>"><input type="hidden" name="no2" value="<?= $num2 ?>"><input type="text" name="test" inputmode="numeric" required>
<input type="submit" name="enter" id="publish-button" value="Publish">
</form></main>
<script>
const scope=document.getElementById('scope-view'),scopeValue=document.getElementById('scope-value'),scopeLabel=document.getElementById('scope-label');
function scopeName(v){v=Number(v)/256;return v<=-.5?'private':(v>=.5?'global':'public')}
function syncScope(){const s=scopeName(scope.value);scopeValue.value=s;scopeLabel.value=s;scopeLabel.textContent=s;}scope.addEventListener('input',syncScope);syncScope();
const delayState={post:<?=json_encode($postDelay,JSON_UNESCAPED_SLASHES)?>,message:<?=json_encode($messageDelay,JSON_UNESCAPED_SLASHES)?>};
function delayForType(){return document.getElementById('post-type').value==='message'?delayState.message:delayState.post}
function fmt(n){if(n===null)return '∞';if(n<60)return n+'s';let m=Math.ceil(n/60);if(m<60)return m+'m';return Math.floor(m/60)+'h '+(m%60)+'m'}
function paintDelay(){const s=delayForType(),b=document.getElementById('publish-button'),r=document.getElementById('delay-readout');if(s.infinite){b.disabled=true;r.textContent='Locked: ∞ until the full-negative/voteban balance is relieved (for example by deleting the rated content).';return}const elapsed=Math.floor((Date.now()/1000)-(window.__ttLoaded||Date.now()/1000)),left=Math.max(0,(s.remaining||0)-elapsed);b.disabled=left>0;r.textContent=left>0?'Locked for another '+fmt(left)+'. Current delay '+fmt(s.duration)+'.':'Ready. Current action delay '+fmt(s.duration)+'.';}
window.__ttLoaded=Date.now()/1000;document.getElementById('post-type').addEventListener('change',paintDelay);paintDelay();setInterval(paintDelay,1000);
</script></body></html>
