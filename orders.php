<?php

require_once 'common.php';

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
}

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

<a href="products.php">
    <button type="button" class="btn btn-primary mx-2 my-2">Products Page</button>
</a>

<a href="index.php">
    <button type="button" class="btn btn-primary mx-2 my-2">Index Page</button>
</a>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Date</th>
        <th scope="col">Name</th>
        <th scope="col">Contact details</th>
        <th scope="col">Items</th>
        <th scope="col">Total</th>
        <th scope="col">Comments</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($orders as $order): ?>
        <tr>
            <th scope="row"><?= $order['id']; ?></th>
            <td><?= $order['date']; ?></td>
            <td><?= $order['name']; ?></td>
            <td><?= $order['details']; ?></td>
            <?php

            $order_ids = array_filter($order_items, function ($var) use ($order) {
                return $var['order_id'] == $order['id'];
            });

            $title_ids = array_map(function ($var) {
                return $var['product_id'];
            }, $order_ids);

            $titles = '';
            $total = 0;

            foreach ($title_ids as $title_id) {
                foreach ($items as $item) {
                    if ($item['id'] == $title_id) {
                        $titles = $titles . $item['title'] . '<br>';
                        $total += $item['price'];
                    }
                }
            }

            ?>

            <td><?= $titles; ?></td>
            <td><?= $total; ?></td>
            <td><?= $order['comments']; ?></td>
            <td><a href="order.php?id=<?= $order['id']; ?>">
                    <button type="button" class="btn btn-primary">View order</button>
                </a></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>

</body>

</html>
