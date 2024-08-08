<?php
session_start();
error_reporting(0);
include('config/connect_db.php');

if(isset($_POST['key']) && isset($_POST['id'])) {
    $key = $_POST['key'];
    $id = $_POST['id'];

    // Fetch the current files
    $query = $conn->prepare("SELECT FILE_UPLOAD1, FILE_UPLOAD2, FILE_UPLOAD3, FILE_UPLOAD4, FILE_UPLOAD5 FROM ims_document_customer_service WHERE id=:id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    // Remove the specified file
    $result[$key] = null;

    // Reorder files
    $files = array_values(array_filter($result)); // Remove null values and reindex array
    for ($i = 1; $i <= 5; $i++) {
        $result['FILE_UPLOAD' . $i] = isset($files[$i - 1]) ? $files[$i - 1] : null;
    }

    // Update database
    $query = $conn->prepare("UPDATE ims_document_customer_service SET 
        FILE_UPLOAD1=:file1, FILE_UPLOAD2=:file2, FILE_UPLOAD3=:file3, FILE_UPLOAD4=:file4, FILE_UPLOAD5=:file5 WHERE id=:id");
    $query->bindParam(':file1', $result['FILE_UPLOAD1'], PDO::PARAM_STR);
    $query->bindParam(':file2', $result['FILE_UPLOAD2'], PDO::PARAM_STR);
    $query->bindParam(':file3', $result['FILE_UPLOAD3'], PDO::PARAM_STR);
    $query->bindParam(':file4', $result['FILE_UPLOAD4'], PDO::PARAM_STR);
    $query->bindParam(':file5', $result['FILE_UPLOAD5'], PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();

    echo "success";
}
?>
