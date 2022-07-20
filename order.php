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

<?php
require 'header.php'; ?>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col"><?= translate('Date'); ?></th>
        <th scope="col"><?= translate('Name'); ?></th>
        <th scope="col"><?= translate('Contact details') ?></th>
        <th scope="col"><?= translate('Items'); ?></th>
        <th scope="col"><?= translate('Total'); ?></th>
        <th scope="col"><?= translate('Comments'); ?></th>
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
    <button type="button" class="btn btn-primary mx-2"><?= translate('Orders page'); ?></button>
</a>

<a href="products.php ">
    <button type="button" class="btn btn-primary mx-2"><?= translate('Products page'); ?></button>
</a>

<a href="index.php ">
    <button type="button" class="btn btn-primary mx-2"><?= translate('Index page'); ?></button>
</a>

<?php
require 'footer.php'; ?>
