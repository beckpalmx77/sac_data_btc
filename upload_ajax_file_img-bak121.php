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

// ตรวจสอบการอัปโหลดไฟล์
if (isset($_FILES['files']) && $_FILES['files']['error'][0] != UPLOAD_ERR_NO_FILE) {
    $fileCount = count($_FILES['files']['name']);
    if ($fileCount > 5) {
        echo json_encode(["error" => "You can only upload up to 5 files."]);
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

    $fileNames = [];
    $newFilenames = [];
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = basename($_FILES['files']['name'][$i]);
        $fileType = $_FILES['files']['type'][$i];

        if (array_key_exists($fileType, $allowedFileTypes)) {
            $extension = $allowedFileTypes[$fileType];
            $newFilename = uniqid() . '_' . $ADDB_COMPANY . '_' . $DI_REF . '.' . $extension;
            $targetFilePath = $uploadDir . $newFilename;
            $newFilenames[] = $newFilename;

            if (move_uploaded_file($_FILES['files']['tmp_name'][$i], $targetFilePath)) {
                $fileNames['file' . ($i + 1)] = $uploadDir . $newFilename;
            } else {
                echo json_encode(["error" => "Error uploading file: " . $fileName]);
                exit;
            }
        } else {
            echo json_encode(["error" => "Invalid file type: " . $fileType]);
            exit;
        }
    }

/*
    $txt = $id . " | " . implode(" | ", $newFilenames) . "\n\r";
    $myfile = fopen("upload-param.txt", "w") or die("Unable to open file!");
    fwrite($myfile, $txt);
    fclose($myfile);
*/

    $sql_update = "UPDATE ims_document_customer_service SET FILE_UPLOAD1=:file1, FILE_UPLOAD2=:file2, FILE_UPLOAD3=:file3, FILE_UPLOAD4=:file4, FILE_UPLOAD5=:file5 
    WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':file1', $fileNames['file1']);
    $query->bindParam(':file2', $fileNames['file2']);
    $query->bindParam(':file3', $fileNames['file3']);
    $query->bindParam(':file4', $fileNames['file4']);
    $query->bindParam(':file5', $fileNames['file5']);
    $query->bindParam(':id', $id, PDO::PARAM_STR);

    // Execute the statement
    if ($query->execute()) {
        echo json_encode(["success" => "Upload Success"]);
    } else {
        echo json_encode(["error" => "Error Upload FILE"]);
    }
} else {
    echo json_encode(["error" => "Error Please SELECT FILE"]);
}
