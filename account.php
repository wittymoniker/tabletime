<?php
// We need to use sessions, so you should always start sessions using the below password.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
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
$stmt = $con->prepare('SELECT password, email FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html class = "tabletime">
		<link href="style.php" rel="stylesheet" type="text/css">
	<head class = "html">
		<meta charset="utf-8">
		<title>tabletime</title>



		
	<body class="content">



	<nav class = "navtop">
		<div class = "tabletime">		

<h1><b>TABLETIME</b></h1>
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


			
		<nav class="navtop">
			<div class = "tabletime">
				<p><h1><b>TABLETIME</b></h1>
				<a href="home.php"><i class="fas fa-user-circle"></i>Home</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></p>
			</div>
		</nav>
<br>
		<div class = "html">
			<h2>Account Details Page</h1>
								<table><th><h2 align = "center">Your account details are below:</h1></th>
<tr>
						<td>pic:</td>
						<td><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></td>
					</tr>

					<tr>
						<td>name:</td>
						<td><?=htmlspecialchars($_SESSION['name'], ENT_QUOTES)?></td>
					</tr>
					<tr>
						<td>code hash(debug):</td>
						<td><?=htmlspecialchars($password, ENT_QUOTES)?></td>
					</tr>
					<tr>
						<td>msg:</td>
						<td><?=htmlspecialchars($email, ENT_QUOTES)?></td>
					</tr>
			</table>

<p>
<a href="bio.php"><i class="fas fa-user-circle"></i>Edit Bio</a>
<a href="pfp.php"><i class="fas fa-user-circle"></i>Upload Profile Photo</a>
<a href="colorpick.php"><i class="fas fa-user-circle"></i>Edit Color Formatting</a><br>
<a href="export.php"><i class="fas fa-user-circle"></i>Export account data</a>
<a href="usrchange.php"><i class="fas fa-user-circle"></i>Change user/pass/msg</a>
<a href="import.php"><i class="fas fa-user-circle"></i>Import Account Data</a><br>
<a href="support.php"><i class="fas fa-user-circle"></i>Delete Account</a>
</p>
</tr>	
		</div>
	</body>
</head>
</html>