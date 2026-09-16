<?php
require_once __DIR__.'/session_bootstrap.php';
require_once __DIR__.'/age_gate_session.php';
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
$returnTo=tt_age_safe_return((string)($_POST['return']??$_GET['return']??'home.php'),'home.php');
if(tt_age_is_passed()){header('Location: '.tt_age_return_with_confirmation($returnTo),true,303);exit;}
$error='';
if(($_SERVER['REQUEST_METHOD']??'GET')==='POST'){
    if((string)($_POST['age18']??'')!=='1'){$error='You must confirm that you are 18 or older to continue.';}
    else{
        tt_age_mark_passed();
        // Wasmer/serverless PHP can rotate workers between the POST and redirect.
        // Flush session state before the 303; the durable HttpOnly cookie is already emitted.
        if(session_status()===PHP_SESSION_ACTIVE)@session_write_close();
        header('Location: '.tt_age_return_with_confirmation($returnTo),true,303);exit;
    }
}
function tt_age_h($v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
?><!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · 18+ confirmation</title></head><body><main class="content"><h1><a href="/">TABLETIME</a></h1><section class="card"><h2>18+ confirmation</h2><?php if($error):?><p class="notice error"><?=tt_age_h($error)?></p><?php endif;?><form method="post" action="age_check.php" autocomplete="off"><input type="hidden" name="return" value="<?=tt_age_h($returnTo)?>"><label class="inline-check"><input type="checkbox" name="age18" value="1" required> I confirm that I am 18 or older.</label><button type="submit">Continue</button></form><p class="meta">The confirmation is retained for your persistent Tabletime session. Confirmation routes cannot redirect back into themselves.</p></section></main>
<!-- TABLETIME_NSFW_BLUR_20260915 -->
<script src="nsfw-blur.js?v=20260915c" defer></script>
</body></html>
