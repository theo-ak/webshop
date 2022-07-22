<?php

require_once 'config.php';
require_once 'translations.php';

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (!isset($_SESSION['admin_logged_in'])) {
    $_SESSION['admin_logged_in'] = false;
}

try {
    $connection = new PDO(
        "mysql:host=" . SERVER . ";dbname=" . DBNAME,
        USERNAME,
        PASSWORD
    );
} catch (PDOException $err) {
    exit;
}

define('LANGUAGE', substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2));

function translate($label)
{
    if (LANGUAGE == 'ro' && isset(TRANSLATIONS[$label])) {
        return TRANSLATIONS[$label];
    } else {
        return $label;
    }
}

function selectAll($connection, $table)
{
    $sql = "SELECT * FROM $table";
    $query = $connection->prepare($sql);
    $query->execute();
    return $query;
}

function selectById(
    $connection,
    $table,
    $id_from_table,
    $id)
{
    $sql = "SELECT * FROM $table WHERE $id_from_table = :id";
    $query = $connection->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    return $query;
}

function testInput($data)
{
    return htmlentities(
        strip_tags($data)
    );
}

function isValid($name)
{
    if(!$name) {
        return false;
    }
    if (preg_match('/[^A-Za-z]/', $name)) {
        return false;
    }
    return true;
}