<?php
require_once __DIR__ . '/db.php';
function pdo_connect_mysql() {
    global $DATABASE_HOST, $DATABASE_PORT, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME;
    // The below variables should reflect your MySQL credentials




    try {
        // Connect to MySQL using the PDO extension
    	return new PDO('mysql:host=' . preg_replace('/:\d+$/', '', $DATABASE_HOST) . ';port=' . (int)$DATABASE_PORT . ';dbname=' . $DATABASE_NAME . ';charset=utf8mb4', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and output the error.
    	exit('Failed to connect to database!');
    }
}

function template_header($title) {
    echo <<<EOT
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>$title</title>
            <link href="style.php" rel="stylesheet" type="text/css">
        </head>
        <body>
        <nav class="navtop">
            <div>
                <h1>Gallery System</h1>
                <a href="post.php"><i class="fas fa-image"></i>Gallery</a>
            </div>
        </nav>
    EOT;
    }
    function template_footer() {
        echo <<<EOT
            </body>
        </html>
        EOT;
        }
?>