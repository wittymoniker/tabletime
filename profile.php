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
<a href="friend.php"><i class="fas fa-user-circle"></i>Friends</a><br>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a>
<a href="forum.php"><i class="fas fa-user-circle"></i>Forums</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a><br>
<a href="people.php"><i class="fas fa-user-circle"></i>People</a>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a><br>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a>
<a href="create.php"><i class="fas fa-user-circle"></i>Create</a></p>




			</div>
</nav>



<div>
	<table>
<form method ="POST">
	<th><br><label name ="range">private/public/global range: </label>"
<input type = "range" id = "view" name = "private/public/global" min = "-256" max = "256">

<br><label name ="index">username: </label>"
<input type = "text" name="index" placeholder = "username">"
</th>
<tr>
<br>
<input type = "submit" value = "submit">
</tr>
</form>
</table>
</div>




</form>
<form method ="POST">
<label name ="rate"> leave rating (-/+) karma/moksha: </label>"
<input type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
</form>
<br>
</body>
</head>
</html>
