<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: home.php');
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






?>


<html class = "tabletime">


<link href="style.php" rel="stylesheet" type="text/css">
<head class = "html">
		<meta charset="utf-8">
		<title>tabletime</title>
<?php include 'createnew.php'; ?>

<body class = "content">  
<nav class="navtop">
            <div class = "tabletime">
                <p><h1><b>TABLETIME</b></h1>
                <a href="home.php"><i class="fas fa-user-circle"></i>Home</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></p>
            </div>
        </nav>
<div>

<p> <?php echo $username; ?>'s post. </p><br>
        
<form method="post" action = "createnew.php" enctype="multipart/form-data" autocomplete="off">					<br>				
<label for="type">
post type:			</label> 
<select name = "type" id = "type">
<option value ="post">message</option>
<option value ="post">media</option>
<option value ="post">post</option>
<option value ="event">event</option>
<option value ="group">group</option>
<option value ="forum">forum</option>
</select>
<label for="scope">
			post scope:			</label>
<select name = "scope" id = "scope">
<option value ="private">private</option>
<option value ="public ">public</option>
<option value ="global">global</option>
</select>
<label for="file">
			file:			</label>
<input type="file" name="file"  placeholder="file directory..." id="file" required> <br>

	<label for="title">
			title:
			</label>     
<input type="text" name="title" placeholder="title (required)" id="title" required> <br>



	<label for="content">
			content:</label>  
            <input type="text" style="height:500px;width:500px;" name="content" placeholder="contents / description (required)" id="content" required><br>

			


	<label for="tags">
			tags:
			</label>
<input type="text" name="tags" placeholder="tags (separate by semicolon;)" id="tags" required>

 <br><label for="recipients">
			send to:
			</label>
<input type="text" name="recipients" placeholder="recipients (separate by semicolon;)" id="recipients" required>

 <br>


<br><br>


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


<br><br><br>


<input type="submit" name= "submit" id = "submit" value="SUBMIT"> <br>


	</form>

</div>

</body>
</head>
</html>




