<?php

require_once 'common.php';

$items = selectAll($connection, 'products');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['cart'][] = $_POST['id'];
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

<a href="products.php">
    <button type="button" class="btn btn-primary mx-2 my-2">Admin Page</button>
</a>

<a href="cart.php ">
    <button type="button" class="btn btn-primary mx-2 my-2">Go to cart</button>
</a>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">Description</th>
        <th scope="col">Price</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($items as $item): ?>
        <?php
        if (!in_array($item['id'], $_SESSION['cart'])): ?>
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
                        <button type="submit" class="btn btn-primary">Add</button>
                    </form>
                </td>
            </tr>
        <?php
        endif; ?>
    <?php
    endforeach; ?>
    </tbody>
</table>

</body>

</html>