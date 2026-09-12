<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/theme_lib.php';
require_once __DIR__.'/session_bootstrap.php';
if (empty($_SESSION['loggedin']) || empty($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ($con->connect_errno) exit('Failed to connect to MySQL.');
$con->set_charset('utf8mb4');
$userId = (int)$_SESSION['id'];

if (empty($_SESSION['theme_csrf'])) $_SESSION['theme_csrf'] = bin2hex(random_bytes(24));
$message = '';
$messageClass = 'ok';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string)($_POST['csrf'] ?? '');
    if (!hash_equals((string)$_SESSION['theme_csrf'], $token)) {
        $message = 'Theme update rejected: invalid form token. Refresh this page and try again.';
        $messageClass = 'error';
    } else {
        $action = (string)($_POST['action'] ?? 'save');
        $themeToSave = $action === 'reset' ? tt_theme_defaults() : tt_theme_from_post($_POST);
        if (tt_theme_save($con, $userId, $themeToSave)) {
            $_SESSION['colors'] = tt_theme_serialize($themeToSave);
            $message = $action === 'reset' ? 'Tabletime theme reset to the default palette.' : 'Tabletime theme saved.';
            $theme = $themeToSave;
        } else {
            $message = 'Could not save the theme.';
            $messageClass = 'error';
        }
    }
}

if (!isset($theme)) $theme = tt_theme_load($con, $userId);
$con->close();
$names = [
    'style1a','style1b','style1c','style2a','style2b','style2c',
    'style3a','style3b','style3c','style4a','style4b','style4c',
    'style5a','style5b','style5c','style6a','style6b','style6c'
];
$labels = [
    'Light A','Light B','Light C','Light D','Light E','Light F',
    'Dark A','Dark B','Dark C','Dark D','Dark E','Dark F',
    'Surface A','Surface B','Surface C','Surface D','Surface E','Surface F'
];
function tt_h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html class="tabletime" lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Tabletime Theme</title>
<link href="style.php?v=theme2" rel="stylesheet" type="text/css">
</head>
<body class="content">
<nav class="navtop"><div class="tabletime">
<h1><a href="/">TABLETIME</a></h1>
<a href="post.php">Messages</a><a href="create.php">Create</a><a href="friend.php">Friends</a>
<a href="file.php">Files</a><a href="profile.php">Profile</a><a href="statsmap.php">Stats/Map</a>
<a href="group.php">Groups</a><a href="people.php">People</a><a href="event.php">Events</a>
</div></nav>

<main class="tabletime">
<h1>Color Formatting</h1>
<p>Tabletime keeps the original 18-color user palette, plus a text color and font-size setting. Your saved palette follows your account on this Tabletime node.</p>
<?php if ($message !== ''): ?><p class="notice <?= tt_h($messageClass) ?>"><?= tt_h($message) ?></p><?php endif; ?>

<form method="post" action="colorpick.php" id="theme-form">
<input type="hidden" name="csrf" value="<?= tt_h($_SESSION['theme_csrf']) ?>">
<fieldset>
<legend>18-color palette</legend>
<div class="theme-grid">
<?php foreach ($names as $i => $name): ?>
<label class="theme-swatch"><?= tt_h($labels[$i]) ?>
<input type="color" name="<?= tt_h($name) ?>" value="<?= tt_h($theme[$i]) ?>" data-theme-index="<?= $i ?>">
</label>
<?php endforeach; ?>
</div>
</fieldset>

<fieldset>
<legend>Text and scale</legend>
<label>Text color
<input type="color" name="styletext" value="<?= tt_h($theme[18]) ?>" data-theme-text>
</label>
<label>Font size: <output id="font-size-output"><?= (int)$theme[19] ?></output> px
<input type="range" name="stylesize" min="3" max="36" value="<?= (int)$theme[19] ?>" data-theme-size>
</label>
</fieldset>

<div class="theme-actions">
<button type="submit" name="action" value="save">Save Theme</button>
<button type="submit" name="action" value="reset" onclick="return confirm('Reset your Tabletime colors and font size to the default theme?')">Reset to Tabletime Default</button>
<a href="profile.php">Back to Profile</a>
</div>

<div class="theme-preview" id="theme-preview">
<strong>Live preview</strong>
<div class="sample-panel">Panel / card surface. <a href="#preview">Example link</a> <span class="tag">example tag</span></div>
<div class="sample-dark">Dark/action surface</div>
</div>
</form>
</main>
<script>
(() => {
  const root = document.documentElement;
  const colors = [...document.querySelectorAll('[data-theme-index]')];
  const text = document.querySelector('[data-theme-text]');
  const size = document.querySelector('[data-theme-size]');
  const out = document.getElementById('font-size-output');
  const apply = () => {
    colors.forEach((el, i) => root.style.setProperty(`--tt-c${i+1}`, el.value));
    root.style.setProperty('--tt-text', text.value);
    root.style.setProperty('--tt-font-size', `${size.value}px`);
    out.value = size.value;
  };
  colors.forEach(el => el.addEventListener('input', apply));
  text.addEventListener('input', apply); size.addEventListener('input', apply);
})();
</script>
</body>
</html>
