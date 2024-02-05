<?php
$sql_data = " SELECT AR_CODE,SKU_CODE,SKU_NAME,TRD_U_PRC,
SUM(IF(DI_MONTH='1',TRD_QTY,0)) AS 1_QTY,
SUM(IF(DI_MONTH='1',TRD_G_KEYIN,0)) AS 1_AMT,
SUM(IF(DI_MONTH='2',TRD_QTY,0)) AS 2_QTY,
SUM(IF(DI_MONTH='2',TRD_G_KEYIN,0)) AS 2_AMT,
SUM(IF(DI_MONTH='3',TRD_QTY,0)) AS 3_QTY,
SUM(IF(DI_MONTH='3',TRD_G_KEYIN,0)) AS 3_AMT,
SUM(IF(DI_MONTH='4',TRD_QTY,0)) AS 4_QTY,
SUM(IF(DI_MONTH='4',TRD_G_KEYIN,0)) AS 4_AMT,
SUM(IF(DI_MONTH='5',TRD_QTY,0)) AS 5_QTY,
SUM(IF(DI_MONTH='5',TRD_G_KEYIN,0)) AS 5_AMT,
SUM(IF(DI_MONTH='6',TRD_QTY,0)) AS 6_QTY,
SUM(IF(DI_MONTH='6',TRD_G_KEYIN,0)) AS 6_AMT,
SUM(IF(DI_MONTH='7',TRD_QTY,0)) AS 7_QTY,
SUM(IF(DI_MONTH='7',TRD_G_KEYIN,0)) AS 7_AMT,
SUM(IF(DI_MONTH='8',TRD_QTY,0)) AS 8_QTY,
SUM(IF(DI_MONTH='8',TRD_G_KEYIN,0)) AS 8_AMT,
SUM(IF(DI_MONTH='9',TRD_QTY,0)) AS 9_QTY,
SUM(IF(DI_MONTH='9',TRD_G_KEYIN,0)) AS 9_AMT,
SUM(IF(DI_MONTH='10',TRD_QTY,0)) AS 10_QTY,
SUM(IF(DI_MONTH='10',TRD_G_KEYIN,0)) AS 10_AMT,
SUM(IF(DI_MONTH='11',TRD_QTY,0)) AS 11_QTY,
SUM(IF(DI_MONTH='11',TRD_G_KEYIN,0)) AS 11_AMT,
SUM(IF(DI_MONTH='12',TRD_QTY,0)) AS 12_QTY,
SUM(IF(DI_MONTH='12',TRD_G_KEYIN,0)) AS 12_AMT

FROM ims_product_sale_sac 
WHERE AR_CODE = '" . $customer_id . "'"
    . " AND DI_YEAR = '" . $year . "'"
    . " GROUP BY SKU_CODE,SKU_NAME,DI_MONTH,DI_YEAR,TRD_U_PRC
ORDER BY SKU_CODE ,  CONVERT(DI_YEAR, FLOAT),CONVERT(DI_MONTH, FLOAT) ";

//$myfile = fopen("param_post_sql.txt", "w") or die("Unable to open file!");
//fwrite($myfile, $sql_data);
//fclose($myfile);

$statement_data = $conn->query($sql_data);
$results_data = $statement_data->fetchAll(PDO::FETCH_ASSOC);

//foreach ($results_data as $row_data) { ?>

//}

?>

