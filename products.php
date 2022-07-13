<?php

require_once 'common.php';

$items = selectAll($connection, 'products');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)test_input($_POST['id']);

    if ($id) {
        $sql = 'DELETE FROM products WHERE id=:id';
        $query = $connection->prepare($sql);
        $query->execute([
                'id' => $id
        ]);

        header('Location: products.php');
    }
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

    <a href="product.php "> <button type="button" class="btn btn-primary" id="add-product-btn">Add Product</button></a>

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
            <tr>
                <th scope="row"><?= $item['id']; ?></th>
                <td><?= $item['title']; ?></td>
                <td><?= $item['description']; ?></td>
                <td><?= $item['price']; ?></td>
                <td><div class="image"><img src="<?= $item['img']; ?>"></div></td>
                <td>
                    <a href="product.php?id=<?= $item['id'] ?>"><button class="btn btn-primary" id="edit">Edit</button></a>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="id" value="<?= $item['id']; ?>">
                        <input type="submit" value="Delete" class="btn btn-primary" id="delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>

</body>

</html>
