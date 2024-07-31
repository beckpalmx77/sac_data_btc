<?php
include '../config/connect_db_btc.php';

    $stmt = $conn_btc->query('SELECT * FROM ims_permission');
    $permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($permissions);
