<?php

require_once 'common.php';

$items = selectAll($connection, 'products');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = array_search($_POST['id'], $_SESSION['cart']);
    array_splice($_SESSION['cart'], $index, 1);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Music Shop</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

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

    <?php foreach ($items as $item): ?>
        <?php if(in_array($item['id'], $_SESSION['cart'])): ?>
            <tr>
                <th scope="row"><?= $item['id']; ?></th>
                <td><?= $item['title']; ?></td>
                <td><?= $item['description']; ?></td>
                <td><?= $item['price']; ?></td>
                <td><img src="<?= $item['img']; ?>"></td>
                <td>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="id" value="<?= $item['id']; ?>">
                        <input type="submit" value="Remove" class="btn btn-primary">
                    </form>
                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="index.php "> <button type="button" class="btn btn-primary">Go to index</button></a>

</body>

</html>
