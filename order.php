<?php

require_once 'common.php';

if (!isset($_GET['id'])) {
    header('location: ' . $_SESSION['rdrurl']);
}

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
}

$id = $_GET['id'];

$order = selectById($connection, 'orders', 'id', $id)->fetch();

$order_product_ids = selectById($connection, 'order_items', 'order_id', $id)->fetchAll();
$product_ids = array_map(function ($var) {
    return $var['product_id'];
}, $order_product_ids);

$product_titles = [];
$total = 0;

foreach ($product_ids as $product_id) {
    $product = selectById($connection, 'products', 'id', $product_id)->fetch();
    $total += $product['price'];
    $product_titles[] = $product['title'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Music Shop</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col"><?= translate('date'); ?></th>
        <th scope="col"><?= translate('name'); ?></th>
        <th scope="col"><?= translate('contact details') ?></th>
        <th scope="col"><?= translate('items'); ?></th>
        <th scope="col"><?= translate('total'); ?></th>
        <th scope="col"><?= translate('comments'); ?></th>
    </tr>
    </thead>
    <tbody>
    <th scope="row"><?= $order['id']; ?></th>
    <td><?= $order['date']; ?></td>
    <td><?= $order['name']; ?></td>
    <td><?= $order['details']; ?></td>
    <td>
        <?php
        foreach ($product_titles as $product_title): ?>
            <?= $product_title ?>
        <?php
        endforeach; ?>
    </td>
    <td><?= $total; ?></td>
    <td><?= $order['comments']; ?></td>
    </tbody>
</table>

<a href="orders.php ">
    <button type="button" class="btn btn-primary mx-2"><?= translate('orders page'); ?>></button>
</a>

<a href="products.php ">
    <button type="button" class="btn btn-primary mx-2"><?= translate('products page'); ?></button>
</a>

<a href="index.php ">
    <button type="button" class="btn btn-primary mx-2"><?= translate('index page'); ?></button>
</a>

</body>

</html>
