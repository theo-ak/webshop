<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    $_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ' . $_SESSION['rdrurl']);
    exit;
}

$order = selectById($connection, 'orders', 'id', $_GET['id']);

if (!$order) {
    http_response_code(404);
    exit;
}

$sql = 'SELECT products.id, products.title, order_items.order_id, order_items.product_price
    FROM products 
    INNER JOIN order_items 
    ON products.id = order_items.product_id 
    WHERE order_id = ?';
$query = $connection->prepare($sql);
$query->execute([$order['id']]);

$orderProducts = $query->fetchAll();

require 'header.php';

?>

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
        <?php foreach ($orderProducts as $orderProduct): ?>
            <p>
                <?= $orderProduct['title'] .
                ' - ' .
                $orderProduct['product_price']; ?>
            </p>
        <?php endforeach; ?>
    </td>
    <td><?= $order['total']; ?></td>
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

<?php require 'footer.php'; ?>