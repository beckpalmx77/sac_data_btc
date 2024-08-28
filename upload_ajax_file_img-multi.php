<?php
include 'config/connect_db.php';
session_start();
error_reporting(0);

// ตั้งค่าการเข้ารหัสของ PHP
header('Content-Type: text/html; charset=utf-8');

$id = $_POST["id"];
$DI_REF = str_replace('/', '-', $_POST["DI_REF"]);
$DI_DATE = $_POST["DI_DATE"];
$ADDB_COMPANY = $_POST["ADDB_COMPANY"];
$ADDB_PHONE = $_POST["ADDB_PHONE"];
$CAR_NO = $_POST["CAR_NO"];

// ตรวจสอบการอัปโหลดไฟล์
if (isset($_FILES['files']) && $_FILES['files']['error'][0] != UPLOAD_ERR_NO_FILE) {
    $fileCount = count($_FILES['files']['name']);
    if ($fileCount > 5) {
        echo "คุณสามารถอัปโหลดไฟล์ได้สูงสุด 6 ไฟล์เท่านั้น";
        exit;
    }

    $allowedFileTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'application/pdf' => 'pdf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'
    ];
    $uploadDir = 'uploads/files/'; // directory to save uploaded files

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Fetch current file fields from database
    $sql_fetch = "SELECT FILE_UPLOAD1,FILE_UPLOAD2,FILE_UPLOAD3,FILE_UPLOAD4,FILE_UPLOAD5,FILE_UPLOAD6 FROM ims_document_customer_service WHERE id = :id";
    $query = $conn->prepare($sql_fetch);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);

    // Initialize file fields array
    $fileFields = ['FILE_UPLOAD1', 'FILE_UPLOAD2', 'FILE_UPLOAD3', 'FILE_UPLOAD4', 'FILE_UPLOAD5', 'FILE_UPLOAD6'];
    $fileNames = [];

    // Populate existing files into fileNames array
    foreach ($fileFields as $field) {
        $fileNames[$field] = !empty($row[$field]) ? $row[$field] : null;
    }

    // Process and upload new files
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = basename($_FILES['files']['name'][$i]);
        $fileType = $_FILES['files']['type'][$i];

        if (array_key_exists($fileType, $allowedFileTypes)) {
            $extension = $allowedFileTypes[$fileType];
            $newFilename = uniqid() . '_' . $ADDB_COMPANY . '_' . $DI_REF . '.' . $extension;
            $targetFilePath = $uploadDir . $newFilename;

            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFilePath)) {
                // Find the next available field
                foreach ($fileFields as $field) {
                    if (empty($fileNames[$field])) {
                        $fileNames[$field] = $uploadDir . $newFilename;
                        break;
                    }
                }
            } else {
                echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์: " . $fileName;
                exit;
            }
        } else {
            echo "ประเภทไฟล์ไม่ถูกต้อง: " . $fileType;
            exit;
        }
    }

    // Update the database with new file names
    $sql_update = "UPDATE ims_document_customer_service SET 
                   FILE_UPLOAD1=:file1, 
                   FILE_UPLOAD2=:file2, 
                   FILE_UPLOAD3=:file3, 
                   FILE_UPLOAD4=:file4, 
                   FILE_UPLOAD5=:file5,
                   FILE_UPLOAD6=:file6 
                   WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':file1', $fileNames['FILE_UPLOAD1']);
    $query->bindParam(':file2', $fileNames['FILE_UPLOAD2']);
    $query->bindParam(':file3', $fileNames['FILE_UPLOAD3']);
    $query->bindParam(':file4', $fileNames['FILE_UPLOAD4']);
    $query->bindParam(':file5', $fileNames['FILE_UPLOAD5']);
    $query->bindParam(':file6', $fileNames['FILE_UPLOAD6']);
    $query->bindParam(':id', $id, PDO::PARAM_STR);

    // Execute the statement
    if ($query->execute()) {
        echo "อัปโหลดสำเร็จ";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
    }
} else {
    echo "กรุณาเลือกไฟล์";
}
?>
