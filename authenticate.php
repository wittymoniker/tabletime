<?php
session_start();
$min  = 1;
$max  = 500;
$num1 = rand( $min, $max );
$num2 = rand( $min, $max );
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
		exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}







if(isset($_POST["submit"]))
            {
                $test=$_POST["test"];
                $number1=$_POST["no1"];
                $number2=$_POST["no2"];
                $total=$number1+$number2;
                if ($total==$test)
                {








if ( !isset($_POST['username'], $_POST['password']) ) {
		exit('Please fill both the username and password fields!');
}     				
if ($stmt = $con->prepare('SELECT username, password, email, id, friends, posts, groups, events, colors, votes, forums, tags, aboutcontent, files  FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($name, $pass, $email, $id, $friends, $posts, $groups, $events, $colors, $votes, $forums, $tags, $aboutcontent, $files );
	$stmt->fetch();
		if (password_verify($_POST['password'], $pass)) {
        
		session_regenerate_id();
        $loggedin=true;
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		header('Location: home.php');
	} else {
		echo 'Incorrect username and/or password!';
	}
} else {
	echo 'Incorrect username and/or password!';
}

	$stmt->close();
}












		
exit();
               }
                else {
                    echo "<p>
                                <font color=red 
                                    font face='arial' 
                                    size='5pt'>
                                Invalid captcha entered !
                                </font>
                            </p>";
                     }
            }
           
?>
