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
            "ADDB_COMPANY" => $result['ADDB_COMPANY'],
            "ADDB_PHONE" => $result['ADDB_PHONE'],
            "CAR_NO" => $result['CAR_NO']);
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

if ($_POST["action"] === 'UPDATE') {

    if ($_POST["product_id"] != '') {

        $id = $_POST["id"];
        $product_id = $_POST["product_id"];
        $name_t = $_POST["name_t"];
        $quantity = $_POST["quantity"];
        $status = $_POST["status"];
        $pgroup_id = $_POST["pgroup_id"];
        $brand_id = $_POST["brand_id"];
        $unit_id = $_POST["unit_id"];
        $picture = "product-001.png";
        $sql_find = "SELECT * FROM ims_document_customer_service WHERE product_id = '" . $product_id . "'";
        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {
            $sql_update = "UPDATE ims_document_customer_service SET name_t=:name_t,quantity=:quantity,status=:status
            ,pgroup_id=:pgroup_id,brand_id=:brand_id,unit_id=:unit_id,picture=:picture
            WHERE id = :id";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':name_t', $name_t, PDO::PARAM_STR);
            $query->bindParam(':quantity', $quantity, PDO::PARAM_STR);
            $query->bindParam(':pgroup_id', $pgroup_id, PDO::PARAM_STR);
            $query->bindParam(':brand_id', $brand_id, PDO::PARAM_STR);
            $query->bindParam(':unit_id', $unit_id, PDO::PARAM_STR);
            $query->bindParam(':picture', $picture, PDO::PARAM_STR);
            $query->bindParam(':status', $status, PDO::PARAM_STR);
            $query->bindParam(':id', $id, PDO::PARAM_STR);
            $query->execute();
            echo $save_success;
        }

    }
}

if ($_POST["action"] === 'DELETE') {

    $id = $_POST["id"];

    $sql_find = "SELECT * FROM ims_document_customer_service WHERE id = " . $id;
    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {
        try {
            $sql = "DELETE FROM ims_document_customer_service WHERE id = " . $id;
            $query = $conn->prepare($sql);
            $query->execute();
            Reorder_Record($conn, "ims_document_customer_service");
            echo $del_success;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
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
        CAR_NO LIKE :CAR_NO ) ";
        $searchArray = array(
            'DI_REF' => "%$searchValue%",
            'ADDB_COMPANY' => "%$searchValue%",
            'ADDB_PHONE' => "%$searchValue%",
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
        . " ORDER BY " . $columnName . " " . $columnSortOrder . " LIMIT :limit,:offset");

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

            if ($row['FILE_UPLOAD']!==null && $row['FILE_UPLOAD']!=="") {
                $FILE_UPLOAD = "<a href='" . $row['FILE_UPLOAD'] . "' target='_blank'>Download File</a>";
            } else {
                $FILE_UPLOAD = "-";
            }

            $data[] = array(
                "id" => $row['id'],
                "DI_REF" => $row['DI_REF'],
                "DI_DATE" => $row['DI_DATE'],
                "ADDB_COMPANY" => $row['ADDB_COMPANY'],
                "ADDB_PHONE" => $row['ADDB_PHONE'],
                "CAR_NO" => $row['CAR_NO'],
                "FILE_UPLOAD" => $FILE_UPLOAD,
                "update" => "<button type='button' name='update' id='" . $row['id'] . "' class='btn btn-info btn-xs update' data-toggle='tooltip' title='Update'>Update</button>",
                "upload" => "<button type='button' name='upload' id='" . $row['id'] . "' class='btn btn-secondary btn-xs upload' data-toggle='tooltip' title='Upload'>Upload File</button>",
                "picture" => "<img src = '" . $row['picture'] . "'  width='32' height='32' title='" . $row['name_t'] . "'>",
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