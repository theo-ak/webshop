<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    $_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$orders = selectAll($connection, 'orders');

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