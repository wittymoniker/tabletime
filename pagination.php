<?php
$mysqli = mysqli_connect('localhost', 'root', '', 'tabletime');

$total_pages = $mysqli->query('SELECT * FROM ($_GET 'table')')->num_rows;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;


$num_results_on_page = ($_GET['items']) ;

if ($stmt = $mysqli->prepare('SELECT * FROM ($_GET 'table') ORDER BY name LIMIT ?,?')) {

	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$result = $stmt->get_result();
?>