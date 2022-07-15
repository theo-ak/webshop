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
    $items = selectAll($connection, 'products');
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
    endforeach; ?>
    </tbody>
</table>

</body>

</html>