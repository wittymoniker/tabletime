<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
	header('Location: login.php');
	exit;
}
header("Content-type: text/css");
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}




$colora= #ababab";
$colorb= "#bcbcbc";
$colorc= "#cdcdcd";
$colord= "#dcdcdc";
$colore= "#ededed";
$colorf= "#dfdfdf";

$colora2= "#0a0a0a";
$colorb2= "#1b1b1b";
$colorc2= "#2c2c2c";
$colord2= "#3d3d3d";
$colore2= "#4e4e4e";
$colorf2= "#5f5f5f";

$colora3= "#a3a3a3";
$colorb3= "#b2b2b2";
$colorc3= "#c1c1c1";
$colord3= "#d1d1d1";
$colore3= "#e2e2e2";
$colorf3= "#f3f3f3";



$colort = "#000000";
$fontSize = "14";


?>

* {
  	box-sizing: border-box;
  	font-family: 'Courier New', Courier, monospace;
  	font-size: <?php echo ($fontSize+2); ?>px;
	text-decoration: none
	align-items: center;
	text-align: center;

}
.tabletime body {
  	background-color: <?php echo $colore; ?>;
}
.tabletime{
  	width: 1250px;
  	background-color: <?php echo $colord; ?>;
  	margin: 100px auto;
}
.tabletime h1 {
  	text-align: center;
  	color: <?php echo $colort; ?>;
  	font-size: <?php echo ($fontSize+14); ?>px;
  	padding: 20px 0 20px 0;
  	border-bottom: <?php echo $colorc; ?>;
}
.tabletime form {
    	padding-top: 20px;
}
.tabletime form label {
  	display: flex;
  	justify-content: center;
  	align-items: center;
  	width: 75px;
  	height: 50px;
  	background-color: <?php echo $colorf; ?>;
  	color: <?php echo $colort; ?>;
}
.tabletime form input[type="password"], 
.tabletime form input[type="text"] {
  	width: 310px;
  	height: 50px;
  	border: <?php echo $colora; ?>;
  	margin-bottom: 20px;
}

.tabletime form input[type="username"], 
.tabletime form input[type="text"] {
  	width: 310px;
  	height: 50px;
  	border: <?php echo $colorc; ?>;
  	margin-bottom: 20px;
  	padding: 0 15px;
}

.tabletime form input[type="text"] {
  	width: 500px;
  	height: 50px;
  	border: <?php echo $colorb; ?>;
  	margin-bottom: 20px;
  	padding: 0 15px;
}


.tabletime form input[type="submit"] {
  	width: 50%;
  	padding: 20px;
 	margin-top: 20px;
  	background-color: <?php echo $colord; ?>;
  	border: 0;
  	cursor: pointer;
  	font-weight: bold;
  	color: <?php echo $colorb; ?>;;
  	transition: background-color 0.2s;
}
.tabletime form input[type="submit"]:hover {
	background-color: <?php echo $colord; ?>;
  	transition: background-color 0.2s;
}










.navtop {
	background-color: <?php echo $colore3?>;
	height: 240px;
	width: 100%;
	border: 10%;
}
.navtop div {
	display: flex;
	margin: 0 auto;
	width: 750px;
	height: 100%;
}
.navtop div h1, .navtop div a {
	display: inline-flex;
	align-items: center;
}
.navtop div h1 {
	flex: 1;
	font-size: <?php echo ($fontSize+17); ?>px;
	padding: 0;
	margin: 0;
	color: <?php echo $colort; ?>;
	font-weight: bolder;
}
.navtop div a {
	padding: 20px;
	text-decoration: none;
	color: <?php echo $colora2; ?>;
	font-weight: bold;
}
.navtop div a i {
}
.navtop div a:hover {
	color: <?php echo $colora3; ?>;
}
.content {
	width: 1000px;
	margin: auto;
}
.content h2 {
	margin: 0;
	font-size: <?php echo ($fontSize+10); ?>px;
	border-bottom: <?php echo $colorf3; ?>;
	color: <?php echo $colort3; ?>;
}
.content < p, .content < div {
	margin: 25px 0;
	padding: 25px;
	background-color: <?php echo $colore2 ?>;
}
.content > p table td, .content > div table td {
	padding: 30px;
}
.content > p table td:first-child, .content > div table td:first-child {
	font-weight: bold;
	color: <?php echo $colort; ?>;
}
.content > div p {
	padding: 5px;
}




.html {
			padding: 30px;
			background-color: <?php echo $colore3; ?>;					align-items: center;
align-items: center;
			text-align: center;

			font-family: 'Courier New', Courier, monospace;

			}
			.html > table {
				border-collapse: collapse;
				width: 750px;
			}
			.html > td, th {
				padding: 20px;
			}
			.html > th {
				background-color: <?php echo $colord3; ?>;
				color: <?php echo $colort; ?>;
				font-weight: italic;
				font-size: <?php echo ($fontSize+8); ?>px;
				border: <?php echo $colorb3; ?>;
			}
			.html > td {
				color: <?php echo $colort2; ?>;
				border: <?php echo $colorf3; ?>;
			}
			.html > tr {
				background-color: <?php echo $colord3; ?>;
			}
			.html > tr:nth-child(odd) {
				background-color: <?php echo $colore3; ?>;
			}
			.list {
				list-style-type: none;
				padding: 10px;
				display: inline-flex;
				justify-content: space-between;
				box-sizing: border-box;
			}
			.list li {
				box-sizing: border-box;
				padding-right: 14px;
			}
			.list li a {
				box-sizing: border-box;
				background-color: <?php echo $colorf2; ?>;
				padding: 12px;
				text-decoration: none;
				font-size: <?php echo ($fontSize+1); ?>px;
				font-weight: bold;
				color: <?php echo $colora3; ?>;
				border-radius: 9px;
			}
			.list li a:hover {
				background-color: <?php echo $colorb3; ?>;
			}
			.list .next a, .list .prev a {
				text-transform: uppercase;
				font-size: <?php echo ($fontSize+14); ?>px;
			}
			.list .currentpage a {
				background-color: <?php echo $colorf3; ?>;
				color: <?php echo $colore; ?>;
			}
			.list .currentpage a:hover {
				background-color: <?php echo $colorf3; ?>;
			}





.tabletime a {
    text-decoration: none;
}
.content a {
    text-decoration: none;
}