<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
error_reporting(E_ERROR | E_PARSE);
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>


<html class = "tabletime">
<link href="style.php" rel="stylesheet" type="text/css">
<head class = "html">
		<meta charset="utf-8">
		<title>TABLETIME</title>
<body class = "content">  

<nav class = "navtop">
		<div class = "tabletime">		

<h1><b><a href="home.php">TABLETIME</a></b></h1>
<p>
<a href="post.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="create.php"><i class="fas fa-user-circle"></i>Create</a>
<a href="friend.php"><i class="fas fa-user-circle"></i>Friends</a><br>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a><br>
<a href="people.php"><i class="fas fa-user-circle"></i>People</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a></p><br>




			</div>
</nav>






</body>
</head>
</html>
