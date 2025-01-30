<?php
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'tabletime';
$mysqli = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

$totalpages = $mysqli->query("SELECT * FROM ($_GET 'table')");
$total_pages = $totalpages -> num_rows;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;


$num_results_on_page = ($_GET['items']) ;

if ($stmt = $mysqli->prepare("SELECT * FROM ($_GET 'table') ORDER BY name LIMIT ?,?")) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$result = $stmt->get_result();
}
?>