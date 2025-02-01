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

		<meta charset="utf-8">
		<title>TABLETIME</title>
<body class = "content">  
<head class = "html">
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
	<h1>FORUM INSPECTOR</h1>
	<th><br>
<br>
<form method = "POST">
<br><label name ="index">post search prompt: </label>"
<input method ="POST" type = "text" name="index" placeholder = "search terms...">"
</th>
<tr>
<br>

<input method ="POST" type = "submit" name= "enter" value = "enter"><th><br><label name ="range">private/public/global range: </label>"
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
<input method ="POST" type = "range" id = "view" name = "view" min = "-256" max = "256" default = "<?php echo $viewselect * 256.0+0;?>">

</tr>
<br>
</table>
</div>
<table><th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>dt</th>
					<th>dt</th>
	<?php



$index = $_POST['index'];
if($viewtag!="public"){
if($viewtag ="private"){
	$index = $_POST['index'];
	if($_POST['enter']){
		$index = $_POST['index'];
		$sql = 'SELECT * FROM posts WHERE (* LIKE $index)  WHERE type LIKE $viewtag BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
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
			$table = $result;?>
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
	</table><br><br><br>	

		<div><?php
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
	<div>
	
	<?php

	
	
	if ($mysqli->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$uname = $_SESSION['name'];
	/////////
	/////////
	///////////
	
	$sql = 'SELECT tags FROM posts WHERE value LIKE $index WHERE type LIKE $viewtag BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	/////////////
	///////////
	///////////
	//////////
	
	$result = $mysqli->query($sql);
	$friendslist;
	$postslist;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $friendslist VALUES
		(($row["tags"]))';
		$table = $result;
		$sql = 'INSERT INTO $postslist VALUES
		($row[$_POST["index"]])';
		$result = $mysqli->query($sql);
		$table = $result;
		}
		
		
	
	} else {
		echo "0 friends";
	}
	$sql = 'SELECT posts FROM forums WHERE forums(posts) LIKE $friendslist  ORDER BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
	$result = $mysqli->query($sql);
	$table = $result;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
		$sql = 'INSERT INTO $postslist VALUES
		(($row["name"]),
		($row["title"]),
		($row["content"]),
		($row["tags"])),
		($row["file"])';
		$result = $mysqli->query($sql);
		$table = $result;
		}
		$sql = 'INSERT INTO $postsslist VALUES
		($row[$_POST["index"]])';
		$result = $mysqli->query($sql);
		
	
	
	} else {
		echo "0 posts";
	}
	
	
	$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
	$num_results_on_page = 16 ;
	
	if ($stmt = $mysqli->prepare('SELECT * FROM  posts, forums LIKE $postslist WHERE  ((array_sum(posts(votes))/(count(posts(votes)))>=0  ORDER BY dt DESC ')) {
	
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
						<th>topic</th>
						<th>content</th>
						<th>file</th>
						<th>tags</th>
										</tr>
	
					<?php if ($result->num_rows > 0) {
						if(isset($_POST['enter'])){
							$votetarget = $_POST['index'];
							$id = $_SESSION['id'];
							$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
							$adjustedvote = -1.0+(float)$vote;
						}
						
							$votetarget = $_POST['index'];
							$id = $_SESSION['id'];
							$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
							if(isset($_POST['perspective'])){
								$sql = "UPDATE accounts ADD $vote TO votes WHERE username == $votetarget";
								$result = $mysqli->query($sql);
						
							}
						}
						?><br><br><br>
						<div>
						
						<?php
						$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
	
						
						
						if ($mysqli->connect_error) {
							die("Connection failed: " . $conn->connect_error);
						}
						$uname = $_SESSION['name'];
						/////////
						/////////
						///////////
						
						$sql = 'SELECT tags FROM posts WHERE value LIKE $index BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
						/////////////
						///////////
						///////////
						//////////
						
						$result = $mysqli->query($sql);
						$friendslist;
						$postslist;
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
							$sql = 'INSERT INTO $friendslist VALUES
							(($row["tags"]))';
							$result = $mysqli->query($sql);
							$sql = 'INSERT INTO $friendslist VALUES
							($row[$_POST["index"]])';
							$result = $mysqli->query($sql);
							}
							
							
						
						} else {
							echo "0 friends";
						}
						$sql = 'SELECT posts FROM forums WHERE forums(posts) LIKE $friendslist ORDER BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
						$result = $mysqli->query($sql);
						
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
							$sql = 'INSERT INTO $postslist VALUES
							(($row["name"]),
							($row["title"]),
							($row["content"]),
							($row["tags"])),
							($row["file"]),
							($row[$_POST["index"]]);';
							$result = $mysqli->query($sql);
							while ($row = $result->fetch_assoc()){ ?>
								<tr>
								<a href = "profile.php?index='<?php echo $row["name"]?>'"><td><?php echo $row['name']; ?></td>
								<td><b><?php echo $row['title']; ?></b> <br> </td></a>
								<a href = "posts.php?index='<?php echo $row["content"]?>'"><td><?php echo $row['content']; ?></td></a>
									<td><?php echo $row['file']; ?></td>
									<td><b><?php echo $row['tags']; echo $row['index']; }?></b> <br> </td>
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
						
						?>
	
				</table>
				
	
				
				
	
			<div>
			
	
	
			<?php
}
if($viewtag= "global"){
	$index = $_POST['index'];
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
		($row["type"])),
		($row["id"]))';
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
</table><br><br><br>	<?php
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
		</body>

		<div><?php
	} else {
		echo "0 posts";
	}
}
}
	}
?>

	 




<?php



if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$uname = $_SESSION['name'];
/////////
/////////
///////////

$sql = 'SELECT tags FROM posts WHERE value LIKE $index BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
/////////////
///////////
///////////
//////////

$result = $mysqli->query($sql);
$friendslist;
$postslist;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $friendslist VALUES
	(($row["tags"]))';
	$result = $mysqli->query($sql);
	$sql = 'INSERT INTO $friendslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);
    }
	
	

} else {
    echo "0 friends";
}
$sql = 'SELECT posts FROM forums WHERE forums(posts) LIKE $friendslist ORDER BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
	$sql = 'INSERT INTO $postslist VALUES
	(($row["name"]),
	($row["title"]),
	($row["content"]),
	($row["tags"])),
	($row["file"])';
	$result = $mysqli->query($sql);
    }
	$sql = 'INSERT INTO $postsslist VALUES
	($row[$_POST["index"]])';
	$result = $mysqli->query($sql);


} else {
    echo "0 posts";
}


$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$num_results_on_page = 16 ;

if ($stmt = $mysqli->prepare('SELECT * FROM  posts, forums LIKE $postslist ORDER BY dt DESC BY ((array_sum(posts(votes))/(count(posts(votes))) DESC')) {

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
					<th>topic</th>
					<th>content</th>
					<th>file</th>
					<th>tags</th>
									</tr>

				<?php if ($result->num_rows > 0) {
					if(isset($_POST['enter'])){
						$votetarget = $_POST['index'];
						$id = $_SESSION['id'];
						$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
						$adjustedvote = -1.0+(float)$vote;
					}
					
						$votetarget = $_POST['index'];
						$id = $_SESSION['id'];
						$vote = ( (string)(float)((256+$_POST['perspective'])/255) . ";" );
						if(isset($_POST['perspective'])){
							$sql = "UPDATE accounts ADD $vote TO votes WHERE username == $votetarget";
							$result = $mysqli->query($sql);
					
						}
					}
					?><br><br><br>
					<div>
					
					<?php
		

					
					
					if ($mysqli->connect_error) {
						die("Connection failed: " . $conn->connect_error);
					}
					$uname = $_SESSION['name'];
					/////////
					/////////
					///////////
					
					$sql = 'SELECT tags FROM posts WHERE value LIKE $index BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
					/////////////
					///////////
					///////////
					//////////
					
					$result = $mysqli->query($sql);
					$friendslist;
					$postslist;
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
						$sql = 'INSERT INTO $friendslist VALUES
						(($row["tags"]))';
						$result = $mysqli->query($sql);
						$sql = 'INSERT INTO $friendslist VALUES
						($row[$_POST["index"]])';
						$result = $mysqli->query($sql);
						}
						
						
					
					} else {
						echo "0 friends";
					}
					$sql = 'SELECT posts FROM forums WHERE forums(posts) LIKE $friendslist ORDER BY ((array_sum(posts(votes))/(count(posts(votes))) DESC';
					$result = $mysqli->query($sql);
					
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
						$sql = 'INSERT INTO $postslist VALUES
						(($row["name"]),
						($row["title"]),
						($row["content"]),
						($row["tags"])),
						($row["file"]),
						($row[$_POST["index"]]);';
						$result = $mysqli->query($sql);
						while ($row = $result->fetch_assoc()){ ?>
							<tr>
							<a href = "profile.php?index='<?php echo $row["name"]?>'"><td><?php echo $row['name']; ?></td>
							<td><b><?php echo $row['title']; ?></b> <br> </td></a>
							<a href = "posts.php?index='<?php echo $row["content"]?>'"><td><?php echo $row['content']; ?></td></a>
								<td><?php echo $row['file']; ?></td>
								<td><b><?php echo $row['tags']; echo $row['index']; }?></b> <br> </td>
							</tr>	

		<div><?php
							
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
					
					?>

			</table>
			

			


	<?php		

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
		</body>

		<div><?php
			}
		
		} else {
			echo "0 posts";
		}
	}


	?>




<form>
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




</p>
		</div>
<?php include 'pagination.php';?>


</head>
</body>

</html>
