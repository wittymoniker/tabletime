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
$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
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
	<form method ="POST">
	<?php
	
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
	<th><br><label name ="range">private/public/global range: </label>"
<input method ="POST" type = "range" id = "view" name = "view" min = "-256" max = "256" default = "<?php echo ($viewselect * 256.0)+0.0;?>">
<?php $viewselect =($_POST['view'] / 256.0)+0.0;
$viewtag="public"; 
if ($viewselect <=-0.5){
$viewtag="private";
}else{
	if ($viewselect >=0.5){
		$viewtag="global";
	}else{
		$viewtag="public";
	}
}
?><br>
<br><label name ="index">post search prompt: </label>"
<input method ="POST" type = "text" name="index" placeholder = "search terms...">"
</th>
<tr>
<br>

<input method ="POST" type = "submit" name= "enter" value = "enter" ></tr>
</form><br>
</table>
</div>
<table>
	<h1>POSTS</h1>
	<?php
$index = $_POST['index'];
/*<th><br><label name ="range">private/public/global range: </label>"
<input method ="POST" type = "range" id = "view" name = "view" min = "-256" max = "256" default = "<?php echo $viewselect * 256.0;?>">
<?php $viewselect =($_POST['view'] / 256.0); 
if (viewselect <=-0.5){
$viewtag="private";
}else{
	if (viewselect >=0.5){
		$viewtag="global";
	}else{
		$viewtag="public";
	}
		//	$sql = 'SELECT * FROM posts WHERE * LIKE $index AND type LIKE $viewtag ';

}*/
if($_POST['enter']){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM posts WHERE * LIKE $index AND type LIKE $viewtag BY ((array_sum(posts(votes))/(count(posts(votes)))';
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
		
		}
		$result = $mysqli->query($sql);
	} else {
		echo "0 posts";
		$sql = 'SELECT * FROM posts WHERE * LIKE $index AND type LIKE $viewtag BY ((array_sum(posts(votes))/(count(posts(votes)))';
	$result = $mysqli->query($sql);
	}
}
?><b>
	<th>name</th>
					<th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>dt</th>
					<th>dt</th>
					<tr>
					<a href ="statsmap.php/?index=<?php echo $feature[0];?>"><td><?php echo $feature[0];?>
	</td></a>
	<a href ="statsmap.php/?index=<?php echo $feature[1];?>"><td><b><?php echo $feature[1];?> <br> </td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[2];?>"><td><?php echo $feature[2];?></td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[3];?>"><td><?php echo $feature[3];?></td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[4];?>"><td><b><?php echo $feature[4];?><br> </td></a>
				</tr>
				<th>tags</th>
				<th>recipients</th>
				<th>comments</th>
				<th>scope</th>
				<th>type</th>
				<tr>
					<a href ="statsmap.php/?index=<?php echo $feature[5];?>"><td><?php echo $feature[5];?></td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[6];?>"><td><b><?php echo $feature[6];?><br> </td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[7];?>"><td><?php echo $feature[7];?></td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[8];?>"><td><?php echo $feature[8];?></td></a>
					<a href ="statsmap.php/?index=<?php echo $feature[9];?>"><td><b><?php echo $feature[9];?></b> <br> </td></a>
				</tr>
</table><br><br><br>
	 

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
        $result = $mysqli->query($sql);

    }
}
if(isset($_POST['enter'])){
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
if(isset($_POST['enter'])){
	$index = $_POST['index'];
	$sql = 'SELECT * FROM accounts  WHERE (* LIKE $index) BY ((array_sum(posts(votes))/(count(posts(votes))) ';
	$result = $mysqli->query($sql);
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $feature VALUES
		(($row["username"]),
		($row["aboutcontent"]),
		($row["tags"]),
		($row["votes"])),
		($row["posts"]);
		(($row["forums"]),
		($row["events"]),
		($row["groups"]),
		($row["comments"])),
		($row["files"]))
		';
	
		$result = $mysqli->query($sql);?>?>
		<th>name</th>
		<th>aboutcontent</th>
		<th>tags</th>
		<th>votes</th>
		<th>posts</th>
		<th>forums</th>
		<tr>
		<a href ="statsmap.php/?index=<?php echo $feature[0];?>"><td><?php echo $feature[0];?>
</td></a>
<a href ="statsmap.php/?index=<?php echo $feature[1];?>"><td><b><?php echo $feature[1];?> <br> </td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[2];?>"><td><?php echo $feature[2];?></td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[3];?>"><td><?php echo $feature[3];?></td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[4];?>"><td><b><?php echo $feature[4];?><br> </td></a>
	</tr>
	<th>events</th>
	<th>groups</th>
	<th>comments</th>
	<th>files</th>
	<tr>
		<a href ="statsmap.php/?index=<?php echo $feature[5];?>"><td><?php echo $feature[5];?></td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[6];?>"><td><b><?php echo $feature[6];?><br> </td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[7];?>"><td><?php echo $feature[7];?></td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[8];?>"><td><?php echo $feature[8];?></td></a>
		<a href ="statsmap.php/?index=<?php echo $feature[9];?>"><td><b><?php echo $feature[9];?></b> <br> </td></a>
	</tr>
</table><br><br><br>


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
<?php
		}
	
	} else {
		echo "0 posts";
	}
}

?>



</body>
</head>
</html>
