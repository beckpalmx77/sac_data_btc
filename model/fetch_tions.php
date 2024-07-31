<?php
include '../config/connect_db_btc.php';

try {

    $sql_get = 'SELECT permission_id, permission_detail FROM ims_permission';
    // Prepare an SQL statement
    $stmt = $conn_btc->prepare($sql_get);


    $myfile = fopen("pw-param.txt", "w") or die("Unable to open file!");
    fwrite($myfile,  $sql_get);
    fclose($myfile);

    // Execute the statement
    $stmt->execute();

    // Fetch all results
    $permission = $stmt->fetchAll();

    // Return the results as JSON
    echo json_encode($permission);

} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['error' => $e->getMessage()]);
}
?>