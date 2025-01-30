

<!DOCTYPE html>
<html class = "tabletime">
	<head class = "html">


<link href="style.php" rel="stylesheet" type="text/css">
<h1><b><a href="home.php">TABLETIME</a></b></h1>
<a href = "register.php"><h2>Register</h2></a>
		<title>tabletime</title>

	
	<body class = "content">
<div>
<table><th>	
			<h1>tabletime</h1>
<?php include 'authenticate.php';

$color;

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
?>


<p>enter your name and code<br> and solve the puzzle to access your timetable</p>
			<form method="POST" action = "authenticate.php" autocomplete="off">
				<label for="username">
				</label>
				<input type="text" name="username" placeholder="name" id="username" required>
				<label for="password">
				</label><br>
				<input type="password" name="password" placeholder="code" id="password" required>
									
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

				<input type="submit" name= "submit" id = "submit" value="LOGIN">

	</form><br>
		
</th>
</table>
</div>
	
<br>
<body><p>Welcome to TABLETIME, a social network featuring:<br><br>

-events<br>
-tags<br>
-tag-based object/location/event/post/group/status/ relevancy and conditional scoring system<br>
-user status tags, group status tags, tag-based content management<br>
-user tags with ratings and statistics<br>
-groups<br>
-posts/media<br>
-global forum<br>
-light scripting of posts for interactable menus and public <br>counters and embed containers<br>
-text/video chat<br>
-paid, taxed user-to-user ads<br>
-advanced user-end-user analytics<br>
-statistically derived global forum topics  and content delivery (based on tags and content-based tags).<br><br><br>
<b>Join today and get involved in the fun!</b><br>
<a href = "register.php">Register</a></p></body><br>






</body>


</head>


</html>
