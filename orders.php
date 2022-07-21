<?php

require_once 'common.php';

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
}

$items = selectAll($connection, 'products')->fetchAll();
$orders = selectAll($connection, 'orders')->fetchAll();
$order_items = selectAll($connection, 'order_items')->fetchAll();

?>

<?php
require 'header.php'; ?>

<a href="products.php">
    <button type="button" class="btn btn-primary mx-2 my-2"><?= translate('Products page') ?></button>
</a>

<a href="index.php">
    <button type="button" class="btn btn-primary mx-2 my-2"><?= translate('Index page') ?></button>
</a>

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
                    <button type="button" class="btn btn-primary"><?= translate('View order'); ?></button>
                </a></td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>
</table>

<?php
require 'footer.php'; ?>