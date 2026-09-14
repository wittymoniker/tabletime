<?php
require_once __DIR__.'/session_bootstrap.php';
require_once __DIR__.'/entry_instructions.php';
if(!empty($_SESSION['loggedin'])){header('Location: home.php');exit;}
?><!doctype html><html class="tabletime"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="style.php"><title>Tabletime</title></head><body><main class="content"><h1>TABLETIME</h1><p>Social posting, tags, forums, groups, events and decentralized-host experimentation.</p><div class="actions"><a href="register.php">Register</a><a href="login.php">Login</a><a href="post.php">Browse public posts</a><a href="setup.php">Database setup / repair</a></div><img src="tabletime logo.png" alt="Tabletime logo" style="max-width:250px"><?php tt_entry_instructions(); ?></main></body></html>
