<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

$items = selectAll($connection, 'products');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = testInput($_POST['id']);

    if ($id && selectByIds($connection, 'products', 'id', $id)) {
        $sql = 'DELETE FROM products WHERE id = ?';
        $query = $connection->prepare($sql);
        $query->execute([$id]);

        header('Location: products.php');
        exit;
    }
}

?>

<?php require 'header.php'; ?>

<a href="product.php ">
    <button type="button" class="btn btn-primary mx-2 my-2"><?= translate('Add product'); ?></button>
</a>

<a href="orders.php">
    <button class="btn btn-primary mx-2 my-2"><?= translate('Orders page'); ?></button>
</a>

<a href="index.php">
    <button class="btn btn-primary mx-2 my-2"><?= translate('Index page') ?></button>
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

    <?php foreach ($items as $item): ?>
    <tr>
        <th scope="row"><?= $item['id']; ?></th>
        <td><?= $item['title']; ?></td>
        <td><?= $item['description']; ?></td>
        <td><?= $item['price']; ?></td>
        <td>
            <div class="image"><img src="<?= $item['img']; ?>"></div>
        </td>
        <td>
            <a href="product.php?id=<?= $item['id'] ?>">
                <button class="btn btn-primary mt-2"><?= translate('Edit'); ?></button>
            </a>

            <form method="post" action="products.php">
                <input type="hidden" name="id" value="<?= $item['id']; ?>">
                <input type="submit" value="<?= translate('Delete'); ?>" class="btn btn-primary mt-2">
            </form>
        </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<?php require 'footer.php'; ?>
