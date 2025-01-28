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
<a href="messages.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a>
<a href="forum.php"><i class="fas fa-user-circle"></i>Forums</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a><br>
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
	<h1>INSPECTOR- click or search</h1>
	<th><br><label name ="range">private/public/global range: </label>"
<input method ="POST" type = "range" id = "view" name = "private/public/global" min = "-256" max = "256">
<br>
<br><label name ="index">post search prompt: </label>"
<input method ="POST" type = "text" name="index" placeholder = "search terms...">"
</th>
<tr>
<br>
<input method ="POST" type = "submit" value = "submit">
</tr>
</form><br>
</table>
</div>
<table>
	<h1>POSTS</h1>
	<?php
$index = $_POST['index'];
if($_POST['submit']){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM posts WHERE (* LIKE $index) ';
	$result = $mysqli->query($sql);
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $feature VALUES
		(($row["name"]),
		($row["title"]),
		($row["content"]),
		($row["dt"])),
		($row["file"]);
		(($row["tags"]),
		($row["recipients"]),
		($row["comments"]),
		($row["scope"])),
		($row["type"])),
		($row["id"]))';
		$result = $mysqli->query($sql);
		}
	
	} else {
		echo "0 posts";
	}
}
?>
	<th>name</th>
					<th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>dt</th>
					<th>dt</th>
					<tr>
					<td><?php echo $feature[0];?>
	</td>
					<td><b><?php echo $feature[1];?></b> <br> </td>
					<td><?php echo $feature[2];?></td>
					<td><?php echo $feature[3];?></td>
					<td><b><?php echo $feature[4];?></b> <br> </td>
				</tr>
				<th>tags</th>
				<th>recipients</th>
				<th>comments</th>
				<th>scope</th>
				<th>type</th>
				<tr>
					<td><?php echo $feature[5];?></td>
					<td><b><?php echo $feature[6];?></b> <br> </td>
					<td><?php echo $feature[7];?></td>
					<td><?php echo $feature[8];?></td>
					<td><b><?php echo $feature[9];?></b> <br> </td>
				</tr>
</table><br><br><br>
	 

<form method ="POST">
<label name ="rate"> <br>leave rating (-/+) karma/moksha: </label>"
<input method = "POST" type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
</form><?php
if(isset($_POST['submit'])){
    $votetarget = $_POST['index'];
    $id = $_SESSION['id'];
    $vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
    if(isset($_POST['perspective'])){
        $sql = "UPDATE posts ADD $vote TO votes WHERE id == $feature[10]";
        $result = $mysqli->query($sql);

    }
}
if(isset($_POST['submit'])){
    $votetarget = $_POST['index'];
    $id = $_SESSION['id'];
    $vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
    if(isset($_POST['perspective'])){
        $sql = "UPDATE accounts ADD $vote TO votes WHERE username == $votetarget";
        $result = $mysqli->query($sql);

    }
}
?><br><br><br>
<h1>ACCOUNTS</h1>
	<?php
$index = $_POST['index'];
if($_POST['submit']){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM accounts  WHERE (* LIKE $index) ';
	$result = $mysqli->query($sql);
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $feature VALUES
		(($row["name"]),
		($row["title"]),
		($row["content"]),
		($row["dt"])),
		($row["file"]);
		(($row["tags"]),
		($row["recipients"]),
		($row["comments"]),
		($row["scope"])),
		($row["type"]))';
		$result = $mysqli->query($sql);
		}
	
	} else {
		echo "0 posts";
	}
}


?>
	<th>name</th>
					<th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>dt</th>
					<th>dt</th>
					<tr>
					<td><?php echo $feature[0];?>
	</td>
					<td><b><?php echo $feature[1];?></b> <br> </td>
					<td><?php echo $feature[2];?></td>
					<td><?php echo $feature[3];?></td>
					<td><b><?php echo $feature[4];?></b> <br> </td>
				</tr>
				<th>tags</th>
				<th>recipients</th>
				<th>comments</th>
				<th>scope</th>
				<th>type</th>
				<tr>
					<td><?php echo $feature[5];?></td>
					<td><b><?php echo $feature[6];?></b> <br> </td>
					<td><?php echo $feature[7];?></td>
					<td><?php echo $feature[8];?></td>
					<td><b><?php echo $feature[9];?></b> <br> </td>
				</tr>
</table><br><br><br>
	 






</p>
		</div>




</body>
</head>
</html>
