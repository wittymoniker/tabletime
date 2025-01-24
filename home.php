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


<!DOCTYPE html>
<html class = "tabletime">
<link href="style.php" rel="stylesheet" type="text/css">
<head class = "html">
		<meta charset="utf-8">
		<title>TABLETIME</title>
<body class = "content">  
 
<?php


/*
$id = $_SESSION['id'];
$colors;
$sql = "SELECT colors FROM accounts WHERE id = accounts($id)";
$colors = $mysqli->query($sql);
if ($colors != NULL){
	$colors = explode(";", $color);
	$colora= colors[0];
	$colorb= colors[1];
	$colorc= colors[2];
	$colord= colors[3];
	$colore= colors[4];
	$colorf= colors[5];
	
	$colora2= colors[6];
	$colorb2= colors[7];
	$colorc2= colors[8];
	$colord2= colors[9];
	$colore2= colors[10];
	$colorf2= colors[11];
	
	$colora3= colors[12];
	$colorb3= colors[13];
	$colorc3= colors[14];
	$colord3= colors[15];
	$colore3= colors[16];
	$colorf3= colors[17];
	
	$colort = colors[18];
	$fontSize = [19];
	
}
else{
	$colora= #ababab";
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
*/
?>

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


<br><br><h1><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>'s timetable</h1><br><br>




			<div>
			<h1>Friends</h1>
			<p>relevant friends' content<br>


<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'tabletime');


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT friends FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM accounts WHERE username IN $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["dt"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
?>
			<meta charset="utf-8">
					
		<body>
			<table class = "list">
				<tr>
					<th>name</th>
					<th>post</th>
					<th>dt</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><b><?php echo $row['title']; echo $row['post']; ?></b> <br> </td>
					<td><?php echo $row['dt']; }}?></td>
				</tr>

			</table>
			

			
			<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
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
			<?php endif; ?>
		</body>








</p>
		</div>












<div>
			<h1>Posts</h1>
			<p>relevant posts<br>
<a href="post.php"><i class="fas fa-user-circle"></i>Create New Post</a><br>







	
<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'tabletime');


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT friends FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM accounts WHERE username IN $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["dt"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
?>
			<meta charset="utf-8">
					
		<body>
			<table class = "list">
				<tr>
					<th>name</th>
					<th>post</th>
					<th>dt</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><b><?php echo $row['title']; echo $row['post']; ?></b> <br> </td>
					<td><?php echo $row['dt']; }}?></td>
				</tr>

			</table>
			

			
			<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
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
			<?php endif; ?>
		</body>






</p>
	</div>








<div>
			<h1>Events</h1>
			<p>relevant events<br>
<a href="post.php"><i class="fas fa-user-circle"></i>Create New Event </a><br>











	
<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'tabletime');


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT friends FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM accounts WHERE username IN $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["dt"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
?>
			<meta charset="utf-8">
					
		<body>
			<table class = "list">
				<tr>
					<th>name</th>
					<th>post</th>
					<th>dt</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><b><?php echo $row['title']; echo $row['post']; ?></b> <br> </td>
					<td><?php echo $row['dt']; }}?></td>
				</tr>

			</table>
			

			
			<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
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
			<?php endif; ?>
		</body>






</p>
		</div>







<div>
			<h1>Global</h1>
			<p>relevant global events, forums posts<br>
<a href="post.php"><i class="fas fa-user-circle"></i>Create New Forum Post </a><br>









	
<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'tabletime');


if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT friends FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM accounts WHERE username IN $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["dt"]))';
	$result = $mysqli->query($sql);
    }

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
?>
			<meta charset="utf-8">
					
		<body>
			<table class = "list">
				<tr>
					<th>name</th>
					<th>post</th>
					<th>dt</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
					<td><?php echo $row['name']; ?></td>
					<td><b><?php echo $row['title']; echo $row['post']; ?></b> <br> </td>
					<td><?php echo $row['dt']; }}?></td>
				</tr>

			</table>
			

			
			<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
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
			<?php endif; ?>
		</body>







</p>
		</div>










	</body>


<br>
<br>
<br>
<p align = "center">
<a href="account.php"><i class="fas fa-user-circle"></i>Account</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
</p>
<br>
<br>
</head>
</html>

