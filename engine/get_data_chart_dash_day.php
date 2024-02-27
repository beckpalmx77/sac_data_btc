<?php


//$end_day = cal_days_in_month(CAL_GREGORIAN, $month, $year);

//$end_day = "15";

$current_day = date("j");

//$current_day = 31;

$label1 = '';
$label2 = '';
$label3 = '';
$label4 = '';
$data1 = '';
$data2 = '';
$data3 = '';
$data4 = '';

$str_labels_return = "[";

for ($c_day_loop = 1; $c_day_loop <= $current_day; $c_day_loop++) {

    if ($c_day_loop === $current_day) {
        $str_labels_return .= $c_day_loop;
    } else {
        $str_labels_return .= $c_day_loop . ",";
    }

}

$str_labels_return .= "]";

$branch = "BTC";


for ($day_loop = 1; $day_loop <= $current_day; $day_loop++) {

    $str_return = "[";

    $sql_get = "SELECT *  FROM ims_product_sale_sac_day 
        WHERE year = '" . $year . "' AND month = '" . $month . "' AND BRANCH = '" . $branch . "'                  
        ORDER BY CAST(day AS UNSIGNED) ";

/*
$myfile = fopen("sql_get.txt", "w") or die("Unable to open file!");
fwrite($myfile, $sql_get);
fclose($myfile);
*/


    $statement = $conn->query($sql_get);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($results as $result) {
        if ((int)$result['day'] === $current_day) {
            $str_return .= $result['total'];
        } else {
            $str_return .= $result['total'] . ",";
        }
    }

    $str_return .= "]";

    $label4 = "BTC";
    $data4 = $str_return;

}

$labels = $str_labels_return;