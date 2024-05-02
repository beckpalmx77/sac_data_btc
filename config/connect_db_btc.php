<?php
date_default_timezone_set("Asia/Bangkok");
include('db_value_btc.inc');

try
{
    $conn_btc = new PDO("mysql:host=".DB_HOST_BTC.";dbname=".DB_NAME_BTC.";port=" .DB_PORT_BTC,DB_USER_BTC, DB_PASS_BTC
        ,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    $conn_btc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
    echo "Error: " . $e->getMessage();
    exit("Error: " . $e->getMessage());
}