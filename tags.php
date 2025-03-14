<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con = $mysqli;
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
$id = $_SESSION['id'];
$color;
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
}
?>

<html class = "tabletime">

<link href="style.php" rel="stylesheet" type="text/css">




<head class = "navtop">
<body class = "content">
		<title>TABLETIME</title>

<nav class = "navtop">
		<div class = "tabletime">		
			<h1><b><a href="home.php">TABLETIME</a></b></h1>
<p>
<a href="messages.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a>
<a href="forum.php"><i class="fas fa-user-circle"></i>Forums</a><br>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a>
<a href="tags.php"><i class="fas fa-user-circle"></i>Tags</a>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a><br>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profiles</a>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a><br>
<a href="create.php"><i class="fas fa-user-circle"></i><b>Create</b></a></p>
			</div>
</nav>

<div>


	<table>
<form method ="POST">
	<h1>PROFILE INSPECTOR</h1>
	<th><br>
<br>
<br><br>

<br><label name ="index">tag/IDs: </label>"
<input type = "text" name="index" placeholder = "username">"
</th>
<tr>
<br>
</tr>

</table>
</div>
<div>
<label name ="rate"> <br>leave rating (-/+) karma/moksha: </label>"
<input method = "POST" type = "range" id = "perspective" name = "rate" min = "-256" max = "256">

<br>
<br><label name ="index">search terms: </label>"
<input method ="POST" type = "text" name="tags" placeholder = "search terms...">"
</th>
<tr>
<br>
<input method ="POST" type = "submit" name= "enter" value = "enter" >
</tr>
</form><br>
</table>
</div>


<br><br><br>
<div>

<div>
	<table>
<form method ="POST">
<?php
if ($_POST['enter']){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM accounts WHERE (* LIKE $index) BY ((array_sum(accounts(votes))/(count(accounts(votes))) DESC';
	$result = $mysqli->query($sql);
	$table = $result;
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$feature = $row;
		}
	}



if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$searchindex = $_POST['tags'];
/////////
/////////
///////////
$uname = $_SESSION['name'];
$postslist;
$index = $_POST['index'];




if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = 'SELECT * FROM tags LIKE $searchindex ORDER BY value DESC BY ((array_sum(accounts(votes))/(count(accounts(votes))) DESC';
$result = $mysqli->query($sql);




$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["forums"]),
	($row["posts"]),
	($row["groups"]),
	(($row["events"]),
	($row["value"])	';
	$result = $mysqli->query($sql);?>
	
	<meta charset="utf-8">
					
	<br>
		<table class = "list">
			<tc>
				<tr>forums</tr>
				<tr>posts</tr>
				<tr>events</tr>
				<tr>groups</tr>
				<tr>VALUE</tr>
				
				</tc>
				<tc>
								

			<?php 
			$result = $friendslist;
			$row= $result;
			if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()){ ?>

			<a href = "forum.php?index='<?php echo $row[$_POST['index']];?>'"><tr><?php echo $row['name']; ?></tr></a>
				<tr><b><?php echo ($row['forums']); ?></b> </tr>
				<tr><b><?php echo $row['posts']; ?></b> </tr>
						<tr><b><?php echo $row['events']; ?></b></tr>
						<tr><b><?php echo $row['groups']; ?></b> </tr>
				<tr><b><?php echo $row['value']; ?></b> </tr>
				
			</tc>

		</table>
<?php
				
    }

} else {
    echo "0 posts";
}
$uname = $_SESSION['name'];
$searchindex = $_POST['index'];
$sql = 'SELECT posts WHERE tags LIKE  $index BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["file"])),
	($row["dt"])';
	$result = $mysqli->query($sql);?>
	<meta charset="utf-8">
					
	<body>
		<table class = "list">
			<tr>
				<th>name</th>
				<th>topic</th>
				<th>content</th>
				<th>file</th>
				<th>dt</th>
								</tr>
			<?php $result = $postslist;?><?php
			$row = $result;?>
			<?php if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()){ ?>
			<tr>
				<td><?php echo $row['name']; ?></td>
				<td><b><?php echo $row['title']; echo $row['post']; ?></b> <br> </td>
				<td><?php echo $row['content']; }}?></td>
				<td><?php echo $row['file']; ?></td>
				<td><b><?php echo $row['dt']; echo $row['post']; ?></b> <br> </td>
			</tr>

		</table>
		

					




		
</p>
	</div>










<?php
		
		

	

    }
	

} else {
    echo "0 posts";
}

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM $postslist BY dt DESC')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}

if(isset($_POST['enter'])){
    $usrname = $_POST['index'];
    $id = $_SESSION['id'];
    $vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
    if(isset($_POST['perspective'])){
        $sql = "UPDATE accounts ADD $vote TO votes WHERE username LIKE $searchindex || $index";
        $result = $mysqli->query($sql);

    }
	if(isset($_POST['enter'])){
		$votetarget = $_POST['index'];
		$id = $_SESSION['id'];
		$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
		if(isset($_POST['perspective'])){
			$sql = "UPDATE posts ADD $vote TO votes WHERE name  LIKE $searchindex || $index";
			$result = $mysqli->query($sql);
	
		}
	}
}
}}}
?>

<?php require 'pagination.php';?>


</p>
		</div>




</body>
</head>
</html>
