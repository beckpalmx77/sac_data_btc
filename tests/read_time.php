<?php

// อ่านข้อมูลในไฟล์
$data = file_get_contents('myfile.txt');
// แยกข้อมูลออกเป็นแถว
$datas = explode("\n", $data);
// แยกข้อมูลออกเป็นคอลัมน์
foreach($datas AS $row) {
    $ds[] = explode(' ', trim($row)); // ใช้ trim() เพื่อป้องกันช่องว่างด้านหน้า และ ด้านหลังข้อมูล เช่น \r
}
// แสดงผลข้อมูลที่อ่านได้
print_r($ds);

$numtd = 1;

// read the file into an array
$data = file("myfile.txt", FILE_IGNORE_NEW_LINES );

// use trim to remove any spaces
$data = array_map("trim", $data);

// array_chunk to split it into parts, output with json_encode
echo json_encode( ["data" => array_chunk($data, $numtd)], JSON_PRETTY_PRINT );

$myJSONvar = json_encode( ["data" => array_chunk($data, $numtd)], JSON_PRETTY_PRINT );

file_put_contents("result_data.json", $myJSONvar);