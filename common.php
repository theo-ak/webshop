<?php

require_once 'config.php';

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
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

define('TRANSLATIONS', [
    'add' => 'adauga',
    'title' => 'titlu',
    'description' => 'descriere',
    'price' => 'pret',
    'name' => 'nume',
    'contact details' => 'date de contact',
    'comments' => 'comentarii',
    'items' => 'produse',
    'total' => 'total',
    'date' => 'data',
    'admin page' => 'pagina admin',
    'go to cart' => 'cart',
    'orders page' => 'pagina comenzi',
    'add product' => 'adauga produs',
    'go to index' => 'index',
    'remove' => 'sterge',
    'index page' => 'index',
    'edit' => 'editeaza',
    'delete' => 'sterge',
    'save' => 'salveaza',
    'back to products page' => 'pagina produse',
    'select image to upload' => 'selecteaza imaginea',
    'current image' => 'imaginea curenta',
    'view order' => 'vizualizeaza comanda'
]);

function translate($label)
{
    $key = strtolower($label);
    if (LANGUAGE == 'ro' && isset(TRANSLATIONS[$key])) {
        return ucfirst(TRANSLATIONS[$key]);
    } else {
        return ucfirst($key);
    }
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

