<?php

require_once 'config.php';

try {
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" .  DBNAME,
        USERNAME,
        PASSWORD);
} catch(PDOException $err) {
    echo 'Connection failed: ' . $err->getMessage();
}

function selectAll($connection, $table) {
    $sql = "SELECT * FROM $table";
    $query = $connection->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}


