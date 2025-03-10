<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
require 'pagination.php';
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con =  $mysqli;
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
<a href="messages.php"><i class="tabletime"></i>Messages</a>
<a href="post.php"><i class="tabletime"></i>Posts</a>
<a href="forum.php"><i class="tabletime"></i>Forums</a><br>
<a href="event.php"><i class="tabletime"></i>Events</a>
<a href="tags.php"><i class="tabletime"></i>Tags</a>
<a href="group.php"><i class="tabletime"></i>Groups</a><br>
<a href="statsmap.php"><i class="tabletime"></i>Stats/Map</a>
<a href="profile.php"><i class="tabletime"></i>Profiles</a>
<a href="file.php"><i class="tabletime"></i>Files</a><br>
<a href="create.php"><i class="tabletime"></i><b>Create</b></a></p>
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
			$result = $con->query($sql);
	
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
<input method ="POST" type = "submit" name= "enter" value = "enter" >
</tr>
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
	$con =  $mysqli;
	$result = $con->query($sql);
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
		$con =  $mysqli;
		$result = $con->query($sql);
	} else {
		echo "0 posts";
		$sql = 'SELECT * FROM posts WHERE * LIKE $index AND type LIKE $viewtag BY ((array_sum(posts(votes))/(count(posts(votes)))';
		$con =  $mysqli;
		$result = $con->query($sql);
	}
}	$con =  $mysqli;
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
		$con =  $mysqli;
        $result = $mysqli->query($sql);

    }
}
if(isset($_POST['enter'])){
    $votetarget = $_POST['index'];
    $id = $_SESSION['id'];
    $vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
    if(isset($_POST['perspective'])){
        $sql = "UPDATE accounts ADD $vote TO votes WHERE username == $votetarget";
		$con =  $mysqli;
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
	$con =  $mysqli;
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
<ul class="content">
	<?php if ($page > 1): ?>
	<li class="prev"><a href="pagination.php?page=<?php echo $page-1 ?>">Prev</a></li>
	<?php endif; ?>

	<?php if ($page > 3): ?>
	<li  ><a href="pagination.php?page=1">1</a></li>
	<li  >...</li>
	<?php endif; ?>

	<?php if ($page-2 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
	<?php if ($page-1 > 0): ?><li class="page"><a href="pagination.php?page=<?php echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

	<li class="currentpage"><a href="pagination.php?page=<?php echo $page ?>"><?php echo $page ?></a></li>

	<?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
	<?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="pagination.php?page=<?php echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

	<?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
	<li  >...</li>
	<li  ><a href="pagination.php?page=<?php echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
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

<br>


<?php
	$con =  $mysqli;
$stmt = $con-> prepare('SELECT  aboutcontent FROM accounts WHERE id = ?');
$prof;
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($prof);
$stmt->fetch();
$stmt->close();
$con->close();
$con =  $mysqli;?>

			
<?php
	$con =  $mysqli;
$stmt = $con->prepare('SELECT password, email, username, votes, messages, media, posts, friends, tags, aboutcontent FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($password, $email, $username, $votelist, $messagelist, $medialist, $postslist, $friendlist,$tagslist, $listedabout);
$stmt->fetch();
$stmt->close();
$con->close();
$con =  $mysqli;?>
<a href="account.php"><i class="tabletime"></i>Account</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>


<b> This is a chart of your account record  for posts, media, messages, karma/moksha, and friends standing:</b><br>
<b>-</b>Tallied votes: <?php echo (string)(count(explode(";",$votelist)));?><br>
<b>-</b>Karma/moksha:<?php echo (string) (array_sum(explode(";",$votelist)));?><br>
<b>-</b>Average vote:<?php echo (string) ((array_sum(explode(";",$votelist))/count(explode(";",$votelist))));?><br>
<b>-</b>Messages Count:<?php echo (string) (count(explode(";",$messagelist)));?><br>
<b>-</b>Upload Count:<?php echo (string) (count(explode(";",$medialist)));?><br>
<b>-</b>Friends Count:<?php echo (string) (count(explode(";",$friendslist)));?><br>
<b>-</b>Posts Count:<?php echo (string) (count(explode(";",$postslist)));?><br>
<b>-</b>Tags Count:<?php echo (string) (count(explode(";",$tagslist)));?><br><br><br><br>
<b> This is a chart based on people similar to you:</b><br>
<b>-</b>Their Tallied votes: <?php echo (string)(count(explode(implode(";",$thrvotelist))));?><br>
<b>-</b>Their Karma/moksha:<?php echo (string) (array_sum(explode(implode(";",$thrvotelist))));?><br>
<b>-</b>Their Average vote:<?php echo (string) (array_sum(explode(implode(";",$thrvotelist)))/count(explode(implode(";",$thrvotelist))));?><br>
<b>-</b>Their Messages Count:<?php echo (string) (count(explode(implode(";",$thrmessagelist))));?><br>
<b>-</b>Their Upload Count:<?php echo (string) (count(explode(implode(";",$thrmedialist))));?><br>
<b>-</b>Their Friends Count:<?php echo (string) (count(explode(implode(";",$thrfriendslist))));?><br>
<b>-</b>Their Posts Count:<?php echo (string) (count(explode(implode(";",$thrpostslist))));?><br>
<b>-</b>Their Tags Count:<?php echo (string) (count(explode(implode(";",$thrtagslist))));?><br>

<br><br><br><br><br><br><br><br>
</body>
<table><tr><tc><th><h2>Calendars</h2></th></tc><tc><th><h2>Group</h2></th></tc></tr>
<tr>
	<tc>
	

<?php require 'functions.php';
$index = $_POST['index'];
	$sql = 'SELECT file FROM * WHERE * LIKE $index, $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)  ';
	$con =  $mysqli;
	$result = $con->query($sql);
	$feature;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $feature VALUES (($row["file"])';
		$con =  $mysqli;
		$result = $con->query($sql);
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
if ($stmt = $con->prepare('SELECT * FROM * LIKE $index, $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)  ')) {

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
if ($stmt = $con->prepare('SELECT * FROM * LIKE $index, $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist) ') {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$stmt->close();
	
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
        $result = $con->query($sql);

    }
	
		require 'Calendar.php';
		$searched=  $_POST['index'];
		$sql = 'SELECT * FROM posts  FROM posts WHERE title LIKE $index, $tagslist, $listedabout, (string)($postslists . $uname . $friendslist . $messagelist)  BY DT DESC';
		$result = $con->query($sql);
		$con =  $mysqli;
$events = $result;

$calendar = new Calendar(date('Y-m-d'));
 foreach ($events as $event): ?>
	
	<a href="#">
		
	<?php $calendar->add_event($events['title'][$event['title']], $events['dt'][$event['dt']]);?>

	</a>
	<?php endforeach; ?>

	<?php echo $calendar;?>
	<?php $calendar;?>

<?php
}
?><br>





</p>
		</div>
	</tc>
</tr>
</table>



</body>
</head>
</html>
