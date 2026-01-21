
 
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
$min  = 1;
$max  = 500;
$num1 = rand( $min, $max );
$num2 = rand( $min, $max );
$DATABASE_HOST = 'sql103.infinityfree.com';
$DATABASE_USER = 'if0_38191057';
$DATABASE_PASS = 'Greenapples55';
$DATABASE_NAME = 'if0_38191057_tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con =  $mysqli;if ( mysqli_connect_errno() ) {
		exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}




$id = $_SESSION['id'];
$color;
$stmt = $con->prepare('SELECT colors FROM accounts WHERE id =?');

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($color);
 $stmt->fetch();
 $stmt->free_result();  // Free them
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
<meta name="viewport" content="width=device-width">
<meta charset="utf-8">
<link href="style.php" rel="stylesheet" type="text/css">
<head class = "html">
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

<a href = "logout.php"><h2>LOGOUT</h2></a>
		<title>tabletime change user</title>

	
<div>
<table><th>	
			<h1>tabletime</h1>
<?php require 'updateusr.php';
$id = $_SESSION['id'];
$color;
$stmt = $con->prepare('SELECT colors FROM accounts WHERE id =?');

$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($color);
 $stmt->fetch();
 $stmt->free_result();  // Free them
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
} ?>


<p>enter your name and code,
<form method="post" action = "updateusr.php" autocomplete="off">
				<label for="username">
				</label>
				<input type="text" name="username" placeholder="name" id="username" required>
				<label for="password">
				</label><br>
				<input type="password" name="password" placeholder="code" id="password" required>
                 new credentials<br> 
				<label for="username">
				</label>
				<input type="text" name="username2" placeholder="new name" id="username2" required>
				<label for="password">
				</label><br>
				<input type="password" name="password2" placeholder="new code" id="password2" required>
                <input type="email" name="email2" placeholder="new msg" id="email2" required><br>
				<label for="email">
									</label>
                
                and solve the puzzle to reset your timetable</p>
			
									
<div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label for="quiz" 
                                   class="col-sm-3 col-form-label">
                                <?php echo $num1 . '+' . $num2; ?>
                            </label>
                            <div>
                                <input type="hidden" 
                                       name="no1" 
                                       value="<?php echo $num1 ?>">
                                <input type="hidden"
                                       name="no2" 
                                       value="<?php echo $num2 ?>">
                                <input type="text" 
                                       name="test"
                                       class="form-control quiz-control" 
                                       autocomplete="off"
                                       id="test" required>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

				
			<input method ="POST" type = "submit" name= "enter" value = "enter" >

	</form><br>
		
</th>
</table>
</div>
	







</body>


</head>


</html>
