<?php
define('TT_DB_ALLOW_UNCONFIGURED', true);
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/theme_lib.php';

require_once __DIR__.'/session_bootstrap.php';
$theme = tt_theme_defaults();
if (!empty($_SESSION['loggedin']) && !empty($_SESSION['id']) && !empty($TT_DB_CONFIGURED) && class_exists('mysqli')) {
    try {
        $con = @new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
        if (!$con->connect_errno) {
            $con->set_charset('utf8mb4');
            $theme = tt_theme_load($con, (int)$_SESSION['id']);
            $con->close();
        }
    } catch (Throwable $e) {
        $theme = tt_theme_defaults();
    }
}
header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: private, no-store, max-age=0');
header('Vary: Cookie');
for ($i=0; $i<19; $i++) $theme[$i] = tt_theme_hex($theme[$i], tt_theme_defaults()[$i]);
$fontSize = max(3, min(36, (int)$theme[19]));
?>
:root{
<?php for ($i=0; $i<18; $i++): ?>  --tt-c<?= $i+1 ?>:<?= htmlspecialchars($theme[$i], ENT_QUOTES) ?>;
<?php endfor; ?>  --tt-text:<?= htmlspecialchars($theme[18], ENT_QUOTES) ?>;
  --tt-font-size:<?= $fontSize ?>px;
  --bg:var(--tt-c5);--panel:var(--tt-c4);--ink:var(--tt-text);--muted:var(--tt-c11);
  --line:var(--tt-c1);--dark:var(--tt-c9);--accent:var(--tt-c12);--surface:var(--tt-c18);
  --surface2:var(--tt-c17);--tag:var(--tt-c2);--button:var(--tt-c9);--button-ink:var(--tt-c18)
}
*{box-sizing:border-box;font-family:"Courier New",Courier,monospace}
html,body{margin:0;padding:0;background:var(--bg);color:var(--ink);font-size:var(--tt-font-size)}
a{color:var(--ink);text-decoration-thickness:1px;text-underline-offset:3px}a:hover{opacity:.7}
.tabletime{max-width:1100px;margin:24px auto;padding:0 18px}.content{max-width:1100px;margin:auto;padding:0 18px 40px}
.navtop{background:var(--surface);border-bottom:1px solid var(--line);margin-bottom:24px}.navtop>div{max-width:1100px;margin:auto;padding:16px;display:flex;gap:12px;align-items:center;flex-wrap:wrap}.navtop h1{margin:0 18px 0 0}.navtop a{padding:5px 7px}
form{max-width:760px;margin:18px auto;padding:18px;background:var(--surface);border:1px solid var(--line)}label{display:block;font-weight:bold;margin:10px 0 4px}
input,textarea,select,button{font:inherit;padding:9px;border:1px solid var(--line);max-width:100%;background:var(--surface2);color:var(--ink)}input[type=text],input[type=password],input[type=email],textarea,select{width:100%}textarea{min-height:180px;resize:vertical}
button,input[type=submit]{cursor:pointer;background:var(--button);color:var(--button-ink);border-color:var(--button)}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}.card{background:var(--surface);border:1px solid var(--line);padding:16px;text-align:left}.card h2,.card h3{margin:.2em 0}.meta{color:var(--muted);font-size:.9rem}.tag{display:inline-block;background:var(--tag);border:1px solid var(--line);border-radius:999px;padding:2px 7px;margin:2px;font-size:.85rem}
.notice{padding:12px;border:1px solid var(--line);background:var(--surface)}.error{border-color:#a33;background:#fee;color:#400}.ok{border-color:#398;background:#efe;color:#030}
.searchbar{display:flex;gap:8px;flex-wrap:wrap;max-width:100%;padding:12px}.searchbar input{flex:1;min-width:220px}.searchbar select{width:auto}.media{max-width:100%;height:auto}.actions{display:flex;gap:10px;flex-wrap:wrap;margin:12px 0}.actions a{display:inline-block;padding:7px 10px;border:1px solid var(--line);background:var(--surface)}
table{width:100%;border-collapse:collapse;background:var(--surface)}th,td{padding:9px;border:1px solid var(--line);text-align:left}
fieldset{border:1px solid var(--line);margin:14px 0;padding:14px}legend{font-weight:bold}.theme-grid{display:grid;grid-template-columns:repeat(6,minmax(74px,1fr));gap:10px}.theme-swatch{display:grid;gap:5px;text-align:center;font-size:.8rem}.theme-swatch input[type=color]{width:100%;height:52px;padding:2px}.theme-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.theme-preview{border:1px solid var(--line);background:var(--surface);padding:16px;margin-top:18px}.theme-preview .sample-dark{background:var(--dark);color:var(--button-ink);padding:10px}.theme-preview .sample-panel{background:var(--panel);padding:10px;margin-top:8px}
@media(max-width:700px){.theme-grid{grid-template-columns:repeat(3,1fr)}}

.video-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px}.video-grid figure{margin:0;border:1px solid var(--line);padding:8px;background:var(--surface2)}.video-grid video{width:100%;max-height:420px;background:#000}.tt-notify-enable{position:fixed;right:16px;bottom:16px;z-index:9999;box-shadow:0 2px 12px rgba(0,0,0,.25)}
