<?php
session_start();

$DATABASE_HOST = 'sql103.infinityfree.com';
$DATABASE_USER = 'if0_38191057';
$DATABASE_PASS = 'Greenapples55';
$DATABASE_NAME = 'if0_38191057_tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con= $mysqli;
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

?>
<?php 
$id = $_SESSION['id'];
$color;
$con =  $mysqli;
$stmt = $con->prepare('SELECT colors FROM accounts WHERE id =?');

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($color);
$stmt->fetch();
$stmt->close();
if ($color != NULL){
	$color = explode(";", $color);
	$colora= color[0];
	$colorb= color[1];
	$colorc= color[2];
	$colord= color[3];
	$colore= color[4];
	$colorf= color[5];
	
	$colora2= color[6];
	$colorb2= color[7];
	$colorc2= color[8];
	$colord2= color[9];
	$colore2= color[10];
	$colorf2= color[11];
	
	$colora3= color[12];
	$colorb3= color[13];
	$colorc3= color[14];
	$colord3= color[15];
	$colore3= color[16];
	$colorf3= color[17];
	
	$colort = color[18];
	$fontSize = [19];
	
}
else{
	$colora= "#ababab";
$colorb= "#bcbcbc";
$colorc= "#cdcdcd";
$colord= "#dcdcdc";
$colore= "#ededed";
$colorf= "#dfdfdf";

$colora2= "#0a0a0a";
$colorb2= "#1b1b1b";
$colorc2= "#2c2c2c";
$colord2= "#3d3d3d";
$colore2= "#4e4e4e";
$colorf2= "#5f5f5f";

$colora3= "#a3a3a3";
$colorb3= "#b2b2b2";
$colorc3= "#c1c1c1";
$colord3= "#d1d1d1";
$colore3= "#e2e2e2";
$colorf3= "#f3f3f3";



$colort = "#000000";
$fontSize = "14";
}?>


<html class = "tabletime">

<link href="style.php" rel="stylesheet" type="text/css">




		<meta charset="utf-8">
		<br><br><title>TABLETIME</title>

		<head class = "content">

<body class = "html">

		<nav class = "content">
		<div class = "content">		
			<h1>		<br><img src="tabletime logo.png" alt="tabletime logo" width="50" height="50"><br>
			<b><a href="home.php">TABLETIME</a></b>
<p>
<a href="messages.php"><i class="tabletime"></i>Messages</a>
<a href="post.php"><i class="tabletime"></i>Posts</a>
<a href="forum.php"><i class="tabletime"></i>Forums</a><br>
<a href="event.php"><i class="tabletime"></i>Events</a>
<a href="tags.php"><i class="tabletime"></i>Tags</a>
<a href="group.php"><i class="tabletime"></i>Groups</a><br>
<a href="statsmap.php"><i class="tabletime"></i>Stats/Map</a>
<a href="profile.php"><i class="tabletime"></i>Profiles</a>
<a href="file.php"><i class="tabletime"></i>Files</a><br>
<a href="create.php"><i class="tabletime"></i><b>Create</b></a></p></h1>
			</div>
</nav>


<div>
	<p>
<h1> Private Messages</h1></br>
<?php require 'pagination.php';
$message = "No new messages/posts/notifications!";
$con =  $mysqli;
if($stmt = $con-> prepare('SELECT  messages FROM accounts WHERE id = ?')){
$prof;
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($message);
$stmt->fetch();
$stmt->close();
}
$con =  $mysqli;
echo $message;?>
</p></div>



</body>
</head>
</html>
