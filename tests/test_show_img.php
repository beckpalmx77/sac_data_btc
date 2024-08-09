<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูลด้วย PDO บนพอร์ต 3307
$host = 'localhost';
$db = 'sac_data2';
$user = 'myadmin';
$pass = 'myadmin';
$charset = 'utf8mb4';
$port = 3307;

$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// ดึงข้อมูลจากฐานข้อมูลเพื่อแสดงภาพ
$yourRecordId = 1019; // ระบุ ID ของ record ที่ต้องการดึงภาพ
$query = "SELECT * FROM ims_document_customer_service WHERE id = :record_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['record_id' => $yourRecordId]);
$row = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <!-- รวม Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- รวม jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- รวม Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .image-container {
            margin-bottom: 15px;
        }
        .image-item {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
        .delete-button {
            display: block;
            width: 100%;
        }
    </style>
    <script>
        function deleteImage(index) {
            // ลบภาพจาก DOM
            $('#image-container-' + index).remove();

            // เลื่อนลำดับภาพที่เหลือขึ้น
            for (let i = index + 1; i <= 5; i++) {
                let nextImageContainer = $('#image-container-' + i);
                if (nextImageContainer.length) {
                    let newIndex = i - 1;
                    nextImageContainer.attr('id', 'image-container-' + newIndex);
                    nextImageContainer.find('.delete-button').text('ลบไฟล์ ' + newIndex).attr('onclick', 'deleteImage(' + newIndex + ')');
                }
            }

            // อัปเดตฐานข้อมูลด้วย AJAX และอัปเดตชื่อไฟล์ตามลำดับใหม่
            $.ajax({
                url: 'your_current_page.php', // เปลี่ยนเป็นชื่อไฟล์ PHP ของคุณ
                type: 'POST',
                data: {
                    action: 'DELETE_IMAGE',
                    index: index,
                    record_id: <?= $yourRecordId ?>
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Image Gallery</h1>
    <div id="image-gallery" class="row">
        <?php
        $placeholderImage = 'img/file.jpg'; // กำหนดเส้นทางของภาพที่เตรียมไว้

        for ($i = 1; $i <= 5; $i++) {
            $fileKey = 'FILE_UPLOAD' . $i;
            if (!empty($row[$fileKey])) {
                $filePath = $row[$fileKey];

                // ตรวจสอบว่าไฟล์เป็นรูปภาพหรือไม่
                if (@getimagesize($filePath)) {
                    $imagePath = $filePath;
                } else {
                    $imagePath = $placeholderImage;
                }

                // อัปเดตชื่อไฟล์ตามลำดับใหม่
                $newFileName = "image_{$yourRecordId}_{$i}." . pathinfo($filePath, PATHINFO_EXTENSION);
                rename($filePath, "{$newFileName}");

                // อัปเดตชื่อไฟล์ในฐานข้อมูล
                $updateFileQuery = "UPDATE ims_document_customer_service SET FILE_UPLOAD{$i} = :newFileName WHERE id = :record_id";
                $updateStmt = $pdo->prepare($updateFileQuery);
                $updateStmt->execute(['newFileName' => "{$newFileName}", 'record_id' => $yourRecordId]);

                echo "
                    <div class='col-md-4 image-container' id='image-container-{$i}'>
                        <img src='{$newFileName}' alt='Image {$i}' class='image-item img-fluid'>
                        <button class='btn btn-danger delete-button' onclick='deleteImage({$i})'>ลบไฟล์ {$i}</button>
                    </div>";
            }
        }
        ?>
    </div>
</div>
</body>
</html>
