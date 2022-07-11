<?php

require_once 'common.php';

$items = selectAll($connection, 'products');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Music Shop</title>
    <!-- JavaScript Bundle with Popper -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
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
            <?php if(!in_array($item['id'], $_SESSION['cart'])): ?>
                <tr>
                    <th scope="row"><?= $item['id']; ?></th>
                    <td><?= $item['title']; ?></td>
                    <td><?= $item['description']; ?></td>
                    <td><?= $item['price']; ?></td>
                    <td>
                        <form action="">
                            <input type="hidden" name="id" value="<?= $item['id']; ?>">
                            <input type="submit" value="Add" class="btn btn-primary">
                        </form>
                    </td>
                </tr>
            <?php endif; ?>

        <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>