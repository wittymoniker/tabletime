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
$con = new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}



$stmt = $con->prepare('SELECT password, email, username, votes FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $username, $votelist);
$stmt->fetch();
$stmt->close();

$authorid = $_SESSION['id'];
$author = $username;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if(isset($_POST["enter"]))
            {
                $test=$_POST["test"];
                $number1=$_POST["no1"];
                $number2=$_POST["no2"];
                $total=$number1+$number2;
                if ($total==$test)
                {






                  $target_dir = "files/" . "file_" . uniqid() . "/";
                  $baseDir = "files/file_" . uniqid() . "/";
                  $target_file = "files/" . "file_" . (string)(uniqid()) . "/" .basename($_FILES["file"]["name"]);
              
                  $uploadOk = 1;
                  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                  if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                  // Check if file already exists
                  if (file_exists($target_file)) {
                      echo "Sorry, file already exists.";
                      $uploadOk = 0;
                  }
              
                  // Check file size (limit to 5MB)
                  if ($_FILES["file"]["size"] > 50000000) {
                      echo "Sorry, your file is too large.";
                      $uploadOk = 0;
                  }
              
                  // Allow certain file formats
           
              
                  // Check if $uploadOk is set to 0 by an error
                  if ($uploadOk == 0) {
                      echo "Sorry, your file was not uploaded.";
                  // If everything is ok, try to upload file
                  } else {
                      if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                          echo "The file ". htmlspecialchars(basename($_FILES["file"]["name"])). " has been uploaded.";
                      } else {
                          echo "Sorry, there was an error uploading your file.";
                      }
                  }
$baseDir = "files/" . "file_" . uniqid() . "/" ;
$file = (string)($baseDir . basename($_FILES["file"]["name"])) ;





        
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
$posttaglets = explode(";", $_POST['tags']);
$postinfo = implode(" : ", [$posttitle, $postcontent, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients]);
$tags = explode(";", $_POST['tags']);
/*<option value ="message">message</option>
<option value ="media">media</option>
<option value ="post">post</option>
<option value ="event">event</option>
<option value ="group">group</option>
<option value ="forum">forum</option>*/
if ($posttype = "message"){
    $i=0;
    foreach  ($posttargets as &$posttarget){
       





      if ($stmt = $con->prepare('SELECT id  FROM accounts WHERE username = $posttarget')) {



        $sql= "INSERT INTO accounts (username, messages, votes, files, friends, votes, friends) 
        VALUES ($posttarget,$postinfo, $postrecipients, $postmedia, $postauthor,$posttags, $postrecipients) BY username";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record INSERT INTOd successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $stmt->close();
    $con->close();
  }
  }
  if ($stmt = $con->prepare('SELECT id  FROM accounts WHERE username = $uname')) {
    $sql= "INSERT INTO accounts(messages)  WHERE accounts(id, username) == ($id, $uname) VALUES ($postcontent)";
    
      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
        $stmt->close();

      }
    $con->close();
    $i=0;
    $stmt->close();
    $con->close();
     
}
if ($posttype = "media"){
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
      if ($stmt = $con->prepare('SELECT id  FROM forums WHERE tag = $posttaglet')) {
        $sql= "INSERT INTO forums (tag, about, groups, posts, events) 
        VALUES ($posttaglet, $posttags, $postrecipients, $postinfo, $postrecipients)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record INSERT INTOd successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $stmt->close();
    $con->close();
  }
  if ($stmt = $con->prepare('SELECT id  FROM posts WHERE title = $posttitle, posts(file) = $postmedia')) {
    $i=0;
    $sql= "INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record INSERT INTOd successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    }
      $stmt->close();
    $con->close();
}
if ($posttype = "comment"){
  $i=0;
  foreach  ($posttargets as &$posttarget){
    if ($stmt = $con->prepare('SELECT id  FROM posts WHERE name LIKE $posttarget OR WHERE id LIKE $posttarget')) {
      $sql= "INSERT INTO posts (comments) WHERE  posts(title, name) ==($posttitle, $posttarget) 
      VALUES ($postinfo)";
   $i=$i+1;
      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
  }
  $stmt->close();
    $con->close();
  if ($stmt = $con->prepare('SELECT id  FROM accounts WHERE (username, id) = ($uname, $id)')) {
  $i=0;
  $sql= "INSERT INTO accounts(posts)  WHERE accounts(id, username) == ($id, $uname) VALUES ($postcontent)";
    
      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
      }
        $stmt->close();
    $con->close();
}
if ($posttype = "post"){
  if ($stmt = $con->prepare('SELECT id  FROM posts WHERE (name, file) = ($uname, $postfile)')) {

    $sql= "INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record INSERT INTOd successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
  }
  $stmt->close();
    $con->close();
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
      if ($stmt = $con->prepare('SELECT id  FROM tags WHERE (value) = ($posttaglet)')) {

        $sql= "INSERT INTO tags (posts, groups, events, forums) WHERE tags(value) == $posttaglet
        VALUES ($postinfo, $postrecipients, $postrecipients, $posttags)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record INSERT INTOd successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
  }
    $i=0;
    $stmt->close();
    $con->close();
} 
    if ($posttype = "profile"){
      if ($stmt = $con->prepare('SELECT id  FROM accounts WHERE (username, id) = ($uname, $id)')) {

      $sql= "INSERT INTO accounts (aboutcontent) 
          VALUE ($postinfo) WHERE  accounts(id, username) == ($id, $uname) ";
    
      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
      }
        $stmt->close();
    $con->close();



      }
  
if ($posttype = "event"){

  if ($stmt = $con->prepare('SELECT id  FROM events WHERE (title) = ($posttitle)')) {

    $sql= "INSERT INTO events (title, type, about, groups, members, posts, tags) 
    VALUES ($posttitle, $posttype, $postcontent, $postrecipients, $postauthor, $postinfo, $posttags)";

    if ($con->query($sql) === TRUE) {
        echo "New record INSERT INTOd successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }

  }
  $stmt->close();
    $con->close();
  if ($stmt = $con->prepare('SELECT id  FROM posts WHERE (name, id, file) = ($uname, $id, $postmedia)')) {

      $sql= "INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
      }
    $stmt->close();
    $con->close();

}
if ($posttype = "group"){
  if ($stmt = $con->prepare('SELECT id  FROM groups WHERE (title) = ($posttitle)')) {


    $sql= "INSERT INTO groups (title, about, members, posts, tags, forums, tags) 
    VALUES ($posttitle, $posttype, $postauthor, $postinfo, $postrecipients, $posttags, $posttags)";

    if ($con->query($sql) === TRUE) {
        echo "New record INSERT INTOd successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    }
    $stmt->close();
    $con->close();
    if ($stmt = $con->prepare('SELECT id  FROM posts WHERE (name, id) = ($uname, $id)')) {

      $sql= "INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

      if ($con->query($sql) === TRUE) {
          echo "New record INSERT INTOd successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
      }
    
    $stmt->close();
    $con->close();
}
if ($posttype = "forum"){
    $i=0;
    foreach ($posttaglets as &$posttaglet){
      if ($stmt = $con->prepare('SELECT id  FROM forums WHERE (tag) = ($posttaglet)')) {
        $sql= "INSERT INTO forums (tag, about, groups, posts, events) 
        VALUES ($posttaglets, $posttags, $postrecipients, $postinfo, $postrecipients)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record INSERT INTOd successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
  }
  $stmt->close();
    $con->close();
    $i=0;

    if ($stmt = $con->prepare('SELECT id  FROM posts WHERE (name, id, file) = ($uname, $id, $postmedia)')) {
    $sql= "INSERT INTO posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record INSERT INTOd successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    
    }

    $stmt->close();
    $con->close();
}
if ($stmt = $con->prepare('SELECT id  FROM accounts WHERE (username, id) = ($uname, $id)')) {

$sql= "INSERT INTO accounts(groups, events, files, forums, friends,  messages, posts, tags, votes) 
WHERE accounts(username, id) == ($postauthor, $authorid) 
VALUES ($postinfo, $postinfo, $postmedia, $posttags, $postrecipients, $postinfo, $postinfo, $postinfo, $postinfo)";  ;

if ($con->query($sql) === TRUE) {
    echo "New record INSERT INTOd successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }


}
$stmt->close();
    $con->close();

echo "Post INSERTd. Wait 10min for next post: ";
echo "<a href='home.php'>Return to home</a>";
header('Location: home.php');
sleep(6000 + 6000 * ((array_sum(explode(";",$votelist))/(count(explode(";",$votelist))))));


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




