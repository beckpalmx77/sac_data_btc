<?php
include 'config/connect_db.php';
session_start();
error_reporting(0);

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = $conn->prepare("SELECT FILE_UPLOAD1, FILE_UPLOAD2, FILE_UPLOAD3, FILE_UPLOAD4, FILE_UPLOAD5 FROM ims_document_customer_service WHERE id=:id");
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result);
}


