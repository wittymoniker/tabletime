<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();


    
// Change this to your connection info.
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
// Try and connect using the info above.
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con =  $mysqli;
if ($mysqli->connect_errno){
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}


$con =  $mysqli;
$authorid = $_SESSION['id'];
$author = $_SESSION['name'];
$uname = $author;
if($stmt = $con->prepare('SELECT password, email, username, votes, id FROM accounts WHERE id = ?')){
  $stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $username, $votelist, $id);
$stmt->fetch();
$stmt->close();
$con =  $mysqli;
$authorid = $_SESSION['id'];
$author = $username;
$uname = $username;
$_SESSION['$uname'] = $uname;
$id = $_SESSION['id'];
}

$id = $_SESSION['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $test=$_POST["test"];
                $number1=$_SESSION['num1'];
                $number2=$_SESSION['num2'];;
                $total=$number1+$number2;
                if ($total==$test)
                {






                  $uniqueFolder = "file_" . uniqid();
$target_dir = "files/" . $uniqueFolder;
$target_folder = $target_dir . "/";
$target_file = $target_folder . basename($_FILES['file']['name']);
                  
                  $uploadOk = 1;
                  $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                  
                  if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                
      
                  if (file_exists($target_file)) {
                      echo "Sorry, file already exists.";
                      $uploadOk = 0;
                  }
              
           
                  if ($_FILES["file"]["size"] > 5000000) {
                      echo "Sorry, your file is too large.";
                      $uploadOk = 0;
                  }
              
                  // Allow certain file formats
                  if($uploadOk){
                    if(move_uploaded_file($_FILES['file']['tmp_name'], $target_file)){
                      $uploadOk=2;
                    }else {
                      echo "Sorry, your file was not uploaded.";
                    }
                  }
                  
                  
$baseDir = $target_dir;
$file = (string)($target_file);






$postfile = $file;
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
$con =  $mysqli;
if ($posttype == "message"){
    $i=0;
    foreach  ($posttargets as &$posttarget){
$msgvote = +0;
        if($stmt= $con->prepare( $con->prepare("INSERT INTO accounts(username, messages, votes,  friends)  VALUES 
        (?,?,?,?)"))){
        
	$stmt->bind_param('ssss', $posttarget, $postinfo, $msgvote, $postauthor);
  if($stmt->execute()){
    $stmt->close(); 
   }else{
    $stmt->close();
    }     
          $i=$i+1;
    

        }
    }
          if($stmt= $con->prepare(("INSERT INTO accounts(id, messages)  VALUES (?,?)" ))){
    $stmt->bind_param('is', $id,$postcontent);
    if($stmt->execute()){
      $stmt->close(); 
     }else{
      $stmt->close();
      }     
    
    }
 
   $i=0;
    echo '.';
      
    
}
$con =  $mysqli;
if ($posttype == "media"){
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
      $con =  $mysqli;
        if($stmt= $con->prepare( "INSERT INTO forums (tag, about, groups, posts, events) 
        VALUES (?,?,?,?,?) ")){
         
        $stmt->bind_param('sssss',$posttaglet, $postcontent, $postrecipients, $postinfo, $postrecipients);
        if($stmt->execute()){
          $stmt->close(); 
         }else{
    $stmt->close();
    }     
        }
        
        $con =  $mysqli;
    if($stmt= $con->prepare( "INSERT INTO events (posts,title) 
        VALUES (?,?)")){
         
        $stmt->bind_param('ss',$postinfo, $posttaglet);
        if($stmt->execute()){
          $stmt->close(); 
         }else{
    $stmt->close();
    }     
 
    
    
   
        }
        $con =  $mysqli;
        if($stmt= $con->prepare( "INSERT INTO groups (posts,title) 
        VALUES (?,?)")){
         
         $stmt->bind_param('ss',$postinfo, $posttaglet);
         if($stmt->execute()){
          $stmt->close(); 
         }else{
    $stmt->close();
    }     
 
    
    
   
        }
        $i=$i+1;
}
$i=0;
}
$con =  $mysqli;
if ($posttype == "comment"){
  $i=0;
  foreach  ($posttargets as &$posttarget){
    $con =  $mysqli;
    if($stmt= $con->prepare( "INSERT INTO posts (comments, title, name) ) 
      VALUES (?,?,?)")){

 $stmt->bind_param('sss',  $postinfo,$posttitle, $posttarget);
 if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
   $i=$i+1;

      }
    }
    $stmt->close(); 
  $i=0;
  $con =  $mysqli;
  if($stmt= $con->prepare( "INSERT INTO accounts(posts,id)  VALUES (?,?)")){

 $stmt->bind_param('si',  $postcontent,$id);
 if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
      
  }
  $stmt->close(); 
     echo '.';
      

}
$con =  $mysqli;
if ($posttype == "post"){
  $con =  $mysqli;
    if ($stmt= $con->prepare( "INSERT INTO posts (content, title,   tags, dt, scope, type, recipients, name, file) VALUES (?,?,?,?,?,?,?,?,?) ")){
    $stmt->bind_param('sssssssss', $postcontent ,  $posttitle ,  $posttags ,  $posttime ,  $postscope ,  $posttype ,  $postrecipients ,  $uname ,  $postmedia  );
    if($stmt->execute()){
      $stmt->close(); 
     }else{
      $stmt->close();
      }      
      
    }
    $i=0;
    foreach  ($posttaglets as &$posttaglet){
      $con =  $mysqli;
        if($stmt= $con->prepare( "INSERT INTO tags (posts, groups, events, forums, value)


        VALUES (?,?,?,?,?) ")){
  $stmt->bind_param('sssss', $postinfo ,  $posttaglets ,  $posttaglets ,  $posttaglets ,  $posttaglet );
  if($stmt->execute()){
    $stmt->close(); 
   }else{
    $stmt->close();
    }      
     $i=$i+1;
    
    }
    
    
     echo '.';
      
} $i=0;
    }
$con =  $mysqli;       
    if ($posttype == "profile"){
      $con =  $mysqli;   
        if($stmt= $con->prepare( "INSERT INTO accounts (aboutcontent, id, username) 
          VALUES (?,?,?)")){
            

 $stmt->bind_param('sis',   $postinfo , $id ,  $uname );
 if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
          }
     echo '.';
      



      }
      $con =  $mysqli;
if ($posttype == "event"){

  $con =  $mysqli;
    if($stmt= $con->prepare( "INSERT INTO events (title, type, about, groups,  posts, tags, title, members, file) 
    VALUES (?,?,?,?,?,?,?,?,?) ")){
$stmt->bind_param('sssssssss',  $posttitle ,  $posttype , $postinfo ,  $postrecipients ,  $postcontent ,  $posttags ,  $posttitle ,  $uname ,  $postmedia  );
if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      

 $con =  $mysqli;
if($stmt= $con->prepare( "INSERT INTO posts (content, title,   tags, dt, scope, type, recipients, name, file) VALUES (?,?,?,?,?,?,?,?,?) ")){
$stmt->bind_param('sssssssss', $postcontent ,  $posttitle ,  $posttags ,  $posttime ,  $postscope ,  $posttype ,  $postrecipients ,  $uname ,  $posttitle ,  $postmedia  );
if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
   
     echo '.';
      

}
    }
}
$con =  $mysqli;
if ($posttype == "group"){

  $con =  $mysqli;
    if($stmt= $con->prepare( "INSERT INTO groups (title, type, about, events,  posts, tags, title, members, file) 
  VALUES (?,?,?,?,?,?,?,?,?) ")){
$stmt->bind_param('sssssssss',  $posttitle ,  $posttype , $postinfo ,  $postrecipients ,  $postcontent ,  $posttags ,  $posttitle ,  $uname ,  $postmedia  );
if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
  $con =  $mysqli;
  if($stmt= $con->prepare( "INSERT INTO posts (content, title,   tags, dt, scope, type, recipients, name, file) VALUES (?,?,?,?,?,?,?,?,?) ")){
$stmt->bind_param('sssssssss', $postcontent ,  $posttitle ,  $posttags ,  $posttime ,  $postscope ,  $posttype ,  $postrecipients ,  $uname ,  $posttitle ,  $postmedia  );
if($stmt->execute()){
  $stmt->close(); 
 }else{
    $stmt->close();
    }      
  }
   echo '.';
}
}
$con =  $mysqli;

    
if ($posttype == "forum"){
    $i=0;
    foreach ($posttaglets as &$posttaglet){
      $con =  $mysqli;
        if($stmt= $con->prepare( "INSERT INTO forums (about, groups, posts, events, tag) 
        VALUES (?,?,?,?,?)")){

     $i=$i+1;
     $stmt->bind_param('sssss', $posttags ,  $postrecipients ,  $postinfo ,  $postrecipients , $posttaglet );
     if($stmt->execute()){
      $stmt->close(); 
     }else{
      $stmt->close();
      }  
     
       
    }

  }
    $i=0;
    $con =  $mysqli;
    if($stmt= $con->prepare( "INSERT INTO posts (content, title,   tags, dt, scope, type, recipients, name, file) VALUES (?,?,?,?,?,?,?,?,?) ")){
        
    $stmt->bind_param('sssssssss', $postcontent ,  $posttitle ,  $posttags ,  $posttime ,  $postscope ,  $posttype ,  $postrecipients ,  $uname ,  $posttitle ,  $postmedia  );
    if($stmt->execute()){
      $stmt->close(); 
     }else{
      $stmt->close();
      }      


      
    }

  }

$con =  $mysqli;

    if($stmt= $con->prepare( "INSERT INTO accounts(groups, events, files, forums, friends,  messages, posts, tags, username, id)  
VALUES (?,?,?,?,?,?,?,?,?,?)")){
$stmt->bind_param('sssssssssi',  $posttitle ,  $posttitle ,   $postmedia ,  $posttags ,  $postrecipients ,  $postinfo , $postinfo ,  $posttags ,  $uname ,  $id );  
if($stmt->execute()){
  $stmt->close(); 
 }else{
  $stmt->close();
  }
}  

     echo '.';
      

$con =  $mysqli;


header('Location: home.php');
sleep(6000 + 6000 * (0-(array_sum(explode(";",$votelist))*(1+abs(count(explode(";",$votelist)))))));
echo "<html>Post created. Wait 10min for next post: ";
echo "<a href='home.php'>Return to home</a></html>";
}else {
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




