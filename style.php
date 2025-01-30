<?php
session_start();

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


.calendar {
    display: flex;
    flex-flow: column;
}
.calendar .header .month-year {
    font-size: 20px;
    font-weight: bold;
    color: <?php echo $colort; ?>;
    padding: 20px 0;
}
.calendar .days {
    display: flex;
    flex-flow: wrap;
}
.calendar .days .day_name {
    width: calc(100% / 7);
    border-right: 1px solid #2c7aca;
    padding: 20px;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: bold;
    color: <?php echo $colort; ?>;
    color: #fff;
    background-color: <?php echo $colorc; ?>;
}
.calendar .days .day_name:nth-child(7) {
    border: none;
}
.calendar .days .day_num {
    display: flex;
    flex-flow: column;
    width: calc(100% / 7);
    border-right: 1px solid <?php echo $colorc2; ?>;
    border-bottom: 1px solid <?php echo $colorc3; ?>;
    padding: 15px;
    font-weight: bold;
    color: <?php echo $colort; ?>;
    cursor: pointer;
    min-height: 100px;
}
.calendar .days .day_num span {
    display: inline-flex;
    width: 30px;
    font-size: 14px;
}
.calendar .days .day_num .event {
    margin-top: 10px;
    font-weight: 500;
    font-size: 14px;
    padding: 3px 6px;
    border-radius: 4px;
    background-color: <?php echo $colord; ?>;
    color: <?php echo $colort; ?>;
    word-wrap: break-word;
}
.calendar .days .day_num .event.green {
    background-color: <?php echo $colord2; ?>;
}
.calendar .days .day_num .event.blue {
    background-color: <?php echo $colord3; ?>;
}
.calendar .days .day_num .event.red {
    background-color: <?php echo $colore; ?>;
}
.calendar .days .day_num:nth-child(7n+1) {
    border-left: 1px solid <?php echo $colore2; ?>;
}
.calendar .days .day_num:hover {
    background-color: <?php echo $colorf2; ?>;
}
.calendar .days .day_num.ignore {
    background-color: <?php echo $colorf; ?>;
    color: #ced2d4;
    cursor: inherit;
}
.calendar .days .day_num.selected {
    background-color: <?php echo $colorf3; ?>;
    cursor: inherit;
}

* {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "segoe ui", roboto, oxygen, ubuntu, cantarell, "fira sans", "droid sans", "helvetica neue", Arial, sans-serif;
    font-size: 16px;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
body {
    background-color: <?php echo $colore; ?>;
    margin: 0;
}
.navtop {
    background-color: <?php echo $colord; ?>;
    height: 60px;
    width: 100%;
    border: 0;
}
.navtop div {
    display: flex;
    margin: 0 auto;
    width: 1000px;
    height: 100%;
}
.navtop div h1, .navtop div a {
    display: inline-flex;
    align-items: center;
}
.navtop div h1 {
    flex: 1;
    font-size: 24px;
    padding: 0;
    margin: 0;
    color: <?php echo $colort; ?>;
    font-weight: normal;
}
.navtop div a {
    padding: 0 20px;
    text-decoration: none;
    color: #<?php echo $colort; ?>;
    font-weight: bold;
}
.navtop div a i {
    padding: 2px 8px 0 0;
}
.navtop div a:hover {
    color: #<?php echo $colort; ?>;
}
.content {
    width: 1000px;
    margin: 0 auto;
}
.content h2 {
    margin: 0;
    padding: 25px 0;
    font-size: 22px;
    border-bottom: 1px solid #<?php echo $colord3; ?>;
    color: <?php echo $colort; ?>;
}
.image-popup {
    display: none;
    flex-flow: column;
    justify-content: center;
    align-items: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: <?php echo $colore2; ?>;
    z-index: 99999;
}
.image-popup .con {
    display: flex;
    flex-flow: column;
    background-color: <?php echo $colore3; ?>;
    padding: 25px;
    border-radius: 5px;
}
.image-popup .con h3 {
    margin: 0;
    font-size: 18px;
}
.image-popup .con .edit, .image-popup .con .trash {
    display: inline-flex;
    justify-content: center;
    align-self: flex-end;
    width: 40px;
    text-decoration: none;
    color: <?php echo $colort; ?>;
    padding: 10px 12px;
    border-radius: 5px;
    margin-top: 10px;
}
.image-popup .con .trash {
    background-color: <?php echo $colore; ?>;
}
.image-popup .con .trash:hover {
    background-color: <?php echo $colord2; ?>;
}
.image-popup .con .edit {
    background-color: <?php echo $colorf2; ?>;
}
.image-popup .con .edit:hover {
    background-color: <?php echo $colord; ?>;
}
.home .upload-image {
    display: inline-block;
    text-decoration: none;
    background-color: <?php echo $colore2; ?>;
    font-weight: bold;
    font-size: 14px;
    border-radius: 5px;
    color: #FFFFFF;
    padding: 10px 15px;
    margin: 15px 0;
}
.home .upload-image:hover {
    background-color: <?php echo $colore3; ?>;
}
.home .images {
    display: flex;
    flex-flow: wrap;
}
.home .images a {
    display: block;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    width: 300px;
    height: 200px;
    margin: 0 20px 20px 0;
}
.home .images a:hover span {
    opacity: 1;
    transition: opacity 1s;
}
.home .images span {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    position: absolute;
    opacity: 0;
    top: 0;
    left: 0;
    color: <?php echo $colort; ?>;
    width: 100%;
    height: 100%;
    background-color: <?php echo $colord3; ?>;
    padding: 15px;
    transition: opacity 1s;
}
.upload form {
    padding: 15px 0;
    display: flex;
    flex-flow: column;
    width: 400px;
}
.upload form label {
    display: inline-flex;
    width: 100%;
    padding: 10px 0;
    margin-right: 25px;
}
.upload form input, .upload form textarea {
    padding: 10px;
    width: 100%;
    margin-right: 25px;
    margin-bottom: 15px;
    border: 1px solid <?php echo $colord; ?>;
}
.upload form textarea {
    height: 200px;
}
.upload form input[type="submit"] {
    display: block;
    background-color: <?php echo $colord2; ?>;
    border: 0;
    font-weight: bold;
    font-size: 14px;
    color: <?php echo $colort; ?>;
    cursor: pointer;
    width: 200px;
    margin-top: 15px;
    border-radius: 5px;
}
.upload form input[type="submit"]:hover {
    background-color: <?php echo $colord2; ?>;
}
.delete .yesno {
    display: flex;
}
.delete .yesno a {
    display: inline-block;
    text-decoration: none;
    background-color: <?php echo $colore; ?>;
    font-weight: bold;
    color: <?php echo $colort; ?>;
    padding: 10px 15px;
    margin: 15px 10px 15px 0;
    border-radius: 5px;
}
.delete .yesno a:hover {
    background-color: <?php echo $colore3; ?>;
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