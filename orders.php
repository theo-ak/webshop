<?php

require_once 'common.php';

$items = selectAll($connection, 'products');
$orders = selectAll($connection, 'orders');
$order_items = selectAll($connection, 'order_items');

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
        <th scope="col">Date</th>
        <th scope="col">Contact details</th>
        <th scope="col">Items</th>
        <th scope="col">Comments</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($orders as $order): ?>
        <tr>
            <th scope="row"><?= $order['id']; ?></th>
            <td><?= $order['date']; ?></td>
            <td><?= $order['details']; ?></td>
            <td><?php  ?></td>
            <td><?= $order['comments']; ?></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>

<a href="cart.php ">
    <button type="button" class="btn btn-primary">Go to cart</button>
</a>

</body>

</html>
