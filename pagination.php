<?php
$DATABASE_HOST = 'sql103.infinityfree.com';
$DATABASE_USER = 'if0_38191057';
$DATABASE_PASS = 'Greenapples55';
$DATABASE_NAME = 'if0_38191057_tabletime';
$mysqli =  new mysqli($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
$con = $mysqli;
$totalpages = $con->query("SELECT * FROM ($_GET 'table')");
$total_pages = $totalpages -> num_rows;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;


$num_results_on_page = 16 ;
$con = $mysqli;
if ($stmt = $con->prepare("SELECT * FROM ($_GET 'table') LIMIT ?,?")) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$result = $stmt->get_result();
}
?>