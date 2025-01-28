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
		<meta charset="utf-8">
		<title>TABLETIME</title>


<body class = "content">  

<nav class = "navtop">
		<div class = "tabletime">		

<h1><b><a href="home.php">TABLETIME</a></b></h1>
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


<div>

<form action = "colorpick.php" method = "POST">
    <p> Colors:<br><br>
<input type="color" name="style1a" default = "<?php echo $colora; ?>">
<input type="color" name="style2a"default = "<?php echo $colord1; ?>">
<input type="color" name="style3b"default = "<?php echo $colorb2; ?>">
<input type="color" name="style4b"default = "<?php echo $colore2; ?>">
<input type="color" name="style5b"default = "<?php echo $colorb3; ?>">
<input type="color" name="style6b"default = "<?php echo $colore3; ?>">
<br>

<input type="color" name="style1b"default = "<?php echo $colorb; ?>">
<input type="color" name="style2b"default = "<?php echo $colore; ?>">
<input type="color" name="style3c"default = "<?php echo $colorc2; ?>">
<input type="color" name="style4c"default = "<?php echo $colorf2; ?>">
<input type="color" name="style5c"default = "<?php echo $colorc3; ?>">
<input type="color" name="style6c"default = "<?php echo $colorf3; ?>">

<br>

<input type="color" name="style5a"default = "<?php echo $colora3; ?>">
<input type="color" name="style6a"default = "<?php echo $colord3; ?>">
<input type="color" name="style3a"default = "<?php echo $colora2; ?>">
<input type="color" name="style4a"default = "<?php echo $colord2; ?>">
<input type="color" name="style1c"default = "<?php echo $colorc; ?>">
<input type="color" name="style2c"default = "<?php echo $colord; ?>"><br><br><br><br>
<label name="reset">
    reset to default:
</label>
<input type = "checkbox" name = "reset" value = "reset"><br><br><br><br><br><br><br><br>


text: <input type="color" name="styletext"><br>
font size: 
<input type = "range" name = "stylesize" min = "3" max = "36">
<input type = "submit" value = "submit">


</p>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the selected values from the form
    $colorp = ["#ababab;",  "#bcbcbc;",  "#cdcdcd;",  "#dcdcdc;",  "#ededed;",   "#dfdfdf;",    "#0a0a0a;", "#1b1b1b;", "#2c2c2c;","#3d3d3d;","#4e4e4e;", "#5f5f5f;",    "#a3a3a3;",  "#b2b2b2;",  "#c1c1c1;","#d1d1d1;", "#e2e2e2;", "#f3f3f3;","#000000;", "14;"];

    $id = $_SESSION['id'];
    $sql = "UPDATE accounts SET colors = ? WHERE id = accounts($id)";  

    $color[20] = $colorp;
    $color[0] = (string)$_POST['style1a'];
    $color[1] = (string)$_POST['style1b'];
    $color[2] = (string)$_POST['style1c'];
    $color[3] = (string)$_POST['style2a'];
    $color[4] = (string)$_POST['style2b'];
    $color[5] = (string)$_POST['style2c'];
    $color[6] = (string)$_POST['style3a'];
    $color[7] = (string)$_POST['style3b'];
    $color[8] = (string)$_POST['style3c'];
    $color[9] = (string)$_POST['style4a'];
    $color[10] =(string) $_POST['style4b'];
    $color[11] =(string) $_POST['style4c'];
    $color[12] = (string)$_POST['style5a'];
    $color[13] =(string) $_POST['style5b'];
    $color[14] = (string)$_POST['style5c'];
    $color[15] = (string)$_POST['style6a'];
    $color[16] = (string)$_POST['style6b'];
    $color[17] = (string)$_POST['style6c'];
    $color[18] = (string)$_POST['styletext'];
    $color[19] = (string)$_POST['stylesize'];
    


if(isset($_POST['submit'])){
    $id = $_SESSION['id'];
    if(isset($_POST['reset'])){
        $color = $colorp;
        $sql = "UPDATE accounts SET account(colors) TO $send WHERE accounts(id) = accounts($id)";
        $result = $mysqli->query($sql);

        header('Location: index.html');
        
    }
    }

    $send = [implode(";", $color)];
    $sql = "UPDATE accounts SET colors TO $send WHERE id = accounts($id)";


}
   

?>

</form>
</div>
<div>
<p>
<h1>test:</h1>

<iframe src="home.php" title="TEST VIEW"></iframe>
</p>
<div>

</body>
</head>
</html>
