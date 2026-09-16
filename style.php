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
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:14px}.card{background:var(--surface);border:1px solid var(--line);padding:16px;text-align:left;min-width:0;overflow-wrap:anywhere}.card h2,.card h3{margin:.2em 0}.meta{color:var(--muted);font-size:.9rem}.tag{display:inline-block;background:var(--tag);border:1px solid var(--line);border-radius:999px;padding:2px 7px;margin:2px;font-size:.85rem}
.notice{padding:12px;border:1px solid var(--line);background:var(--surface)}.error{border-color:#a33;background:#fee;color:#400}.ok{border-color:#398;background:#efe;color:#030}
.searchbar{display:flex;gap:8px;flex-wrap:wrap;max-width:100%;padding:12px}.searchbar input{flex:1;min-width:220px}.searchbar select{width:auto}.media{max-width:100%;height:auto}.actions{display:flex;gap:10px;flex-wrap:wrap;margin:12px 0}.actions a{display:inline-block;padding:7px 10px;border:1px solid var(--line);background:var(--surface)}
table{width:100%;border-collapse:collapse;background:var(--surface)}th,td{padding:9px;border:1px solid var(--line);text-align:left}
fieldset{border:1px solid var(--line);margin:14px 0;padding:14px}legend{font-weight:bold}.theme-grid{display:grid;grid-template-columns:repeat(6,minmax(74px,1fr));gap:10px}.theme-swatch{display:grid;gap:5px;text-align:center;font-size:.8rem}.theme-swatch input[type=color]{width:100%;height:52px;padding:2px}.theme-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.theme-preview{border:1px solid var(--line);background:var(--surface);padding:16px;margin-top:18px}.theme-preview .sample-dark{background:var(--dark);color:var(--button-ink);padding:10px}.theme-preview .sample-panel{background:var(--panel);padding:10px;margin-top:8px}
@media(max-width:700px){.theme-grid{grid-template-columns:repeat(3,1fr)}}

.video-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:12px}.video-grid figure{margin:0;border:1px solid var(--line);padding:8px;background:var(--surface2)}.video-grid video{width:100%;max-height:420px;background:#000}.tt-notify-enable{position:fixed;right:16px;bottom:16px;z-index:9999;box-shadow:0 2px 12px rgba(0,0,0,.25)}

.scope-slider,.karma-slider{width:100%;padding:0;accent-color:var(--dark)}
.slider-labels{display:flex;justify-content:space-between;gap:10px;font-size:.82rem;color:var(--muted);margin:4px 0 10px}.scope-control{min-width:260px;flex:1}.inline-check{display:flex;gap:6px;align-items:center;font-weight:normal}.inline-check input{width:auto}.rating-form{max-width:none;margin:12px 0;padding:12px}.karma-summary{margin-top:12px;padding:8px;border:1px solid var(--line);background:var(--surface2)}.inline-form{display:inline;padding:0;margin:0;background:none;border:0}.inline-form button{width:auto}.scope-search{align-items:end}

/* Post comment presentation: keep long comments/URLs inside their card and make the disclosure read like a link. */
.comments-disclosure{margin-top:10px;max-width:100%;min-width:0}
.comments-disclosure>summary{display:inline-block;cursor:pointer;color:var(--ink);font-weight:bold;text-decoration:underline;text-decoration-thickness:1px;text-underline-offset:3px;padding:2px 0;list-style:none}
.comments-disclosure>summary::-webkit-details-marker{display:none}
.comments-disclosure>summary::before{content:"▸ ";display:inline-block;text-decoration:none}
.comments-disclosure[open]>summary::before{content:"▾ "}
.comments-disclosure>summary:hover{opacity:.7}
.comments-disclosure>summary:focus-visible{outline:2px solid var(--ink);outline-offset:3px}
.comments-body{white-space:pre-wrap;overflow-wrap:anywhere;word-break:break-word;max-width:100%;min-width:0;margin:8px 0 0;padding:10px;border:1px solid var(--line);background:var(--surface2);font:inherit;line-height:1.45}
.post-card{cursor:zoom-in;transition:padding .15s ease,border-width .15s ease}
.post-card.is-expanded{grid-column:1/-1;cursor:zoom-out;padding:24px;border-width:2px}
.post-card.is-expanded>p:not(.meta){font-size:1.04em;line-height:1.55}
.post-card.is-expanded .comments-body{line-height:1.55}
@media(max-width:700px){.post-card.is-expanded{padding:18px}}

/* Forum time/content cloud: occupied time bands descend vertically; each band is a horizontal post array. */
.forum-cloud{display:flex;flex-direction:column;gap:16px;min-width:0}
.forum-cloud-controls{max-width:none}
.forum-time-row{min-width:0;border-left:3px solid var(--line);padding-left:10px}
.forum-time-row>header{font-size:.92rem;color:var(--muted);margin:0 0 7px 2px}
.forum-row-posts{display:flex;gap:12px;overflow-x:auto;overflow-y:hidden;padding:2px 2px 12px;scroll-snap-type:x proximity;align-items:stretch}
.forum-cloud-post{flex:0 0 min(360px,82vw);scroll-snap-align:start;transition:flex-basis .16s ease,padding .15s ease,border-width .15s ease}
.forum-row-posts .post-card.is-expanded{grid-column:auto;flex-basis:min(900px,92vw);width:auto}
.forum-cloud-post>p,.forum-cloud-post .comments-body{overflow-wrap:anywhere;word-break:break-word}
@media(max-width:700px){.forum-cloud-post{flex-basis:86vw}.forum-row-posts .post-card.is-expanded{flex-basis:94vw}}

.post-image-link{display:block;margin:10px 0}.post-image{display:block;max-width:100%;max-height:520px;object-fit:contain;border:1px solid var(--line);border-radius:10px;padding:4px;background:rgba(0,0,0,.15)}.ad-credit-box,.boost-disclosure{margin:14px 0;padding:12px;border:1px solid var(--line);border-radius:10px}.boost-form{display:grid;gap:8px}.ad-card{outline:1px solid var(--accent)}

/* Reusable expandable cards for People, media, files/tags and other dense entry lists. */
.tt-expand-card{cursor:zoom-in;transition:padding .15s ease,border-width .15s ease,box-shadow .15s ease}
.tt-expand-card.is-expanded{grid-column:1/-1;cursor:zoom-out;padding:24px;border-width:2px;box-shadow:0 3px 14px rgba(0,0,0,.12)}
.tt-expand-card.is-expanded>p:not(.meta){font-size:1.04em;line-height:1.55}
.profile-media{display:block;width:100%;max-width:720px;max-height:520px;object-fit:contain;margin:10px 0;border:1px solid var(--line);background:var(--surface2);border-radius:10px}
.profile-audio{width:100%;margin:10px 0}.person-card .rating-form{margin-top:10px}
@media(max-width:700px){.tt-expand-card.is-expanded{padding:18px}}

.profile-heading{display:flex;gap:14px;align-items:center}.profile-avatar{width:88px;height:88px;object-fit:cover;border-radius:50%;border:1px solid var(--line);flex:0 0 88px}.profile-avatar-empty{display:grid;place-items:center;background:var(--surface2);font-weight:bold}.moksha-summary{margin:.35rem 0;padding:6px 8px;border:1px solid var(--line);background:var(--surface2)}.person-card{cursor:default}.profile-moksha-form{display:block!important;max-height:none!important;overflow:visible!important}.selected-profile{outline:2px solid var(--line)}

/* People profiles: compact summary stays visible; expansion reveals profile details and controls. */
.person-card .person-expanded-content{display:none}
.person-card.is-expanded .person-expanded-content,.person-card.selected-profile .person-expanded-content{display:block}
.person-card .profile-heading{align-items:flex-start}
.person-card .moksha-summary{margin:.2rem 0}
.selected-profile-view{margin:18px 0}
.post-author-link{font-weight:600}

.earnings-total{font-size:2rem;font-weight:800;margin:.4rem 0}.earnings-list p{border-bottom:1px solid var(--line);padding:.55rem 0;margin:0}.earnings-grid{grid-template-columns:repeat(auto-fit,minmax(280px,1fr))}

/* TABLETIME_NSFW_BLUR_20260915 */
[data-tt-nsfw-blur-ready="1"] {
  position: relative;
  isolation: isolate;
}

[data-tt-nsfw-blur-ready="1"].tt-nsfw-locked > :not(.tt-nsfw-reveal) {
  filter: blur(18px);
  opacity: .56;
  pointer-events: none;
  user-select: none;
}

.tt-nsfw-reveal {
  position: absolute;
  inset: 0;
  z-index: 20;
  width: 100%;
  min-height: 100%;
  display: grid;
  place-items: center;
  padding: 14px;
  border: 0;
  border-radius: inherit;
  color: inherit;
  background: rgba(0, 0, 0, .22);
  cursor: pointer;
  text-align: center;
}

.tt-nsfw-reveal-inner {
  display: grid;
  gap: 4px;
  max-width: 28rem;
  padding: 12px 16px;
  border: 1px solid var(--line, rgba(255,255,255,.45));
  border-radius: 12px;
  background: var(--surface, rgba(16,16,18,.94));
  color: var(--ink, #fff);
  box-shadow: 0 8px 24px rgba(0,0,0,.28);
}

.tt-nsfw-title { font-weight: 700; }
.tt-nsfw-hint { font-size: .92em; opacity: .88; }
.tt-nsfw-reveal:focus-visible .tt-nsfw-reveal-inner {
  outline: 2px solid currentColor;
  outline-offset: 3px;
}
.tt-nsfw-revealed > .tt-nsfw-reveal { display: none; }
.collection-gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;margin:12px 0 24px}.collection-media{margin:0;padding:10px;border:1px solid var(--line);border-radius:10px;background:var(--surface)}.collection-media img,.collection-media video{width:100%;max-height:320px;object-fit:contain;border-radius:8px}.collection-media audio{width:100%}.event-calendar{display:grid;gap:12px;margin-bottom:24px}.event-day h3{margin-top:0}.tt-call-dock{position:fixed;right:12px;bottom:12px;z-index:9999;width:min(360px,calc(100vw - 24px));display:grid;gap:8px}.tt-call-dock[hidden]{display:none}.tt-call-dock>div{background:var(--surface);border:2px solid var(--accent);box-shadow:0 8px 28px rgba(0,0,0,.25);padding:12px;border-radius:12px;display:grid;gap:6px}.call-presence-row{padding:8px 0;border-bottom:1px solid var(--line)}

/* Safari/cross-browser call participant presence */
.participant-tile{position:relative;margin:0;border:1px solid var(--line);padding:8px;background:var(--surface2);min-height:180px;display:flex;flex-direction:column;gap:6px}.participant-tile video{display:block;width:100%;min-height:140px;max-height:420px;background:#000;object-fit:contain}.participant-tile figcaption{display:flex;justify-content:space-between;gap:8px;align-items:center;flex-wrap:wrap}.participant-state{font-size:.82rem;color:var(--muted)}.tt-video-play{position:absolute;left:16px;top:16px;z-index:3}.participant-tile.is-local{outline:1px solid var(--accent);outline-offset:1px}

/* Tabletime calls v12: pre-call device setup and flat participant grid */
.call-device-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px;align-items:end}.call-device-grid label{display:flex;flex-direction:column;gap:5px}.device-preview{max-width:520px;margin-top:12px}.device-preview video{aspect-ratio:16/9}.video-grid#participant-grid{grid-template-columns:repeat(auto-fit,minmax(220px,1fr))}
