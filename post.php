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
$con =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
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
<head class = "html">
<meta name="viewport" content="width=device-width">
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
	<h1>POST INSPECTOR</h1>
	<th><br>
<br>
<br><label name ="index">post search prompt: </label>"
<input method ="POST" type = "text" name="index" placeholder = "search terms...">"
</th>
<tr>
<br>
<input method ="POST" type = "submit" name= "enter" value = "enter" >
<th><br><label name ="range">private/public/global range: </label>"
<?php 
$viewselect = (float)($_POST['view'] / 256.0)+0.0; 
$viewtag="public";
if ($viewselect <=-0.5){
$viewtag="private";
}else{
	if ($viewselect >=0.5){
		$viewtag="global";
	}else{
		$viewtag="public";
	}
		//	$sql = 'SELECT * FROM posts WHERE * LIKE $index AND type LIKE $viewtag ';

}?>
</tr>
</form><br>
</table>
</div>
<form method ="POST">
	<label name ="rate"> <br>leave rating (-/+) karma/moksha: </label>"
	<input method = "POST" type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
	</form><?php
	if(isset($_POST['enter'])){
		$uname = $_POST['index'];
		$id = $_SESSION['id'];
		$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
		if(isset($_POST['perspective'])){
			$sql = "UPDATE accounts ADD $vote TO votes WHERE username = $uname";
			$result = $mysqli->query($sql);
	
		}
	}
	
	if(isset($_POST['enter'])){
		$votetarget = $_POST['index'];
		$id = $_SESSION['id'];
		$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
		if(isset($_POST['perspective'])){
			$sql = "UPDATE posts ADD $vote TO votes WHERE id == $feature[10]";
			$result = $mysqli->query($sql);
	
		}
	}
	?>
	<br><br><br>
	<div>
<table><th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>dt</th>
					<th>dt</th>
	<?php
$index = $_POST['index'];
if($viewtag!="public"){
if($viewtag ="private"){
	if($_POST['enter']){

		$index = $_POST['index'];
		$sql = 'SELECT * FROM posts WHERE (* LIKE $index) WHERE type = "private" BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
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
			$result = $mysqli->query($sql);?>
			<th>name</th>
						
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
					</tr><?php
			}
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
			</body><?php
		} else {
			echo "0 posts";
		}
	}
	?>
		
		 
	

	
	
	<?php
	
	
	
	$uname = $_SESSION['name'];
	/////////
	/////////
	///////////
	
	$sql = 'SELECT * FROM posts WHERE accounts(posts(name)) LIKE accounts($uname) WHERE type = "private" BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	/////////////
	///////////
	///////////
	//////////
	
	$result = $mysqli->query($sql);
	$friendslist;
	$postslist;
	if ($mysqli->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$uname = $_SESSION['name'];
	$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname) BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	$result = $mysqli->query($sql);
	$friendslist;
	$postslist;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $friendslist VALUES
		(($row["friends"]),
		($row["tags"]),
		($row["media"]),
		($row["posts"])),
		($row["username"])';
		
		$result = $mysqli->query($sql);?>
		
	?>
	<meta charset="utf-8">
			
<body>
	<table class = "list">
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
			<td><?php echo $row['friends'||'recipients']; ?></td>
			<td><b><?php echo $row['tags']; ?></b> </td>
			<td><b><?php echo $row['media' && ['content' || 'aboutcontent']]; ?></b> </td>
			<td><?php echo $row['name'||'username']; ?></td>
			<a href = "profile.php?index='<?php echo $row["username"]?>'"><td><?php echo $row['username']; }}?></td></a>
		</tr><?php
		}?>
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
	<?php
		$result = $mysqli->query($sql);
	} else {
		echo "0 friends";
	}
	$sql = 'SELECT posts FROM accounts LIKE  $friendslist BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	$result = $mysqli->query($sql);
	
	
	
	
	$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
	$num_results_on_page = 16 ;
	
	if ($stmt = $mysqli->prepare('SELECT * FROM  posts LIKE $friendslist || $postslist WHERE type = "private" BY ((array_sum(posts(votes))/(count(posts(votes))) DESC')) {
	
		$calc_page = ($page - 1) * $num_results_on_page;
		$stmt->bind_param('ii', $calc_page, $num_results_on_page);
		$stmt->execute(); 
		
	}
	else {
		echo "0 posts";
	}?>
	
	
	
				
	
	
	
	
			<br><br>
	<br>
	
	
	
	
	
	</p>
			</div>
			<?php
}
if($viewtag= "global"){
	if($_POST['enter']){

$index = $_POST['index'];
$sql = 'SELECT * FROM posts WHERE (* LIKE $index)  WHERE type = "global" BY ((array_sum(posts(votes))/(count(posts(votes)))  DESC';
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
	$result = $mysqli->query($sql);?>
	<th>name</th>
				
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
			<?php
	}
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


 

<form method ="POST">
<label name ="rate"> <br>leave rating (-/+) karma/moksha: </label>"
<input method = "POST" type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
</form><?php
if(isset($_POST['enter'])){
$uname = $_POST['index'];
$id = $_SESSION['id'];
$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
if(isset($_POST['perspective'])){
	$sql = "UPDATE accounts ADD $vote TO votes WHERE username = $uname";
	$result = $mysqli->query($sql);

}
}

if(isset($_POST['enter'])){
$votetarget = $_POST['index'];
$id = $_SESSION['id'];
$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
if(isset($_POST['perspective'])){
	$sql = "UPDATE posts ADD $vote TO votes WHERE id == $feature[10]";
	$result = $mysqli->query($sql);

}
}
?>
<br><br><br>
<div>


<?php


$uname = $_SESSION['name'];
/////////
/////////
///////////

$sql = 'SELECT * FROM posts WHERE accounts(posts(name)) LIKE accounts($uname) WHERE type = "global" BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
/////////////
///////////
///////////
//////////

$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($mysqli->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname) BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
while($row = $result->fetch_assoc()) {
$sql = 'INSERT INTO $friendslist VALUES
(($row["friends"]),
($row["tags"]),
($row["media"]),
($row["posts"])),
($row["username"])';
$result = $mysqli->query($sql);
}
$sql = 'INSERT INTO $friendslist VALUES
($row[$_POST["index"]])';
$result = $mysqli->query($sql);
} else {
echo "0 friends";
}
$sql = 'SELECT posts FROM accounts LIKE  $friendslist BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
$result = $mysqli->query($sql);




$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM  posts LIKE $friendslist || $postslist WHERE type = "global" BY ((array_sum(posts(votes))/(count(posts(votes))) DESC')) {

$calc_page = ($page - 1) * $num_results_on_page;
$stmt->bind_param('ii', $calc_page, $num_results_on_page);
$stmt->execute(); 

}
else {
echo "0 posts";
}


?>
		<meta charset="utf-8">
				
	<body>
		<table class = "list">
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
				<td><?php echo $row['friends'||'recipients']; ?></td>
				<td><b><?php echo $row['tags']; ?></b> </td>
				<td><b><?php echo $row['media' && ['content' || 'aboutcontent']]; ?></b> </td>
				<td><?php echo $row['name'||'username']; ?></td>
				<a href = "profile.php?index='<?php echo $row["username"]?>'"><td><?php echo $row['username']; }}?></td></a>
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





	<br><br>
<br>





</p>
	</div>
	<?php		
}
}else{

	if($_POST['enter']){

		$index = $_POST['index'];
		$sql = 'SELECT * FROM posts WHERE (* LIKE $index)  BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
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
			$result = $mysqli->query($sql);?>
			<th>name</th>
						
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
					</tr><?php
			}
		
		} else {
			echo "0 posts";
		}
	}
	?>
	
	<form method ="POST">
	<label name ="rate"> <br>leave rating (-/+) karma/moksha: </label>"
	<input method = "POST" type = "range" id = "perspective" name = "rate" min = "-256" max = "256">
	</form><?php
	if(isset($_POST['enter'])){
		$uname = $_POST['index'];
		$id = $_SESSION['id'];
		$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
		if(isset($_POST['perspective'])){
			$sql = "UPDATE accounts ADD $vote TO votes WHERE username = $uname";
			$result = $mysqli->query($sql);
	
		}
	}
	
	if(isset($_POST['enter'])){
		$votetarget = $_POST['index'];
		$id = $_SESSION['id'];
		$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
		if(isset($_POST['perspective'])){
			$sql = "UPDATE posts ADD $vote TO votes WHERE id == $feature[10]";
			$result = $mysqli->query($sql);
	
		}
	}
	?>
	<br><br><br>
	<div>
	
	
	<?php
	
	

	$uname = $_SESSION['name'];
	/////////
	/////////
	///////////
	
	$sql = 'SELECT * FROM posts WHERE accounts(posts(name)) LIKE accounts($uname) BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	/////////////
	///////////
	///////////
	//////////
	
	$result = $mysqli->query($sql);
	$friendslist;
	$postslist;
	if ($mysqli->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$uname = $_SESSION['name'];
	$sql = 'SELECT friends, tags, media, posts, username FROM accounts WHERE username = accounts($uname) BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	$result = $mysqli->query($sql);
	$friendslist;
	$postslist;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $friendslist VALUES
		(($row["friends"]),
		($row["tags"]),
		($row["media"]),
		($row["posts"])),
		($row["username"])';
		$result = $mysqli->query($sql);
		}
		$sql = 'INSERT INTO $friendslist VALUES
		($row[$_POST["index"]])';
		$result = $mysqli->query($sql);
	} else {
		echo "0 friends";
	}
	$sql = 'SELECT posts FROM accounts LIKE  $friendslist BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	$result = $mysqli->query($sql);
	
	
	
	
	$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
	$num_results_on_page = 16 ;
	
	if ($stmt = $mysqli->prepare('SELECT * FROM  posts LIKE $friendslist || $postslist BY ((array_sum(posts(votes))/(count(posts(votes))) DESC')) {
	
		$calc_page = ($page - 1) * $num_results_on_page;
		$stmt->bind_param('ii', $calc_page, $num_results_on_page);
		$stmt->execute(); 
		
	}
	else {
		echo "0 posts";
	}
	
	
	?>
				<meta charset="utf-8">
						
			<body>
				<table class = "list">
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
						<td><?php echo $row['friends'||'recipients']; ?></td>
						<td><b><?php echo $row['tags']; ?></b> </td>
						<td><b><?php echo $row['media' && ['content' || 'aboutcontent']]; ?></b> </td>
						<td><?php echo $row['name'||'username']; ?></td>
						<a href = "profile.php?index='<?php echo $row["username"]?>'"><td><?php echo $row['username']; }}?></td></a>
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
				<?php endif; }}}?>
			</body>
	
	
	
	
	
			<br><br>
	<br>
	
	
	
	
	
	</p>
			</div>
	
	
			<?php include 'pagination.php';?>




</body>
</head>
</html>
