<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

$orders = selectAll($connection, 'orders');

$sql = 'SELECT products.id, products.title, order_items.order_id 
        FROM products 
        INNER JOIN order_items 
        ON products.id = order_items.product_id';
$query = $connection->prepare($sql);
$query->execute();

$titles = $query->fetchAll();

?>

<?php require 'header.php'; ?>

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
    <?php foreach ($orders as $order): ?>
        <tr>
            <th scope="row"><?= $order['id']; ?></th>
            <td><?= $order['date']; ?></td>
            <td><?= $order['name']; ?></td>
            <td><?= $order['details']; ?></td>
            <td>
                <?php foreach ($titles as $title): ?>
                <?php if ($title['order_id'] == $order['id']): ?>
                    <p><?= $title['title'] ?></p>
                <?php endif; ?>
                <?php endforeach; ?>
            </td>
            <td><?= $order['total']; ?></td>
            <td><?= $order['comments']; ?></td>
            <td>
                <a href="order.php?id=<?= $order['id']; ?>">
                    <button type="button" class="btn btn-primary"><?= translate('View order'); ?></button>
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php require 'footer.php'; ?>