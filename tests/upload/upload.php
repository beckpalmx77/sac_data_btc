<?php
// upload.php
$host = '192.168.88.7';
$db = 'sac_test';
$user = 'myadmin';
$pass = 'myadmin';
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if files were uploaded
    if (isset($_FILES['files']) && $_FILES['files']['error'][0] != UPLOAD_ERR_NO_FILE) {
        $fileCount = count($_FILES['files']['name']);
        if ($fileCount > 5) {
            echo "You can only upload up to 5 files.";
            exit;
        }

        $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf']; // allowed file types
        $uploadDir = 'uploads/'; // directory to save uploaded files

        // Create the upload directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Prepare SQL statement to insert file names
        $stmt = $pdo->prepare("
            INSERT INTO files (file1, file2, file3, file4, file5) 
            VALUES (:file1, :file2, :file3, :file4, :file5)
        ");

        $fileNames = ['file1' => null, 'file2' => null, 'file3' => null, 'file4' => null, 'file5' => null];

        for ($i = 0; $i < $fileCount; $i++) {
            $fileTmpName = $_FILES['files']['tmp_name'][$i];
            $fileName = basename($_FILES['files']['name'][$i]);
            $fileType = $_FILES['files']['type'][$i];

            // Validate file type
            if (in_array($fileType, $allowedFileTypes)) {
                $uploadFilePath = $uploadDir . $fileName;

                // Move the uploaded file to the upload directory
                if (move_uploaded_file($fileTmpName, $uploadFilePath)) {
                    $fileKey = 'file' . ($i + 1);
                    $fileNames[$fileKey] = $fileName;
                } else {
                    echo "Failed to move uploaded file: $fileName<br>";
                }
            } else {
                echo "Invalid file type: $fileName<br>";
            }
        }

        // Execute the SQL statement to insert file names
        $stmt->execute($fileNames);

        echo "Files uploaded and saved successfully.";
    } else {
        echo "No files were uploaded.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
