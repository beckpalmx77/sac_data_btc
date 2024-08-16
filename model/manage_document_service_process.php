<?php
session_start();
error_reporting(0);

include('../config/connect_db.php');
include('../config/lang.php');
include('../util/reorder_record.php');


if ($_POST["action"] === 'GET_DATA') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM ims_document_customer_service WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "DI_REF" => $result['DI_REF'],
            "DI_DATE" => $result['DI_DATE'],
            "REMARK" => $result['REMARK'],
            "ADDB_COMPANY" => $result['ADDB_COMPANY'],
            "ADDB_PHONE" => $result['ADDB_PHONE'],
            "CAR_NO" => $result['CAR_NO']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'GET_FILE') {

    $id = $_POST["id"];

    $return_arr = array();

    $sql_get = "SELECT * FROM ims_document_customer_service WHERE id = " . $id;
    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($results as $result) {
        $return_arr[] = array("id" => $result['id'],
            "FILE_UPLOAD1" => $result['FILE_UPLOAD1'],
            "FILE_UPLOAD2" => $result['FILE_UPLOAD2'],
            "FILE_UPLOAD3" => $result['FILE_UPLOAD3'],
            "FILE_UPLOAD4" => $result['FILE_UPLOAD4'],
            "FILE_UPLOAD5" => $result['FILE_UPLOAD5'],
            "FILE_UPLOAD6" => $result['FILE_UPLOAD6']);
    }

    echo json_encode($return_arr);

}

if ($_POST["action"] === 'SEARCH') {

    if ($_POST["DI_REF"] !== '') {

        $DI_REF = $_POST["DI_REF"];
        $sql_find = "SELECT * FROM ims_document_customer_service WHERE DI_REF = '" . $DI_REF . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            echo 2;
        } else {
            echo 1;
        }
    }
}

/*
$myfile = fopen("a_carno.txt", "w") or die("Unable to open file!");
fwrite($myfile,  $id . " | " . $CAR_NO . " | " . $sql_find . " | " . $sql_update);
fclose($myfile);
*/


if ($_POST["action"] === 'UPDATE') {
    if ($_POST["id"] != '') {
        $id = $_POST["id"];
        $CAR_NO = $_POST["CAR_NO"];
        $REMARK = $_POST["REMARK"];
        $sql_find = "SELECT * FROM ims_document_customer_service WHERE id = '" . $id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE ims_document_customer_service SET CAR_NO=:CAR_NO,REMARK=:REMARK            
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':CAR_NO', $CAR_NO, PDO::PARAM_STR);
            $query->bindParam(':REMARK', $REMARK, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }
    }
}

if ($_POST["action"] === 'DELETE_FILE') {

    $file_name = $_POST['file_name'];
    $id = $_POST['id'];

    // ลบไฟล์จากระบบไฟล์
    $file_path = $file_name;

    if (file_exists($file_path)) {
        unlink($file_path);
    }

    // อัปเดตฐานข้อมูล
    $stmt = $conn->prepare("SELECT * FROM ims_document_customer_service WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // Remove the file from record
    $files = [
        'FILE_UPLOAD1' => $record['FILE_UPLOAD1'],
        'FILE_UPLOAD2' => $record['FILE_UPLOAD2'],
        'FILE_UPLOAD3' => $record['FILE_UPLOAD3'],
        'FILE_UPLOAD4' => $record['FILE_UPLOAD4'],
        'FILE_UPLOAD5' => $record['FILE_UPLOAD5'],
        'FILE_UPLOAD6' => $record['FILE_UPLOAD6'],
    ];

    /*
        $txt = $_POST["action"] . " | " . $file_name . " | " . $id;
        $myfile = fopen("a_delete_file1.txt", "w") or die("Unable to open file!");
        fwrite($myfile,  $file_name . " | DELETE | ID = " . $id . " | " . print_r($files, TRUE));
        fclose($myfile);
    */

    foreach ($files as $key => $file) {
        if ($file == $file_name) {
            $files[$key] = null; // Remove the file name
            break;
        }
    }

    // จัดเรียงอาร์เรย์ใหม่ให้เลื่อนไฟล์ที่เหลือขึ้นมา
    $files = array_values(array_filter($files));

    $fileContent = implode("\n", $files); // แปลงอาร์เรย์เป็นสตริงโดยใช้ new line เป็นตัวคั่น

// แยกค่าจากอาร์เรย์ลงในตัวแปรแยก
    $file1 = isset($files[0]) ? $files[0] : null;
    $file2 = isset($files[1]) ? $files[1] : null;
    $file3 = isset($files[2]) ? $files[2] : null;
    $file4 = isset($files[3]) ? $files[3] : null;
    $file5 = isset($files[4]) ? $files[4] : null;
    $file6 = isset($files[5]) ? $files[5] : null;

    $txt = "1 = " . $file1 . " | " . "\n\r" .
        "2 = " . $file2 . " | " . "\n\r" .
        "3 = " . $file3 . " | " . "\n\r" .
        "4 = " . $file4 . " | " . "\n\r" .
        "5 = " . $file5 . " | " . "\n\r" .
        "6 = " . $file6 . "\n\r" .
        " ID = " . $id;

    /*
        $myfile = fopen("a_delete_file1.txt", "w") or die("Unable to open file!");
        fwrite($myfile,  $txt);
        fclose($myfile);
    */

    $sql_update = "UPDATE ims_document_customer_service SET FILE_UPLOAD1=:FILE_UPLOAD1,FILE_UPLOAD2=:FILE_UPLOAD2
            ,FILE_UPLOAD3=:FILE_UPLOAD3,FILE_UPLOAD4=:FILE_UPLOAD4,FILE_UPLOAD5=:FILE_UPLOAD5,FILE_UPLOAD6=:FILE_UPLOAD6
            WHERE id = :id";
    $query = $conn->prepare($sql_update);
    $query->bindParam(':FILE_UPLOAD1', $file1, PDO::PARAM_STR);
    $query->bindParam(':FILE_UPLOAD2', $file2, PDO::PARAM_STR);
    $query->bindParam(':FILE_UPLOAD3', $file3, PDO::PARAM_STR);
    $query->bindParam(':FILE_UPLOAD4', $file4, PDO::PARAM_STR);
    $query->bindParam(':FILE_UPLOAD5', $file5, PDO::PARAM_STR);
    $query->bindParam(':FILE_UPLOAD6', $file6, PDO::PARAM_STR);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    echo $save_success;

    /*
        $myfile = fopen("a_delete_file_update.txt", "w") or die("Unable to open file!");
        fwrite($myfile,  $sql_update);
        fclose($myfile);
    */

}

if ($_POST["action"] === 'GET_DOCUMENT') {

## Read value
    $draw = $_POST['draw'];
    $row = $_POST['start'];
    $rowperpage = $_POST['length']; // Rows display per page
    $columnIndex = $_POST['order'][0]['column']; // Column index
    $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
    $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
    $searchValue = $_POST['search']['value']; // Search value

    $searchArray = array();

## Search
    $searchQuery = " ";
    if ($searchValue != '') {
        $searchQuery = " AND (DI_REF LIKE :DI_REF or 
        ADDB_COMPANY LIKE :ADDB_COMPANY OR
        ADDB_PHONE LIKE :ADDB_PHONE OR
        DI_DATE LIKE :DI_DATE OR                
        CAR_NO LIKE :CAR_NO) ";
        $searchArray = array(
            'DI_REF' => "%$searchValue%",
            'ADDB_COMPANY' => "%$searchValue%",
            'ADDB_PHONE' => "%$searchValue%",
            'DI_DATE' => "%$searchValue%",
            'CAR_NO' => "%$searchValue%"
        );
    }

## Total number of records without filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ims_document_customer_service ");
    $stmt->execute();
    $records = $stmt->fetch();
    $totalRecords = $records['allcount'];

## Total number of records with filtering
    $stmt = $conn->prepare("SELECT COUNT(*) AS allcount FROM ims_document_customer_service WHERE 1 " . $searchQuery);
    $stmt->execute($searchArray);
    $records = $stmt->fetch();
    $totalRecordwithFilter = $records['allcount'];

## Fetch records
    $stmt = $conn->prepare("SELECT * FROM ims_document_customer_service WHERE 1 " . $searchQuery
        . " ORDER BY id DESC " . " LIMIT :limit,:offset");

// Bind values
    foreach ($searchArray as $key => $search) {
        $stmt->bindValue(':' . $key, $search, PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
    $stmt->execute();
    $empRecords = $stmt->fetchAll();
    $data = array();

    foreach ($empRecords as $row) {
        if ($_POST['sub_action'] === "GET_MASTER") {

            $FILE_UPLOAD1 = "";
            $FILE_UPLOAD2 = "";
            $FILE_UPLOAD3 = "";
            $FILE_UPLOAD4 = "";
            $FILE_UPLOAD5 = "";
            $FILE_UPLOAD6 = "";

            for ($i = 1; $i <= 6; $i++) {
                $fileKey = 'FILE_UPLOAD' . $i;

                if (!empty($row[$fileKey])) {
                    $filePath = $row[$fileKey];
                    $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    // ตรวจสอบว่าไฟล์เป็นรูปภาพหรือไม่
                    if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                        ${$fileKey} = "<a href='upload_to_show.php?filename=" . urlencode($filePath) . "&id=" . $i . "' data-title='File " . $i . " Title' data-favicon='img/favicon.ico' class='open-window' target='_blank'>File" . $i . "</a>";
                    } else {
                        ${$fileKey} = "<a href='" . $row[$fileKey] . "' data-title='File " . $i . " Title' data-favicon='img/favicon.ico' class='open-window' target='_blank'>File" . $i . "</a>";
                    }
                } else {
                    ${$fileKey} = "-";
                }
            }

            $data[] = array(
                "id" => $row['id'],
                "DI_REF" => $row['DI_REF'],
                "DI_DATE" => substr($row['DI_DATE'], 0, 10),
                "ADDB_COMPANY" => $row['ADDB_COMPANY'],
                "ADDB_PHONE" => $row['ADDB_PHONE'],
                "CAR_NO" => $row['CAR_NO'],
                "REMARK" => $row['REMARK'],

                "FILE_UPLOAD1" => $FILE_UPLOAD1,
                "FILE_UPLOAD2" => $FILE_UPLOAD2,
                "FILE_UPLOAD3" => $FILE_UPLOAD3,
                "FILE_UPLOAD4" => $FILE_UPLOAD4,
                "FILE_UPLOAD5" => $FILE_UPLOAD5,
                "FILE_UPLOAD6" => $FILE_UPLOAD6,

                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "upload" => "<button type='button' name='upload' id='" . $row['id'] . "' class='btn btn-secondary btn-xs upload' data-toggle='tooltip' title='Upload'>Upload</button>",
                "picture" => "<img src = '" . $row['picture'] . "'  width='32' height='32' title='" . $row['id'] . "'>",
                "status" => $row['status'] === 'Active' ? "<div class='text-success'>" . $row['status'] . "</div>" : "<div class='text-muted'> " . $row['status'] . "</div>"
            );
        }
    }

## Response Return Value
    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
    );

    echo json_encode($response);

}