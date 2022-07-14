<?php

require_once 'config.php';

session_start();

try {
    $connection = new PDO(
        "mysql:host=" . SERVER . ";dbname=" . DBNAME,
        USERNAME,
        PASSWORD
    );
} catch (PDOException $err) {
    echo 'Connection failed: ' . $err->getMessage();
}

function selectAll($connection, $table)
{
    $sql = "SELECT * FROM $table";
    $query = $connection->prepare($sql);
    $query->execute();
    return $query->fetchAll();
}

function selectById($connection, $table, $id_from_table, $id)
{
    $sql = "SELECT * FROM $table WHERE $id_from_table = :id";
    $query = $connection->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    return $query;
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = strip_tags($data);
    return $data;
}

