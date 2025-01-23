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




	

$directory = 'users/' . $authorid . '/';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $uploadDir = $directory;
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Check if the upload directory exists, if not, create it
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo "File successfully uploaded.";
    } else {
        echo "File upload failed.";
    }
} else {
    echo "No file uploaded.";
}                        




				$postcontent = $_POST['content'];
				$posttitle = $_POST['title'];
				$posttags = $_POST['tags'];
				$postmedia = 'users/' . $authorid . '/' . basename($_FILES['file']['name']);
				$postauthor = $authorid;
				$posttime = now();
				$postscope = $_POST['scope'];


$posttargets = explode(";", $_POST['tr']);
$postinfo = implode(' , ', $posttitle, $postauthor, $posttime,$postcontent, $postmedia, $posttags, ' ; ');
$tags = explode(";", $_POST['tags']);

if ($stmt = $con->prepare('INSERT INTO posts (content, title,  file, tags, name, dt, scope) VALUES (?, ?, ?, ?, ?, ?)')) {
    $stmt->bind_param('sssssss', $postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime);
    $stmt->execute();
 

$con->close();


header('Location: login.php');

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
        }

?>


