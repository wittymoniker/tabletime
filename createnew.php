<?php
$min  = 1;
$max  = 500;
$num1 = rand( $min, $max );
$num2 = rand( $min, $max );

    
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
// Try and connect using the info above.
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}



$stmt = $con->prepare('SELECT password, email, username FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $username);
$stmt->fetch();
$stmt->close();

$authorid = $_SESSION['id'];
$author = $username;

if(isset($_POST["submit"]))
            {
                $test=$_POST["test"];
                $number1=$_POST["no1"];
                $number2=$_POST["no2"];
                $total=$number1+$number2;
                if ($total==$test)
                {




	

$baseDir = 'files/posts/';

$filename = htmlspecialchars(date('Y-m-d H:i:s')) . '/';
$directory =  htmlspecialchars($_FILES['file']['name']);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {    // Upload destination directory
    $upload_destination = $baseDir . $filename;
    // Iterate all the files and move the temporary file to the new directory
        if ($_FILES['file']['size'][$i] > 20000000) {
            exit('Please upload a file less than 20MB!');
        }
        // Add your validation here
  
        mkdir($baseDir, 0777, true);

        $file = $upload_destination . $directory;
        
        // Move temporary files to new specified location
        move_uploaded_file($_FILES['file']['tmp_name'][$i], $file);
    }
    // Output response



				$postcontent = $_POST['content'];
				$posttitle = $_POST['title'];
				$posttags = $_POST['tags'];
				$postmedia = $file;
				$postauthor = $author;
				$posttime = date('Y-m-d H:i:s');
				$postscope = $_POST['scope'];
                $posttype = $_POST['type'];
                $postrecipients = $_POST['recipients'];


$posttargets = explode(";", $_POST['recipients']);
$postinfo = implode(" ; ", [$postcontent, $posttitle, $uploadFile, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients]);
$tags = explode(";", $_POST['tags']);



if ($stmt = $con->prepare('INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)')) {
    $stmt->bind_param('sssssssss', $postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients);
    $stmt->execute();
 
} else {
    echo "Could not prepare statement";
}




$con->close();



header('Location: home.php');

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


