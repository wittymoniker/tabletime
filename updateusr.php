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








if(isset($_POST["enter"]))
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
if ($stmt = $con->prepare('SELECT username, password, email, id FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
if ($stmt->num_rows > 0) {
	$stmt->bind_result($name, $pass, $email, $id);
	$stmt->fetch();
		if (password_verify($_POST['password'], $pass)) {
		session_regenerate_id();
		$_SESSION['loggedin'] = TRUE;
		$_SESSION['name'] = $_POST['username'];
		$_SESSION['id'] = $id;
		//header('Location: account.php');


        }
    if (!isset($_POST['username2'], $_POST['password2'], $_POST['email2'])) {
            exit('Please complete the registration form!');
    }
    if (empty($_POST['username2']) || empty($_POST['password2']) || empty($_POST['email2'])) {
            exit('Please complete the registration form');
    }
    
        if ($stmt = $con->prepare('SELECT username, password, email FROM accounts WHERE id = ?')) {
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $stmt->store_result();
            if ($stmt->num_rows > 0) {
                    
                    if ($stmt = $con->prepare('REPLACE INTO accounts (username, password, email) VALUES (?, ?, ?) WHERE (username, password, email) = ($name, $pass, $email))')) {
                        $password = password_hash($_POST['password2'], PASSWORD_DEFAULT);
                    $stmt->bind_param('sss', $_POST['username2'], $password, $_POST['email2']);
            $stmt->execute();
            $stmt->close();
            header('Location: account.php');
        }  else {
		echo 'Incorrect username and/or password!';
	}
}

exit(); 
} 
}
}
}
}    
?>
