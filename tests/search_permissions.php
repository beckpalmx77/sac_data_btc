<?php
$dsn = 'mysql:host=192.168.88.7;port=3307;dbname=sac_data_btc';
$username = 'root';
$password = 'root007';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $stmt = $pdo->prepare('SELECT * FROM ims_permission WHERE permission_id LIKE :search OR permission_detail LIKE :search');
    $stmt->execute(['search' => "%$search%"]);
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = [];
    foreach ($permissions as $permission) {
        $results[] = ['id' => $permission['permission_id'], 'text' => $permission['permission_id']];
    }

    echo json_encode(['results' => $results]);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
