<?php
// ตั้งค่าตัวแปร
$host = '192.168.88.40';
$port = '3307';
$user = 'root';
$dbname = 'sac_data2';
$backupFile = 'D:\\DB\\backup_directory\\sac_data2_20240808_042028.sql';

// สร้างคำสั่ง mysql
$command = "mysql -h $host -P $port -u $user $dbname < \"$backupFile\"";

// เรียกใช้งานคำสั่ง
exec($command, $output, $returnVar);

// ตรวจสอบว่าการ Restore สำเร็จหรือไม่
if ($returnVar === 0) {
    echo "Restore successful!";
} else {
    echo "Restore failed!";
}