<?php

// Database configuration
$dbHost     = "192.168.88.7";
//$dbHost     = "localhost";
$dbPort     = "3307";
$dbUsername = "myadmin";
$dbPassword = "myadmin";
$dbName     = "sac_data2";

// Create database connection
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName, $dbPort);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
