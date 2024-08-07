<?php
include 'config/connect_db.php';
session_start();
error_reporting(0);

$id = $_POST["id"];
$DI_REF = str_replace('/', '-', $_POST["DI_REF"]);
$DI_DATE = $_POST["DI_DATE"];
$ADDB_COMPANY = $_POST["ADDB_COMPANY"];
$ADDB_PHONE = $_POST["ADDB_PHONE"];
$CAR_NO = $_POST["CAR_NO"];

$myfile = fopen("a_menu-param3.txt", "w") or die("Unable to open file!");
fwrite($myfile,  $id . " | " . $CAR_NO);
fclose($myfile);


if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
    $allowedExts = array('xlsx', 'xls', 'pdf','jpg','png');
    $temp = explode('.', $_FILES['fileToUpload']['name']);
    $extension = end($temp);

    if (in_array($extension, $allowedExts)) {
        $uploadDir = 'uploads/files/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // ตั้งชื่อไฟล์ใหม่โดยการต่อ $DI_REF เข้ากับชื่อไฟล์เดิม
        $filenameWithoutExtension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_FILENAME);
        $newFilename = $filenameWithoutExtension . '_' . $ADDB_COMPANY . '_' . $DI_REF . '.' . $extension;
        $uploadFile = $uploadDir . basename($newFilename);

        if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $uploadFile)) {
            $response = "Upload ไฟล์สำเร็จ";
            // คุณสามารถเก็บข้อมูลในฐานข้อมูลที่นี่
            $sql_update = "UPDATE ims_document_customer_service SET FILE_UPLOAD1=:FILE_UPLOAD1 WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':FILE_UPLOAD1', $uploadFile, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();

            echo $response;

        } else {
            $response = "เกิดข้อผิดพลาด Upload ไฟล์ ไม่สำเร็จ";
            echo $response;
        }
    } else {
        echo 'เกิดข้อผิดพลาด Upload ไฟล์ ไม่สำเร็จ ต้องเป็นไฟล์ Excel หรือ PDF เท่านั้น';
        $response = "เกิดข้อผิดพลาด Upload ไฟล์ ไม่สำเร็จ ต้องเป็นไฟล์ Excel หรือ PDF เท่านั้น";
    }
} else {
    echo 'เกิดข้อผิดพลาดไม่มีไฟล์ กรุณาเลือกไฟล์';
    $response = "เกิดข้อผิดพลาดไม่มีไฟล์ กรุณาเลือกไฟล์ ";
}

