<?php
include 'config/connect_db.php';
header('Content-Type: text/html; charset=utf-8');

    $uploadField = $_POST['uploadField']; // ฟิลด์ที่เลือกจาก radio button
    $id = $_POST["id"];
    $DI_REF = str_replace('/', '-', $_POST["DI_REF"]);
    $DI_DATE = $_POST["DI_DATE"];
    $ADDB_COMPANY = $_POST["ADDB_COMPANY"];
    $ADDB_PHONE = $_POST["ADDB_PHONE"];
    $CAR_NO = $_POST["CAR_NO"];

    $uploadedFiles = [];
    $uploadDir = 'uploads/files/'; // directory to save uploaded files

    // ตรวจสอบว่าไดเร็กทอรีสำหรับอัปโหลดมีอยู่หรือไม่
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // ประมวลผลไฟล์ที่ถูกอัปโหลด
    foreach ($_FILES['files']['tmp_name'] as $key => $tmpName) {
        $originalFileName = basename($_FILES['files']['name'][$key]);
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
        $newFileName = uniqid() . '_' . $ADDB_COMPANY . '_' . $DI_REF . '.' . $fileExtension; // สร้างชื่อไฟล์ใหม่ด้วย uniqid()
        $filePath = $uploadDir . $newFileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $uploadedFiles[] = $uploadDir . $newFileName;
        }
    }

    if (!empty($uploadedFiles)) {
        $fileNames = implode(',', $uploadedFiles);

        // อัปเดตชื่อไฟล์ในฐานข้อมูล
        $sql = "UPDATE ims_document_customer_service SET $uploadField = :fileNames WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':fileNames', $fileNames);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            echo "อัปโหลดสำเร็จ";
        } else {
            echo "เกิดข้อผิดพลาดในการอัปโหลดไฟล์";
        }
    } else {
        echo "กรุณาเลือกไฟล์";
    }

