<?php
//$year = '2022';
$label1 = '';
$label2 = '';
$label3 = '';
$label4 = '';
$data1 = '';
$data2 = '';
$data3 = '';
$data4 = '';

$str_return = "[";

$sql_get = " SELECT BRANCH,DI_YEAR,DI_MONTH,sum(CAST(TRD_G_KEYIN AS DECIMAL(10,2))) as  TRD_G_KEYIN
 FROM ims_product_sale_sac 
 WHERE DI_YEAR = '" . $year . "'
 AND BRANCH = 'BTC'
 AND ICCAT_CODE <> '6SAC08'  AND (DT_DOCCODE <> 'IS' OR DT_DOCCODE <> 'IIS' OR DT_DOCCODE <> 'IC')
 GROUP BY DI_MONTH,BRANCH  
 ORDER BY BRANCH,CAST(DI_MONTH AS UNSIGNED) ";

$statement = $conn->query($sql_get);
$results = $statement->fetchAll(PDO::FETCH_ASSOC);


foreach ($results as $result) {
    if ($result['DI_MONTH'] == 12) {
        $str_return .= $result['TRD_G_KEYIN'];
    } else {
        $str_return .= $result['TRD_G_KEYIN'] . ",";
    }
}

$str_return .= "]";

$label1 = "BTC";
$data1 = $str_return;



