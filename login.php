

<!DOCTYPE html>
<html>
	<head>
<a href = "register.php"><h2>Register</h2></a>

<link href="style.css" rel="stylesheet" type="text/css">
		<title>tabletime</title>

	</head>
	<body>
<div class="tabletime" align = "center">
<table style="background-color:#EFEFEF;" align = 
"center">
<th>	
			<h1 size = "37">tabletime</h1>
<?php include 'authenticate.php'; ?>


<p>enter your name and code<br> to access your timetable</p>
			<form method="post" action = "authenticate.php" autocomplete="off">
				<label for="username">
				</label>
				<input type="text" name="username" placeholder="name" id="username" required><br>
				<label for="password">
				</label>
				<input type="password" name="password" placeholder="code" id="password" required> <br>
									</label>
<div class="col-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row">
                            <label for="quiz" 
                                   class="col-sm-3 col-form-label">
                                <?php echo $num1 . '+' . $num2; ?>
                            </label>
                            <div class="tabletime">
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

	</form>
		
</th>
</table>
</div>
	</body>

<body><p style = "text-align: center;">Welcome to TABLETIME, a social network featuring:<br><br>

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












</html>
