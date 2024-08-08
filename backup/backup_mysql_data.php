<?php
// ตั้งค่าตัวแปร
$host = '192.168.88.7';
$port = '3307';
$dbname = 'sac_data2';
$backupDir = "D:\DB\backup_directory";
$date = date('Ymd_His');
$backupFile = $backupDir . DIRECTORY_SEPARATOR . $dbname . "_" . $date . ".sql";

// สร้างคำสั่ง mysqldump
$command = "mysqldump --defaults-file=\"D:\\wamp64\\bin\\mysql\\mysql8.3.0\\my.ini\" -h $host -P $port $dbname > \"$backupFile\"";

// เรียกใช้งานคำสั่ง
exec($command, $output, $returnVar);

// ตรวจสอบว่าการสำรองข้อมูลสำเร็จหรือไม่
if ($returnVar === 0) {
    echo "Backup successful! Backup file is located at $backupFile";
} else {
    echo "Backup failed!";
}

