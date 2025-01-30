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
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
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
<?php include 'pagination.php';?>
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
	<h1>EVENT INSPECTOR</h1>
	<th><br>
<br>
<br><label name ="index">search: </label>"
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
	<?php
$index = $_POST['index'];
if($_POST['submit']){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM posts WHERE posts(type) == "event" && (* LIKE $index)  ';
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
		$table = $result;
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
	 

<br><br>
<div>


<?php
		}?>
		<?php
		if (ceil($total_pages / $num_results_on_page) > 0): ?>
		<ul class="pagination">
			<?php if ($page > 1): ?>
			<li class="prev"><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
			<?php endif; ?>

			<?php if ($page > 3): ?>
			<li class="start"><a href="pagination.php?page=1">1</a></li>
			<li class="dots">...</li>
			<?php endif; ?>

			<?php if ($page-2 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
			<?php if ($page-1 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

			<li class="currentpage"><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

			<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
			<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

			<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
			<li class="dots">...</li>
			<li class="end"><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
			<?php endif; ?>

			<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
			<li class="next"><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
			<?php endif; ?>
		</ul>
		<?php endif;?><?php
	} else {
		echo "0 posts";
	}
}

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
/////////
/////////
///////////

$sql = 'SELECT id FROM posts WHERE accounts(posts(name)) LIKE accounts($uname)  ';
/////////////
///////////
///////////
//////////


$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT * FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$result = $mysqli->query($sql);
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);

} else {
    echo "0 friends";
}
$sql = 'SELECT * FROM events WHERE * LIKE $friendslist ';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["title"]),
	($row["about"]),
	($row["posts"]),
	($row["members"]))';
	$result = $mysqli->query($sql);
	$table = $result;
	?>
			<meta charset="utf-8">
					
		<body>
			<table class = "list">
				<tr>
					<th>title</th>
					<th>about</th>
					<th>posts</th>
					<th>members</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
				<a href = "group.php?index='<?php echo $row["`" . $friendslist . $postslist . "`"]?>'"><td><?php echo $row['title']; ?></td></a>
					<td><?php echo $row['about']; ?></td>
					<td><?php echo $row['posts']; ?></td>
					<td><?php echo $row['members']; ?></td>
				</tr>

			</table><?php
			
    }?>
	<?php
			if (ceil($total_pages / $num_results_on_page) > 0): ?>
			<ul class="pagination">
				<?php if ($page > 1): ?>
				<li class="prev"><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
				<?php endif; ?>

				<?php if ($page > 3): ?>
				<li class="start"><a href="pagination.php?page=1">1</a></li>
				<li class="dots">...</li>
				<?php endif; ?>

				<?php if ($page-2 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
				<?php if ($page-1 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

				<li class="currentpage"><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

				<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
				<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
				<li class="dots">...</li>
				<li class="end"><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
				<?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
				<li class="next"><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
				<?php endif; ?>
			</ul>
			<?php endif;} }?>
			
			<?php

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM * LIKE $postslist  ')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}?>

		</body>


		<br><br>
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
?><br>





</p>
		</div>


<?php
		include 'Calendar.php';
		$searched=  $_POST['index'];
		$sql = 'SELECT posts(dt, title)  FROM posts WHERE title LIKE $searched ';
		$result = $mysqli->query($sql);
$events = $result;

$calendar = new Calendar(date('Y-m-d'));
 foreach ($events as $event): ?>
	
	<a href="#">
		
	$calendar->add_event(<?php echo $event?>, date('Y-m-d'));

	</a>
	<?php endforeach; ?>



<?=$calendar?>

<br><br>
</body>
</head>
</html>
