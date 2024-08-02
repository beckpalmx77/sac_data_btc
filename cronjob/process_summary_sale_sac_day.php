<?php

ini_set('display_errors', 1);
error_reporting(~0);

include("../config/connect_db.php");

//$year = 2024;
//$month = 1;

$year = date("Y");
$month = date("n");

//$year = 2024;
//$month = 1;

$str_insert = "OK Insert";
$str_update = "OK Update";

//echo $year . " - " . $month . " | " . $day . " Count <br>";

$branch = "BTC";

//for ($month = 1; $month <= 7; $month++) {

    $day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

    for ($day_loop = 1; $day_loop <= $day; $day_loop++) {

        $sql_find = "SELECT DI_DATE  FROM ims_product_sale_sac 
            WHERE DI_YEAR = '" . $year . "'
            AND DI_MONTH = '" . $month . "'
            AND BRANCH = '" . $branch . "'
            AND CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) = " . $day_loop . "
            AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
            GROUP BY DI_DATE";

        $nRows = $conn->query($sql_find)->fetchColumn();
        if ($nRows > 0) {

            $sql_get = " SELECT BRANCH,DI_DATE,DI_YEAR,DI_MONTH,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
                    FROM ims_product_sale_sac 
                    WHERE DI_YEAR = '" . $year . "'
                    AND DI_MONTH = '" . $month . "'
                    AND BRANCH = '" . $branch . "'
                    AND CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) = " . $day_loop . "
                    AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
                    GROUP BY DI_DATE,DI_MONTH,BRANCH  
                    ORDER BY CAST(SUBSTR(DI_DATE,1,2) AS UNSIGNED) ";

            $statement = $conn->query($sql_get);
            $results = $statement->fetchAll(PDO::FETCH_ASSOC);


            foreach ($results as $result) {

                echo $branch . " | " . $year . " | " . $month . " | " . $day_loop . " | " . $result['TRD_G_KEYIN'] . "\n\r";
                $total = $result['TRD_G_KEYIN'];

            }

        } else {

            echo $branch . " | " . $year . " | " . $month . " | " . $day_loop . " = 0 " . "\n\r";
            $total = "0.00";

        }

        $sql_find_data = "SELECT day  FROM ims_product_sale_sac_day 
            WHERE year = '" . $year . "'
            AND month = '" . $month . "'
            AND branch = '" . $branch . "'
            AND day = " . $day_loop;

        $nRows = $conn->query($sql_find_data)->fetchColumn();
        if ($nRows <= 0) {
            $sql_insert = "INSERT INTO ims_product_sale_sac_day(branch,day,month,year,total,remark) VALUES (:branch,:day,:month,:year,:total,:remark)";
            $query = $conn->prepare($sql_insert);
            $query->bindParam(':branch', $branch, PDO::PARAM_STR);
            $query->bindParam(':day', $day_loop, PDO::PARAM_STR);
            $query->bindParam(':month', $month, PDO::PARAM_STR);
            $query->bindParam(':year', $year, PDO::PARAM_STR);
            $query->bindParam(':total', $total, PDO::PARAM_STR);
            $query->bindParam(':remark', $str_insert, PDO::PARAM_STR);
            $query->execute();
            $lastInsertId = $conn->lastInsertId();
            if ($lastInsertId) {
                echo " | " . $str_insert . "\n\r";
            }
        } else {
            $sql_update = "UPDATE ims_product_sale_sac_day SET total=:total , remark=:remark               
            WHERE branch = :branch AND day = :day AND month = :month AND year = :year ";
            $query = $conn->prepare($sql_update);
            $query->bindParam(':total', $total, PDO::PARAM_STR);
            $query->bindParam(':remark', $str_update, PDO::PARAM_STR);
            $query->bindParam(':branch', $branch, PDO::PARAM_STR);
            $query->bindParam(':day', $day_loop, PDO::PARAM_STR);
            $query->bindParam(':month', $month, PDO::PARAM_STR);
            $query->bindParam(':year', $year, PDO::PARAM_STR);
            $query->execute();
            echo " | " . $str_update . "\n\r";
        }
    //}
}
