<?php

require_once 'config.php';

try {
    $connection = new PDO("mysql:host=" . SERVER . ";dbname=" .  DBNAME,
        USERNAME,
        PASSWORD);

    echo "Connected succesfully.";
} catch(PDOException $err) {
    echo 'Connection failed: ' . $err->getMessage();
}

