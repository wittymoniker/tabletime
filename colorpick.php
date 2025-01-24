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
<input type="color" name="style1a">
<input type="color" name="style2a">
<input type="color" name="style3b">
<input type="color" name="style4b">
<input type="color" name="style5b">
<input type="color" name="style6b">
<br>

<input type="color" name="style1b">
<input type="color" name="style2b">
<input type="color" name="style3c">
<input type="color" name="style4c">
<input type="color" name="style5c">
<input type="color" name="style6c">

<br>

<input type="color" name="style5a">
<input type="color" name="style6a">
<input type="color" name="style3a">
<input type="color" name="style4a">
<input type="color" name="style1c">
<input type="color" name="style2c"><br><br><br><br>
<label name="reset">
    reset to default:
</label>
<input type = "radio" name = "reset" value = "reset"><br><br><br><br><br><br><br><br>


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
    $color[0] = (string)$_POST['style1a'].";";
    $color[1] = (string)$_POST['style1b'].";";
    $color[2] = (string)$_POST['style1c'].";";
    $color[3] = (string)$_POST['style2a'].";";
    $color[4] = (string)$_POST['style2b'].";";
    $color[5] = (string)$_POST['style2c'].";";
    $color[6] = (string)$_POST['style3a'].";";
    $color[7] = (string)$_POST['style3b'].";";
    $color[8] = (string)$_POST['style3c'].";";
    $color[9] = (string)$_POST['style4a'].";";
    $color[10] =(string) $_POST['style4b'].";";
    $color[11] =(string) $_POST['style4c'].";";
    $color[12] = (string)$_POST['style5a'].";";
    $color[13] =(string) $_POST['style5b'].";";
    $color[14] = (string)$_POST['style5c'].";";
    $color[15] = (string)$_POST['style6a'].";";
    $color[16] = (string)$_POST['style6b'].";";
    $color[17] = (string)$_POST['style6c'].";";
    $color[18] = (string)$_POST['styletext'].";";
    $color[19] = (string)$_POST['stylesize'].";";
    


if(isset($_POST['submit'])){
    $id = $_SESSION['id'];
    if(isset($_POST['reset'])){
        $color = $colorp;
        $sql = "UPDATE accounts SET colors = $send WHERE id = accounts($id)";
        $result = $mysqli->query($sql);

        header('Location: index.html');
        
    }
    }

    $send = implode(';', $color);
    $sql = "UPDATE accounts SET colors = $send WHERE id = accounts($id)";


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
