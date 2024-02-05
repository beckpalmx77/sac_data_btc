<?php
session_start();
error_reporting(0);
include '../config/connect_db.php';

// Number of records fetch
$numberofrecords = 500000;

$manage_team_id = ($_SESSION['manage_team_id'] === '-') ? "'%'" : "'%" . $_SESSION['manage_team_id'] . "%'";

if(!isset($_POST['searchTerm'])){

	// Fetch records
    $sql_tires = "SELECT * FROM ims_tires_master GROUP BY brand ORDER BY brand LIMIT :limit";
    $stmt = $conn->prepare($sql_tires);
	$stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
	$stmt->execute();
    $tiresList = $stmt->fetchAll();

}else{

	$search = $_POST['searchTerm'];// Search text
	
	// Fetch records
    $sql_tires = "SELECT * FROM ims_tires_master WHERE brand like :brand GROUP BY brand ORDER BY brand LIMIT :limit";
    $stmt = $conn->prepare($sql_tires);
	$stmt->bindValue(':brand', '%'.$search.'%', PDO::PARAM_STR);
	$stmt->bindValue(':limit', (int)$numberofrecords, PDO::PARAM_INT);
	$stmt->execute();
    $tiresList = $stmt->fetchAll();

}

/*
$myfile = fopen("qry_file_mysql_server.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_tires);
fclose($myfile);
*/

	
$response = array();

// Read Data
foreach($tiresList as $tires){
	$response[] = array(
		"id" => $tires['brand'],
		"text" => $tires['brand']
    );
}

echo json_encode($response);
exit();
