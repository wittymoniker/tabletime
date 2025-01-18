<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
//error_reporting(E_ERROR | E_PARSE);
?>


<!DOCTYPE html>
<link href="style.css" rel="stylesheet" type="text/css">
<html>
	<head>
		<meta charset="utf-8">
		<title size = "39">TABLETIME</title>
		
		
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1 size = "39"><b>TABLETIME</b></h1>

<p align = "center">
<a href="post.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="friends.php"><i class="fas fa-user-circle"></i>Friends </a><a href="Groups.php"><i class="fas fa-user-circle"></i>Groups </a><a href="people.php"><i class="fas fa-user-circle"></i>People </a>
<a href="Events.php"><i class="fas fa-user-circle"></i>Events </a>

</p>

			</div>
		</nav>
		<div class="tabletime">
			<h1><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?>'s timetable</h1>
</div>
<div>


		</div>










<div class = "content" method = "get" align = "center">
			<h1>Friends</h1>
			<p>relevant friends' content<br>
<a href="post.php"><i class="fas fa-user-circle"></i>Create New Group</a><br>









	
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
			<table>
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












	<div class = "content" method = "get" align = "center">
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
			<table>
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








<div class = "content" method = "get" align = "center">
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
			<table>
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







<div class = "content" method = "get" align = "center">
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
			<table>
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
</html>

