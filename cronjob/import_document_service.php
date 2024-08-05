<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_sqlserver.php");
include("../config/connect_db.php");
include('../util/month_util.php');

$sql_query_data = "SELECT 
    MAX(ADDRBOOK.ADDB_KEY) AS ADDB_KEY,
    MAX(ADDRBOOK.ADDB_BRANCH) AS ADDB_BRANCH,
    MAX(ADDRBOOK.ADDB_SEARCH) AS ADDB_SEARCH,
    MAX(ADDRBOOK.ADDB_ADDB_1) AS ADDB_ADDB_1,
    MAX(ADDRBOOK.ADDB_ADDB_2) AS ADDB_ADDB_2,
    MAX(ADDRBOOK.ADDB_ADDB_3) AS ADDB_ADDB_3,
    MAX(ADDRBOOK.ADDB_COMPANY) AS ADDB_COMPANY,
    MAX(ADDRBOOK.ADDB_PHONE) AS ADDB_PHONE,
    DOCINFO.DI_REF,
    MAX(DOCINFO.DI_DATE) AS DI_DATE,
    DAY(MAX(DOCINFO.DI_DATE)) AS DI_DAY,
    MONTH(MAX(DOCINFO.DI_DATE)) AS DI_MONTH,
    YEAR(MAX(DOCINFO.DI_DATE)) AS DI_YEAR
FROM 
    ADDRBOOK,
    ARADDRESS,
    ARDETAIL,
    DOCINFO
WHERE
    (ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB) AND 
    (ARDETAIL.ARD_AR = ARADDRESS.ARA_AR) AND 
    (DOCINFO.DI_KEY = ARDETAIL.ARD_DI) AND    
    (DOCINFO.DI_REF LIKE 'CCS6%' OR
     DOCINFO.DI_REF LIKE 'CCS7%' OR
     DOCINFO.DI_REF LIKE 'DDS5%' OR
     DOCINFO.DI_REF LIKE 'IC5%' OR
     DOCINFO.DI_REF LIKE 'IC6%' OR
     DOCINFO.DI_REF LIKE 'IIS5%' OR
     DOCINFO.DI_REF LIKE 'IIS6%' OR
     DOCINFO.DI_REF LIKE 'IV3%') ";

$sql_group = " GROUP BY DOCINFO.DI_DATE,DOCINFO.DI_REF";

echo "Today is " . date("Y/m/d");
echo "\n\r" . date("Y/m/d", strtotime("yesterday"));

//$select_query_daily_cond = " AND DI_DATE BETWEEN '2024/01/01' AND '" . date("Y/m/d") . "'";

$select_query_daily_cond = " AND DOCINFO.DI_DATE BETWEEN '" . date("Y/m/d", strtotime("yesterday")) . "' AND '" . date("Y/m/d") . "'";

$sql_sqlsvr = $sql_query_data . $select_query_daily_cond . $sql_group . " ORDER BY DOCINFO.DI_REF ";

$insert_data = "";
$update_data = "";

$res = "";
$txt = "";
$txt1 = "";
$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$return_arr = array();

while ($result_sqlsvr = $stmt_sqlsvr->fetch(PDO::FETCH_ASSOC)) {


    $sql_cust_string = "
        SELECT ADDRBOOK.ADDB_PHONE,ARADDRESS.ARA_ADDB
        FROM ARADDRESS
        LEFT JOIN ADDRBOOK ON ADDRBOOK.ADDB_KEY = ARADDRESS.ARA_ADDB
        WHERE ADDRBOOK.ADDB_COMPANY LIKE '%" . $result_sqlsvr['ADDB_COMPANY'] . "%' AND ARADDRESS.ARA_DEFAULT = 'Y' ";

    $sql_find = "SELECT * FROM ims_document_customer_service "
        . " WHERE DI_REF = '" . $result_sqlsvr["DI_REF"] . "'";

    $nRows = $conn->query($sql_find)->fetchColumn();
    if ($nRows > 0) {

        $sql_update = " UPDATE ims_document_customer_service  SET CAR_NO=:CAR_NO , DI_DATE=:DI_DATE               
        WHERE DI_REF  = :DI_REF ";

        $query = $conn->prepare($sql_update);
        $query->bindParam(':CAR_NO', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->execute();

        $update_data = $result_sqlsvr["DI_DATE"] . " : " . $result_sqlsvr["DI_REF"] . " | " . $result_sqlsvr["ADDB_BRANCH"] . "\n\r";
        echo "UPDATE DATA " . $update_data;

    } else {

        $sql = " INSERT INTO ims_document_customer_service (DI_REF,DI_DATE,ADDB_KEY,ADDB_COMPANY,ADDB_PHONE,CAR_NO,DI_DAY,DI_MONTH,DI_YEAR)
                 VALUES (:DI_REF,:DI_DATE,:ADDB_KEY,:ADDB_COMPANY,:ADDB_PHONE,:CAR_NO,:DI_DAY,:DI_MONTH,:DI_YEAR) ";
        $query = $conn->prepare($sql);
        $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
        $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_KEY', $result_sqlsvr["ADDB_KEY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_COMPANY', $result_sqlsvr["ADDB_COMPANY"], PDO::PARAM_STR);
        $query->bindParam(':ADDB_PHONE', $result_sqlsvr["ADDB_PHONE"], PDO::PARAM_STR);
        $query->bindParam(':CAR_NO', $result_sqlsvr["ADDB_BRANCH"], PDO::PARAM_STR);
        $query->bindParam(':DI_DAY', $result_sqlsvr["DI_DAY"], PDO::PARAM_STR);
        $query->bindParam(':DI_MONTH', $result_sqlsvr["DI_MONTH"], PDO::PARAM_STR);
        $query->bindParam(':DI_YEAR', $result_sqlsvr["DI_YEAR"], PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $conn->lastInsertId();
        if ($lastInsertId) {
            $insert_data = $result_sqlsvr["DI_DATE"] . " : " . $result_sqlsvr["DI_REF"] . " | " . $result_sqlsvr["ADDB_BRANCH"] . "\n\r";
            echo "INSERT DATA " . $insert_data;
        } else {
            echo " Error ";
        }

    }

}

$conn_sqlsvr = null;