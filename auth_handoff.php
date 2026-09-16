<?php
require_once __DIR__.'/session_bootstrap.php';
require_once __DIR__.'/age_gate_session.php';
header('Cache-Control: private, no-store, max-age=0, must-revalidate');
header('Pragma: no-cache');
header('Referrer-Policy: no-referrer');
header('X-Robots-Tag: noindex, nofollow, noarchive');
$returnTo=tt_age_safe_return((string)($_GET['return']??'home.php'),'home.php');
$ticket=(string)($_GET['t']??'');
$ok=$ticket!=='' && tt_restore_login_handoff($ticket);
if(!$ok && !empty($_SESSION['loggedin'])&&!empty($_SESSION['id'])) $ok=true;
if($ok){
    tt_age_mark_passed();
    if(session_status()===PHP_SESSION_ACTIVE)@session_write_close();
    // Deliberately return 200 here. Safari/WebKit and intermediary stacks are
    // more reliable about committing Set-Cookie from a normal response than
    // from a chain of redirects. JavaScript replaces the URL immediately; the
    // meta refresh is the no-script fallback.
    $safe=htmlspecialchars($returnTo,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');
    $js=json_encode($returnTo,JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_UNESCAPED_SLASHES);
    echo '<!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta http-equiv="refresh" content="0;url='.$safe.'"><link rel="stylesheet" href="style.php"><title>Tabletime · Signed in</title></head><body><main class="content"><section class="card"><h2>Signed in</h2><p>Opening Tabletime…</p><p><a href="'.$safe.'">Continue</a></p></section></main><script>location.replace('.$js.');</script></body></html>';
    exit;
}
header('Location: login.php?handoff=expired&return='.rawurlencode($returnTo),true,303);
exit;
?>
