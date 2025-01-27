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




	

$baseDir = 'files/' . 'file_' . uniqid() . '/';
$file = $upload_destination . $_FILES['file']['name'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {    // Upload destination directory
    $upload_destination = $baseDir;
    // Iterate all the files and move the temporary file to the new directory
        
  
        mkdir($upload_destination, 0777, true);

        
        
        // Move temporary files to new specified location
        move_uploaded_file($_FILES['file']['tmp_name'], $file);
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
       
        $sql= "CREATE accounts (username, messages, votes, files, friends, votes, friends) 
        VALUES ($posttarget,$postinfo, $postrecipients, $postmedia, $postauthor,$posttags, $postrecipients)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $sql= "CREATE accounts(messages)  WHERE accounts(id, username) == ($id, $uname) VALUES ($postcontent)";
    
      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
        $stmt->close();
    $con->close();
    $i=0;
    $stmt->close();
    $con->close();
}
if ($posttype = "media"){
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
       
        $sql= "CREATE forums (tag, about, groups, posts, events) 
        VALUES ($posttaglet, $posttags, $postrecipients, $postinfo, $postrecipients)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $i=0;
    $sql= "CREATE posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
      $stmt->close();
    $con->close();
}
if ($posttype = "comment"){
  $i=0;
  foreach  ($posttargets as &$posttarget){
     
      $sql= "CREATE posts (comments) WHERE  posts(title, author) ==($posttitle, $posttarget) 
      VALUES ($postinfo)";
   $i=$i+1;
      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
  }
  $i=0;
  $sql= "CREATE accounts(posts)  WHERE accounts(id, username) == ($id, $uname) VALUES ($postcontent)";
    
      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
        $stmt->close();
    $con->close();
}
if ($posttype = "post"){
    $sql= "CREATE posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
       
        $sql= "CREATE tags (posts, groups, events, forums) WHERE tags(value) == $posttaglet
        VALUES ($postinfo, $postrecipients, $postrecipients, $posttags)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $i=0;
    $stmt->close();
    $con->close();
} 
    if ($posttype = "profile"){
      
      $sql= "CREATE accounts (aboutcontent) 
          VALUE ($postinfo) WHERE  accounts(id, username) == ($id, $uname) ";
    
      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
        $stmt->close();
    $con->close();



      }
  
if ($posttype = "event"){
    $sql= "CREATE events (title, type, about, groups, members, posts, tags) 
    VALUES ($posttitle, $posttype, $postcontent, $postrecipients, $postauthor, $postinfo, $posttags)";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }

    
      $sql= "CREATE posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
    
    $stmt->close();
    $con->close();

}
if ($posttype = "group"){
    $sql= "CREATE groups (title, about, members, posts, tags, forums, tags) 
    VALUES ($posttitle, $posttype, $postauthor, $postinfo, $postrecipients, $posttags, $posttags)";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    
      $sql= "CREATE posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

      if ($con->query($sql) === TRUE) {
          echo "New record created successfully";
        } else {
          echo "Error: " . $sql . "<br>" . $con->error;
        }
    
    
    $stmt->close();
    $con->close();
}
if ($posttype = "forum"){
    $i=0;
    foreach ($posttaglets as &$posttaglet){
       
        $sql= "CREATE forums (tag, about, groups, posts, events) 
        VALUES ($posttaglets, $posttags, $postrecipients, $postinfo, $postrecipients)";
     $i=$i+1;
        if ($con->query($sql) === TRUE) {
            echo "New record created successfully";
          } else {
            echo "Error: " . $sql . "<br>" . $con->error;
          }
    }
    $i=0;
    $sql= "CREATE posts (content, title,  file, tags, name, dt, scope, type, recipients) VALUES ($postcontent, $posttitle, $postmedia, $posttags, $postauthor, $posttime, $postscope, $posttype, $postrecipients)";

    if ($con->query($sql) === TRUE) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . $con->error;
      }
    
    
    $stmt->close();
    $con->close();
}

$sql= "CREATE accounts(groups, events, files, forums, friends,  messages, posts, tags, votes) 
WHERE accounts(username, id) == ($postauthor, $authorid) 
VALUES ($postinfo, $postinfo, $postmedia, $posttags, $postrecipients, $postinfo, $postinfo, $postinfo, $postinfo)";  ;

if ($con->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $con->error;
  }




$stmt->close();
$con->close();

echo "Post created. Wait 10min for next post: ";
echo "<a href='home.php'>Return to home</a>";
sleep(6000);
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


