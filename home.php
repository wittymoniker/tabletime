<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
error_reporting(E_ERROR | E_PARSE);

$DATABASE_HOST = 'sql103.infinityfree.com';
$DATABASE_USER = 'if0_38191057';
$DATABASE_PASS = 'Greenapples55';
$DATABASE_NAME = 'if0_38191057_tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con =  $mysqli;
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
 $stmt->free_result();  // Free them
$stmt->close();
 
if ($color != ''){
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
}	$con =  $mysqli;?>




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
<a href="account.php"><i class="tabletime"></i>Account</a>




<?php
	$con =  $mysqli;
if($stmt = $con-> prepare('SELECT  aboutcontent FROM accounts WHERE id = ?')){
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$stmt->bind_result($about);
	 
	$con =  $mysqli;
}
else{
	$about = 'tabletime';
}
$prof;

			

	$con =  $mysqli;
if($stmt = $con->prepare('SELECT password, email, username, votes, messages, media, posts, friends, tags, aboutcontent FROM accounts WHERE id = ?')){
	$stmt->bind_param('i', $_SESSION['id']);
	$stmt->execute();

	$stmt->bind_result($password, $email, $username, $votelist, $messagelist, $medialist, $postslist, $friendlist,$tagslist, $listedabout);
 
	$con =  $mysqli;
}
else{
	$prof = 'tabletime';
}



$con =  $mysqli;
if($stmt = $con->prepare('SELECT  username, votes,  media, posts,tags, friends aboutcontent FROM accounts BY username, votes,  media, posts,tags, friends, aboutcontent LIKE ?,?,?')){



	$stmt->bind_param('sss',$tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist) );
	$stmt->execute();

	$stmt->bind_result($thrusername, $thrvotelist,  $thrmedialist, $thrpostslist,$thrtagslist, $thrfriendlist,  $thrlistedabout);
	 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
	 
	 
	$con =  $mysqli;
	
 
}
?></head>
<h1><?php echo htmlspecialchars($_SESSION['name'], ENT_QUOTES);?>'s timetable <br>.. on post timedelay  <?php echo (string)((10.0 - 5.0*(0+(count(explode(";",$votelist)))*(abs(array_sum(explode(";",$votelist)))))));?> minutes.<br> 
<br> Default delay is 10min.<br><br></h1>
	


<div class = "content">	

		
			<h1>Feed</h1>
		relevant sorted' post content<br>
<br>

<?php








$con =  $mysqli;
$uname = $_SESSION['name'];
$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname)';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$friendslist;
$postslist;
$con =  $mysqli;
$sql = 'SELECT posts FROM accounts LIKE  $friendslist ORDER BY dt DESC';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

$con =  $mysqli;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;
$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM accounts || posts LIKE $friendslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	 
	
}
else {
    
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
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
	
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
			<ul class="content">
				<?php if ($page > 1): ?>
				<li ><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
				<?php endif; ?>

				<?php if ($page > 3): ?>
				<li ><a href="pagination.php?page=1">1</a></li>
				<li >...</li>
				<?php endif; ?>

				<?php if ($page-2 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
				<?php if ($page-1 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

				<li ><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

				<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
				<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
				<li >...</li>
				<li ><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
				<?php endif; ?>

				<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
				<li ><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
				<?php endif; ?>
			</ul>
			<?php endif; ?><?php
} }?>
<br>

			</table>
			

			
		</div>
        <div class = "content">		








<br>

		
			<h1>Friends</h1>
			relevant friend activity<br>







	
<?php










$con =  $mysqli;
$uname = $_SESSION['name'];
$sql = 'SELECT aboutcontent FROM accounts WHERE username = accounts($uname) OR tags  LIKE accounts($tags)';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$friendslist;
$postslist;
$con =  $mysqli;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()&& $row<=9) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["info"]))';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

} else {
    
}
$con =  $mysqli;
$sql = 'SELECT posts FROM accounts WHERE username LIKE $friendslist';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()&& $row<=9) {
		$sql = 'INSERT INTO $postslist VALUES
		(($row[":"]))';
		$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
		}

} else {

}

$con =  $mysqli;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;
$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM posts IF * IN $postslist ORDER BY dt DESC')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	 
	$con =  $mysqli;
	
}
$con =  $mysqli;
$uname = $_SESSION['name'];
$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname)';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$friendslist;
$postslist;
$con =  $mysqli;
$sql = 'SELECT posts FROM accounts LIKE  $friendslist ORDER BY dt DESC';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

$con =  $mysqli;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;
$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM accounts || posts LIKE $friendslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	 
}
else {
    
}
$con =  $mysqli;
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
		$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 ?>
	
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
<ul class="content">
	<?php if ($page > 1): ?>
	<li ><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
	<?php endif; ?>

	<?php if ($page > 3): ?>
	<li ><a href="pagination.php?page=1">1</a></li>
	<li >...</li>
	<?php endif; ?>

	<?php if ($page-2 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
	<?php if ($page-1 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

	<li><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

	<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
	<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
	<li >...</li>
	<li ><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
	<?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
	<li ><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
	<?php endif; ?>
</ul>
<?php endif; ?>
<?php
} ?>
				

			
<br>
</table>
</div>
<div class = "content">	

	<br>
			<h1>Events</h1>
			relevant events<br>











	
<?php



$con =  $mysqli;
$uname = $_SESSION['name'];
$sql = 'SELECT friends FROM accounts WHERE username = accounts($uname)';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$con =  $mysqli;
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["friends"]))';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

} else {
    
}
$con =  $mysqli;
$sql = 'SELECT * FROM events WHERE members LIKE $friendslist ORDER BY dt DESC';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$con =  $mysqli;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["title"]),
	($row["about"]),
	($row["posts"]),
	($row["members"]))';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
	

	
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

<ul class="content">
	<?php if ($page > 1): ?>
	<li ><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
	<?php endif; ?>

	<?php if ($page > 3): ?>
	<li ><a href="pagination.php?page=1">1</a></li>
	<li >...</li>
	<?php endif; ?>

	<?php if ($page-2 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
	<?php if ($page-1 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

	<li class="currentpage"><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

	<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
	<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
	<li >...</li>
	<li ><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
	<?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
	<li ><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
	<?php endif; ?>
</ul>
<?php endif; ?>

<br>
</table>
</div>

<?php
} 


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $con->prepare('SELECT * FROM groups LIKE $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	 
	
}
?>


<br>

<div class = "content">	

		
			<h1>Global</h1>
		relevant global events, forums posts<br>









	
<?php


$con =  $mysqli;
$uname = $_SESSION['name'];
$sql = 'SELECT tags FROM accounts WHERE username = accounts($id) && username = accounts($id) OR contains(accounts(friends), $uname)';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$con =  $mysqli;
$tagslist;
$postslist;
$con =  $mysqli;
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
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
    }
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

} else {
    
}
$con =  $mysqli;
$sql = 'SELECT * IN forums LIKE ORDER BY tags DESC';
$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$con =  $mysqli;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["groups"]),
	($row["events"]),
	($row["content"]),
	($row["tag"]))';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
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
		<ul class="content">
			<?php if ($page > 1): ?>
			<li ><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
			<?php endif; ?>

			<?php if ($page > 3): ?>
			<li ><a href="pagination.php?page=1">1</a></li>
			<li >...</li>
			<?php endif; ?>

			<?php if ($page-2 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
			<?php if ($page-1 > 0): ?><li ><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

			<li ><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

			<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
			<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li ><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

			<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
			<li >...</li>
			<li ><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
			<?php endif; ?>

			<?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
			<li ><a href="pagination.php?page=<?php echo $page+1 ?>">Next</a></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>


		<br></table><br><?php
} 


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM posts LIKE $tagslist || $postslist')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	 
}
 
			?>

			
			
			






		

		</div>












		<br>
<div>




<b> This is a chart of your account record  for posts, media, messages, karma/moksha, and friends standing:</b><br>
<b>-</b>Tallied votes: <?php echo (string)(count(explode(";",$votelist)));?><br>
<b>-</b>Karma/moksha:<?php echo (string) (array_sum(explode(";",$votelist)));?><br>
<b>-</b>Average vote:<?php echo (string) (((0+array_sum(explode(";",$votelist+0)))/(1+count(explode(";",$votelist)))));?><br>
<b>-</b>Messages Count:<?php echo (string) (count(explode(";",$messagelist)));?><br>
<b>-</b>Upload Count:<?php echo (string) (count(explode(";",$medialist)));?><br>
<b>-</b>Friends Count:<?php echo (string) (count(explode(";",$friendslist)));?><br>
<b>-</b>Posts Count:<?php echo (string) (count(explode(";",$postslist)));?><br>
<b>-</b>Tags Count:<?php echo (string) (count(explode(";",$tagslist)));?><br><br><br><br>
<b> This is a chart based on people similar to you:</b><br>
<b>-</b>Their Tallied votes: <?php echo (string)(count(explode(implode(";",$thrvotelist))));?><br>
<b>-</b>Their Karma/moksha:<?php echo (string) (array_sum(explode(implode(";",$thrvotelist))));?><br>
<b>-</b>Their Average vote:<?php echo (string) ((0+array_sum(explode(implode(";",$thrvotelist))))/(1+count(explode(implode(";",$thrvotelist)))));?><br>
<b>-</b>Their Messages Count:<?php echo (string) (count(explode(implode(";",$thrmessagelist))));?><br>
<b>-</b>Their Upload Count:<?php echo (string) (count(explode(implode(";",$thrmedialist))));?><br>
<b>-</b>Their Friends Count:<?php echo (string) (count(explode(implode(";",$thrfriendslist))));?><br>
<b>-</b>Their Posts Count:<?php echo (string) (count(explode(implode(";",$thrpostslist))));?><br>
<b>-</b>Their Tags Count:<?php echo (string) (count(explode(implode(";",$thrtagslist))));?><br>

<br><br><br><br><br><br><br><br>
</div>
<table><tr><tc><th><h2>Calendars</h2></th></tc><tc><th><h2>Group</h2></th></tc></tr>
<tr>
	<tc>
	

<?php require 'functions.php';
$index = $_POST['index'];
	$sql = 'SELECT file FROM * WHERE * LIKE $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)';
	$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $feature VALUES (($row["file"])';
		$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
		$con =  $mysqli;
		}?>
		<?=template_header('Gallery')?>

<div>

	<div >
		<?php foreach ($feature as $image): ?>
		<?php if (file_exists($image['filepath'])): ?>
		<a href="#">
			<img src="<?=$image['filepath']?>" alt="<?=$image['description']?>" data-id="<?=$image['id']?>" data-title="<?=$image['title']?>" width="300" height="200">
			<span><?=$image['description']?></span>
		</a>
		<?php endif; ?>
		<?php endforeach; ?>
	</div>
</div>

<div >
<script>
// Container we'll use to output the image
let image_popup = document.querySelector('.image-popup');
// Iterate the images and apply the onclick event to each individual image
document.querySelectorAll('.images a').forEach(img_link => {
	img_link.onclick = e => {
		e.preventDefault();
		let img_meta = img_link.querySelector('img');
		let img = new Image();
		img.onload = () => {
			// Create the pop out image
			image_popup.innerHTML = `
				<div>
					<h3>${img_meta.dataset.title}</h3>
					<p>${img_meta.alt}</p>
					<img src="${img.src}" width="${img.width}" height="${img.height}">
					<a href="delete.php?id=${img_meta.dataset.id}"  title="Delete Image"><i ></i></a>
				</div>
			`
			image_popup.style.display = 'flex';
		}
		img.src = img_meta.src;
	}
});
// Hide the image popup container, but only if the user clicks outside the image
image_popup.onclick = e => {
	if (e.target.className == 'image-popup') {
		image_popup.style.display = "none";
	}
}
</script>
</div>
<?=template_footer()?>
<?php	}?>


<?php 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;
$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM * LIKE $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)  ')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$con =  $mysqli;
	
}?><br>
</p>
		</div>

	</tc>
	<tc>
	<?php $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;
$con =  $mysqli;
if ($stmt = $con->prepare('SELECT * FROM * LIKE $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)  ')) {

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
if(isset($_POST['enter'])){
    $votetarget = $_POST['index'];
    $id = $_SESSION['id'];
    $vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
    if(isset($_POST['perspective'])){
        $sql = "UPDATE posts ADD $vote TO votes WHERE id == $feature[10]";
		$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 

    }
	
		require 'Calendar.php';
		$searched=  $_POST['index'];
		$sql = 'SELECT * FROM posts  FROM posts WHERE title LIKE $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist) BY DT DESC';
		$con =  $mysqli;
		$stmt = $con->prepare($sql);
$stmt->execute();
$stmt->bind_result($result);
 $stmt->fetch();
 $stmt->free_result();  // Free them
$stmt->close();
 
$events = $result;

$calendar = new Calendar(date('Y-m-d'));
 foreach ($events as $event): ?>
	
	<a href="#">
		
	<?php $calendar->add_event($events['title'][$event['title']], $events['dt'][$event['dt']]);?>

	</a>
	<?php endforeach; ?>

	<?php echo $calendar;?>
	<?php $calendar;?>

<br>





</p>
		</div>
	</tc>
</tr>
</table>
<?php
}require 'pagination.php';
?>
</body>
<a href="account.php"><i class="tabletime"></i>Account</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
</html>
