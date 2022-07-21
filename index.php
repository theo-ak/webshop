<?php

require_once 'common.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (!$id) {
        http_response_code(400);
        exit;
    }

    if (selectById($connection, 'products', 'id', $id)->fetch() && !in_array($id, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $id;
    }
}


$cart_str = implode(',', $_SESSION['cart']);

if ($cart_str) {
    $sql = "SELECT * FROM products WHERE id NOT IN ($cart_str)";
    $query = $connection->prepare($sql);
    $query->execute();
    $items = $query->fetchAll();
} else {
    $items = selectAll($connection, 'products')->fetchAll();
}

?>

<?php
require 'header.php'; ?>

    <a href="products.php">
        <button type="button" class="btn btn-primary mx-2 my-2"><?= translate('Admin page'); ?></button>
    </a>

    <a href="cart.php ">
        <button type="button" class="btn btn-primary mx-2 my-2"><?= translate('Go to cart'); ?></button>
    </a>

    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col"><?= translate('Title'); ?></th>
            <th scope="col"><?= translate('Description'); ?></th>
            <th scope="col"><?= translate('Price'); ?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($items as $item): ?>
            <tr>
                <th scope="row"><?= $item['id']; ?></th>
                <td><?= $item['title']; ?></td>
                <td><?= $item['description']; ?></td>
                <td><?= $item['price']; ?></td>
                <td>
                    <div class="image"><img src="<?= $item['img']; ?>"></div>
                </td>
                <td>
                    <form method="post" action="index.php">
                        <input type="hidden" name="id" value="<?= $item['id']; ?>">
                        <button type="submit" class="btn btn-primary"><?= translate('Add'); ?></button>
                    </form>
                </td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>

<?php
require 'footer.php'; ?>