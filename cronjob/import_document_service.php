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
    DOCINFO
    INNER JOIN ARDETAIL ON DOCINFO.DI_KEY = ARDETAIL.ARD_DI
    INNER JOIN ARADDRESS ON ARDETAIL.ARD_AR = ARADDRESS.ARA_AR
    INNER JOIN ADDRBOOK ON ARADDRESS.ARA_ADDB = ADDRBOOK.ADDB_KEY
WHERE
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

$select_query_daily_cond = " AND DOCINFO.DI_DATE BETWEEN '" . date("Y/m/d", strtotime("yesterday")) . "' AND '" . date("Y/m/d") . "'";

$sql_sqlsvr = $sql_query_data . $select_query_daily_cond . $sql_group . " ORDER BY DOCINFO.DI_REF ";

$stmt_sqlsvr = $conn_sqlsvr->prepare($sql_sqlsvr);
$stmt_sqlsvr->execute();

$results_sqlsvr = $stmt_sqlsvr->fetchAll(PDO::FETCH_ASSOC);

if (!empty($results_sqlsvr)) {
    // 1. Get all unique DI_REFs from SQL Server results
    $di_refs = array_filter(array_unique(array_column($results_sqlsvr, 'DI_REF')));
    
    // 2. Pre-fetch existing DI_REFs from MySQL in chunks
    $existing_refs = array();
    if (!empty($di_refs)) {
        $chunks = array_chunk($di_refs, 500);
        foreach ($chunks as $chunk) {
            $placeholders = implode(',', array_fill(0, count($chunk), '?'));
            $stmt_check = $conn->prepare("SELECT DI_REF FROM ims_document_customer_service WHERE DI_REF IN ($placeholders)");
            $stmt_check->execute($chunk);
            while ($ref = $stmt_check->fetchColumn()) {
                $existing_refs[$ref] = true;
            }
        }
    }

    // 3. Prepare MySQL Insert statement once outside loop
    $sql_insert = "INSERT INTO ims_document_customer_service (DI_REF, DI_DATE, ADDB_KEY, ADDB_COMPANY, ADDB_PHONE, CAR_NO, DI_DAY, DI_MONTH, DI_YEAR)
                   VALUES (:DI_REF, :DI_DATE, :ADDB_KEY, :ADDB_COMPANY, :ADDB_PHONE, :CAR_NO, :DI_DAY, :DI_MONTH, :DI_YEAR)";
    $stmt_insert = $conn->prepare($sql_insert);

    // 4. Wrap database operations in a transaction for maximum speed
    $conn->beginTransaction();

    try {
        foreach ($results_sqlsvr as $result_sqlsvr) {
            $di_ref = $result_sqlsvr["DI_REF"];

            if (isset($existing_refs[$di_ref])) {
                $sql_update = " UPDATE ims_document_customer_service SET CAR_NO=:CAR_NO , DI_DATE=:DI_DATE WHERE DI_REF = :DI_REF ";
                /*
                $query = $conn->prepare($sql_update);
                $query->bindParam(':CAR_NO', $result_sqlsvr["ADDB_SEARCH"], PDO::PARAM_STR);
                $query->bindParam(':DI_DATE', $result_sqlsvr["DI_DATE"], PDO::PARAM_STR);
                $query->bindParam(':DI_REF', $result_sqlsvr["DI_REF"], PDO::PARAM_STR);
                $query->execute();
                */

                $update_data = $result_sqlsvr["DI_DATE"] . " : " . $result_sqlsvr["DI_REF"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";
                echo "UPDATE DATA " . $update_data;
            } else {
                $stmt_insert->execute([
                    ':DI_REF'       => $result_sqlsvr["DI_REF"],
                    ':DI_DATE'      => $result_sqlsvr["DI_DATE"],
                    ':ADDB_KEY'     => $result_sqlsvr["ADDB_KEY"],
                    ':ADDB_COMPANY'  => $result_sqlsvr["ADDB_COMPANY"],
                    ':ADDB_PHONE'    => $result_sqlsvr["ADDB_PHONE"],
                    ':CAR_NO'       => $result_sqlsvr["ADDB_SEARCH"],
                    ':DI_DAY'       => $result_sqlsvr["DI_DAY"],
                    ':DI_MONTH'     => $result_sqlsvr["DI_MONTH"],
                    ':DI_YEAR'      => $result_sqlsvr["DI_YEAR"]
                ]);

                // Track newly inserted ref so duplicates in same batch are recognized
                $existing_refs[$di_ref] = true;

                $insert_data = $result_sqlsvr["DI_DATE"] . " : " . $result_sqlsvr["DI_REF"] . " | " . $result_sqlsvr["ADDB_SEARCH"] . "\n\r";
                echo "INSERT DATA " . $insert_data;
            }
        }
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}

$conn_sqlsvr = null;