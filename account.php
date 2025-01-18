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
<html>
	<head>
		<meta charset="utf-8">
		<title>timetable</title>
		<link href="style.css" rel="stylesheet" type="text/css">
			</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1 size = "39"><b>TABLETIME</b></h1>
				<a href="home.php"><i class="fas fa-user-circle"></i>Home</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Account Details Page</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
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
<tr>
<p>
<a href="usrchange.php"><i class="fas fa-user-circle"></i>Change user/pass/msg</a>

<a href="home.php"><i class="fas fa-user-circle"></i>Edit Tags</a>
<a href="home.php"><i class="fas fa-user-circle"></i>Edit Bio</a>
<a href="home.php"><i class="fas fa-user-circle"></i>Upload Profile Photo</a>
<a href="home.php"><i class="fas fa-user-circle"></i>Export account data</a>
<a href="home.php"><i class="fas fa-user-circle"></i>Import Account Data</a>
<a href="home.php"><i class="fas fa-user-circle"></i>Delete Account</a>
</p>
</tr>				</table>
			</div>
		</div>
	</body>
</html>