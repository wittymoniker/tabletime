<?php
require_once __DIR__.'/session_bootstrap.php';
require_once __DIR__.'/entry_instructions.php';
if (!empty($_SESSION['loggedin'])) { header('Location: home.php'); exit; }
$num1=random_int(1,500); $num2=random_int(1,500);
?><!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime · Register</title></head><body><main class="content"><h1><a href="/">TABLETIME</a></h1><form method="post" action="newuser.php"><h2>Register</h2><label>Username</label><input name="username" maxlength="50" required><label>Password</label><input type="password" name="password" required><label>Email</label><input type="email" name="email" required><label><?= $num1 ?> + <?= $num2 ?></label><input type="hidden" name="no1" value="<?= $num1 ?>"><input type="hidden" name="no2" value="<?= $num2 ?>"><input name="test" inputmode="numeric" required><label class="inline-check"><input type="checkbox" name="age18" value="1" required> I confirm that I am 18 or older.</label><input type="submit" name="submit" value="Register"><p class="meta">Successful registration signs you in immediately with no login delay. A failed registration attempt activates the original 30-minute timing rule and repeated failures escalate it.</p></form><p><a href="login.php">Already registered? Log in.</a></p><?php tt_entry_instructions(); ?></main>
<!-- TABLETIME_NSFW_BLUR_20260915 -->
<script src="nsfw-blur.js?v=20260915c" defer></script>
</body></html>
