<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
//error_reporting(E_ERROR | E_PARSE);
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
?>





<?php



$id = $_SESSION['id'];
$color;
$stmt = $con->prepare('SELECT colors FROM accounts WHERE id =?');

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($color);
$stmt->fetch();
$stmt->close();
if ($color != ""){
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



<html >
<meta name="viewport" content="width=device-width">
<meta charset="utf-8">
<link href="style.php" rel="stylesheet" type="text/css">

<head class = "content">
<body class = "tabletime">


		<br><br><title>TABLETIME</title>



<nav class = "navtop">
		<div class = "tabletime">		

		
		<br><img src="tabletime logo.png" alt="tabletime logo" width="50" height="50"><br>
		<h1><br><br>
		<b><a href="home.php">TABLETIME</a></b></h1>
		<br><br>
<a href="messages.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a>
<a href="forum.php"><i class="fas fa-user-circle"></i>Forums</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a><br><br><br>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a><br><br><br><br>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a>
<a href="create.php"><i class="fas fa-user-circle"></i>Create</a></div></nav>



<?php
$stmt = $con-> prepare('SELECT  aboutcontent FROM accounts WHERE id = ?');
$prof;
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($prof);
$stmt->fetch();
$stmt->close();?>

			
<?php
$stmt = $con->prepare('SELECT password, email, username, votes FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $username, $votelist);
$stmt->fetch();
$stmt->close();
?>
<h1><?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES);?>'s timetable </h1><br>...on timedelay shift (between posts) <?php echo (string)(6.0 - 6.0*(-1.0+(1.0+count(explode(";",$votelist))/(1.0+array_sum(explode(";",$votelist))))));?> minutes.<br> <a href="account.php"><i class="fas fa-user-circle"></i>Account </a>
				 <br> DEFAULT DELAY: 10min<br><br>



<div>
	<p>

		
			<h1>Feed</h1>
		relevant sorted' post content<br>
<br>

<?php









$uname = $_SESSION['name'];
$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;

$sql = 'SELECT posts FROM accounts LIKE  $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM accounts || posts LIKE $friendslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
else {
    echo "0 posts";
}


?><br>
			
					

			<table >
				<tr>
					<th>about</th>
					<th>tags</th>
					<th>media</th>
					<th>posts</th>
					<th>username</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
				<?php if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]),
	($row["tags"]),
	($row["media"]),
	($row["posts"])),
	($row["username"])';
	$result = $mysqli->query($sql);
	
    }
if ($result->num_rows > 0) {?>
	<td><?php echo $row['friends'||'recipients']; ?></td>
	<td><b><?php echo $row['tags']; ?></b> </td>
	<td><b><?php echo $row['media' && ['content' || 'aboutcontent']]; ?></b> </td>
	<td><?php echo $row['name'||'username']; ?></td>
	<a href = "profile.php?index='<?php echo $row["username"]?>'"><td><?php echo $row['username']; }}?></td></a><?php
	?>
		<?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
	
				</tr>
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
			<?php endif; ?><?php
	$result = $mysqli->query($sql);
} }?>
<br>

			</table>
			

			
			








<br>

		
			<h1>Friends</h1>
			relevant friend activity<br>







	
<?php











$uname = $_SESSION['name'];
$sql = 'SELECT aboutcontent FROM accounts WHERE username = accounts($uname) OR tags  LIKE accounts($tags)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()&& $row<=9) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["info"]))';
	$result = $mysqli->query($sql);
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM accounts WHERE username LIKE $friendslist';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()&& $row<=9) {
		$sql = 'INSERT INTO $postslist VALUES
		(($row[":"]))';
		$result = $mysqli->query($sql);
		}

} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM posts IF * IN $postslist ORDER BY dt DESC')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}

$uname = $_SESSION['name'];
$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname)';
$result = $mysqli->query($sql);
$friendslist;
$postslist;

$sql = 'SELECT posts FROM accounts LIKE  $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM accounts || posts LIKE $friendslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
else {
    echo "0 posts";
}

?>

			
					

			<table>
				<tr>
					<th>about</th>
					<th>tags</th>
					<th>media</th>
					<th>posts</th>
					<th>username</th>
									</tr>

				<tr>
<?php if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]),
	($row["tags"]),
	($row["media"]),
	($row["posts"])),
	($row["username"])';
	$result = $mysqli->query($sql);?>
	
	<?php	
}
if ($result->num_rows > 0) {
				while ($row = $result->fetch_assoc()){ ?>
			<tr>
			<a href = "people.php?index='<?php echo $row["`" . $friendslist . $postslist . "`"]?>'"><td><?php echo $row[':']; }}?></td></a>
	<td><?php echo $row['friends'||'recipients']; ?></td>
	<td><b><?php echo $row['tags']; ?></b> </td>
	<td><b><?php echo $row['media' && ['content' || 'aboutcontent']]; ?></b> </td>
	<td><?php echo $row['name'||'username']; ?></td>
	<a href = "profile.php?index='<?php echo $row["username"]?>'"><td><?php echo $row['username']; ?></td></a><?php?> 
			</tr>
			<?php
if (ceil($total_pages / $num_results_on_page) > 0): 
	?>
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
<?php
} ?>
				

			
<br>
</table>



	<br>
			<h1>Events</h1>
			relevant events<br>











	
<?php




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
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);

} else {
    echo "0 friends";
}
$sql = 'SELECT * FROM events WHERE members LIKE $friendslist ORDER BY dt DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["title"]),
	($row["about"]),
	($row["posts"]),
	($row["members"]))';
	$result = $mysqli->query($sql);
	

	
}

 if ($result->num_rows > 0) {?>
	<table >
				<tr>
					<th>title</th>
					<th>about</th>
					<th>posts</th>
					<th>members</th>
									</tr>
<?php
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
				<a href = "group.php?index='<?php echo $row["`" . $friendslist . $postslist . "`"]?>'"><td><?php echo $row['title']; ?></td></a>
					<td><?php echo $row['about']; ?></td>
					<td><?php echo $row['posts']; ?></td>
					<td><?php echo $row['members']; }}?></td>
				</tr>
				
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

<br>
</table>
<?php
} 


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM groups LIKE $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
?>


<br>



		
			<h1>Global</h1>
		relevant global events, forums posts<br>









	
<?php



$uname = $_SESSION['name'];
$sql = 'SELECT tags FROM accounts WHERE username = accounts($id) && username = accounts($id) OR contains(accounts(friends), $uname)';
$result = $mysqli->query($sql);
$tagslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $tagslist VALUES
	(($row["tags"])),
	(($row["forums"])),
	(($row["friends"])),
	(($row["events"])),
	(($row["groups"])),
	(($row["posts"])),
	(($row["aboutcontent"]))';
	$result = $mysqli->query($sql);
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);

} else {
    echo "0 friends";
}
$sql = 'SELECT * IN forums LIKE ORDER BY tags DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["groups"]),
	($row["events"]),
	($row["content"]),
	($row["tag"]))';
	$result = $mysqli->query($sql);
	?><br>
			
			<table >
				<tr>
					<th>name</th>
					<th>title</th>
					<th>post</th>
					<th>tags</th>
									</tr>

			<?php	
    }
	 if ($result->num_rows > 0) {
					while ($row = $result->fetch_assoc()){ ?>
				<tr>
				<a href = "forum.php?index='<?php echo $row["`" . $friendslist . $postslist . "`"]?>'"><td><?php echo $row['name']; ?></td></a>
					<td><b><?php echo $row['title']; ?></b> </td>
					<td><b><?php echo $row['content']; ?></b> </td>
							<td><?php echo $row['dt']; }}?></td>
				</tr>
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
		<?php endif; ?>


		<br></table><br><?php
} 


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM posts LIKE $tagslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	
}
 
			?>

			
			
			






		
</p>
		</div>











		<a href="account.php"><i class="fas fa-user-circle"></i>Account</a>
		<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
<br>
<br>
<br>
<?php include 'pagination.php';?>
<br>
<br>
<br><br><br>



</body>
</head>


</html>
