<?php
// We need to use sessions, so you should always start sessions using the below password.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
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
error_reporting(E_ERROR | E_PARSE);

$stmt = $con->prepare('SELECT username, files FROM accounts WHERE id = ?');

$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($username, $files);
$stmt->fetch();
$stmt->close();


?>



<?php



if (isset($_GET['file'])) {
    // If the file is a directory
    if (is_dir($_GET['file'])) {
        // Update the current directory
        $current_directory = $_GET['file'] . '/';
    } else {
        // Download file
        header('Content-Description: File Transfer'); 
        header('Content-Type: application/octet-stream'); 
        header('Content-Disposition: attachment; filename="' . basename($_GET['file']) . '"'); 
        readfile($_GET['file']);
        exit; 
    }
}
// Retrieve all files and directories
$results = glob(str_replace(['[',']',"\f[","\f]"], ["\f[","\f]",'[[]','[]]'], ($current_directory ? $current_directory : $initial_directory)) . '*');
// If true, directories will appear first in the populated file list
$directory_first = true; 
// Sort files
$initial_directory = 'files/';
// Sort files
if ($directory_first) {
    usort($results, function($a, $b){
        $a_is_dir = is_dir($a);
        $b_is_dir = is_dir($b);
        if ($a_is_dir === $b_is_dir) {
            return strnatcasecmp($a, $b);
        } else if ($a_is_dir && !$b_is_dir) {
            return -1;
        } else if (!$a_is_dir && $b_is_dir) {
            return 1;
        }
    });
}






function convert_filesize($bytes, $precision = 2) {
    $units = ['Bytes', 'KB', 'MB', 'GB', 'TB']; 
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}




function get_filetype_icon($filetype) {
    if (is_dir($filetype)) {
        return '<i class="fa-solid fa-folder"></i>';
    } else if (preg_match('/image\/*/', mime_content_type($filetype))) {
        return '<i class="fa-solid fa-file-image"></i>';
    } else if (preg_match('/video\/*/', mime_content_type($filetype))) {
        return '<i class="fa-solid fa-file-video"></i>';
    } else if (preg_match('/audio\/*/', mime_content_type($filetype))) {
        return '<i class="fa-solid fa-file-audio"></i>';
    } else if (preg_match('/audio\/*/', mime_content_type($filetype))) {
        return '<i class="fa-solid fa-file-audio"></i>';
    }
    return '<i class="fa-solid fa-file"></i>';
}
?>

<html class = "tabletime">
		<link href="style.php" rel="stylesheet" type="text/css">
	<head class = "html">
		<meta charset="utf-8">
		<title>tabletime</title>



		
	<body class="content">



    <nav class = "navtop">
		<div class = "tabletime">		

<h1><b><a href="home.php">TABLETIME</a></b></h1>
<p>
<a href="post.php"><i class="fas fa-user-circle"></i>Messages</a>
<a href="create.php"><i class="fas fa-user-circle"></i>Create</a>
<a href="friend.php"><i class="fas fa-user-circle"></i>Friends</a><br>
<a href="file.php"><i class="fas fa-user-circle"></i>Files</a>
<a href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
<a href="statsmap.php"><i class="fas fa-user-circle"></i>Stats/Map</a>
<a href="group.php"><i class="fas fa-user-circle"></i>Groups</a><br>
<a href="people.php"><i class="fas fa-user-circle"></i>People</a>
<a href="post.php"><i class="fas fa-user-circle"></i>Posts</a>
<a href="event.php"><i class="fas fa-user-circle"></i>Events</a></p><br>




			</div>
</nav>







<div class = "html">
                <a href="createUI.php?directory=<?=$current_directory?>"><i class="fa-solid fa-plus"></i></a>


            <table class="tabletime">
                <th>
                    <tr>
                        <td class="selected-column">Name<i class="fa-solid fa-arrow-down-long fa-xs"></i></td>
                        <td>Size</td>
                        <td>Modified</td>
                        <td>Actions</td>
                    </tr>
                </th>
                <tbody>
                    <?php if (isset($_GET['file']) && realpath($current_directory) != realpath($initial_directory)): ?>
                    <tr>
                        <td colspan="10" class="name"><i class="fa-solid fa-folder"></i><a href="?file=<?=urlencode($_GET['file']) . '/..'?>">...</a></td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($results as $result): ?>
                    <tr class="file">
                        <td class="name"><?=get_filetype_icon($result)?><a class="view-file" href="?file=<?=urlencode($result)?>"><?=basename($result)?></a></td>
                        <td><?=is_dir($result) ? 'Folder' : convert_filesize(filesize($result))?></td>
                        <td class="date"><?=str_replace(date('F j, Y'), 'Today,', date('F j, Y H:ia', filemtime($result)))?></td>
                        <td class="actions">
                            <a href="rename.php?file=<?=urlencode($result)?>" class="btn"><i class="fa-solid fa-pen fa-xs"></i></a>
                            <a href="delete.php?file=<?=urlencode($result)?>" class="btn red" onclick="return confirm('Are you sure you want to delete <?=basename($result)?>?')"><i class="fa-solid fa-trash fa-xs"></i></a>
                            <?php if (!is_dir($result)): ?>
                            <a href="?file=<?=urlencode($result)?>" class="btn green"><i class="fa-solid fa-download fa-xs"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
                            </div>
        </div>
        </div>  
                
                                 
                                       
</body>
</head>
</html>
