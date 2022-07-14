<?php

require_once 'common.php';

$items = [];

if (isset($_POST['id'])) {
    $index = array_search($_POST['id'], $_SESSION['cart']);
    array_splice($_SESSION['cart'], $index, 1);
}

$cart_str = implode(',', $_SESSION['cart']);

if ($cart_str) {
    $sql = "SELECT * FROM products WHERE id IN ($cart_str)";
    $query = $connection->prepare($sql);
    $query->execute();
    $items = $query->fetchAll();
}

$name = $contact = $comments = "";
$nameErr = $contactErr = $commentsErr = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $name = isset($_POST['name']) ? test_input($_POST['name']) : "";
    $contact = isset($_POST['contact']) ? test_input($_POST['contact']) : "";
    $comments = isset($_POST['comments']) ? test_input($_POST['comments']) : "";

    if ($name && $contact) {
        $date = date("Y-m-d h:i:s");
        $item_ids = '';

        $to = MANAGER;

        $subject = "New Order";

        $message = '<p>Name: ' . $name . '</p>' .
            '<p>Contact: ' . $contact . '</p>' .
            '<p>Comments: ' . $comments . '</p>';

        foreach ($items as $item) {
            if (in_array($item['id'], $_SESSION['cart'])) {
                $item_ids = $item_ids . ' ' . $item['id'];
                $message = $message . '<p>' . $item['title'] .
                    '<br>' . $item['description'] .
                    '<br>' . $item['price'] .
                    '<br>' . '<img src="' . $item['img'] . '">' . '</p>';
            }
        }

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        $headers[] = 'From: <dummy_email@provider.eu>';

        mail($to, $subject, $message, implode("\r\n", $headers));

        $sql = 'INSERT INTO orders (date, name, details, comments) VALUES (:date, :name, :details, :comments)';
        $query = $connection->prepare($sql);
        $query->execute([
            'date' => $date,
            'name' => $name,
            'details' => $contact,
            'comments' => $comments
        ]);

        $items_arr = explode(' ', $item_ids);
        $items_arr = array_filter($items_arr);

        $sql = 'SELECT MAX(id) FROM orders';
        $query = $connection->prepare($sql);
        $query->execute();

        $order_arr = $query->fetch();
        $order_id = $order_arr[0];

        foreach ($items_arr as $item) {
            $sql = 'INSERT INTO order_items (order_id, product_id) VALUES (:order_id, :product_id)';
            $query = $connection->prepare($sql);
            $query->execute([
                'order_id' => (int)$order_id,
                'product_id' => (int)$item
            ]);
        }

        $_SESSION['cart'] = [];

        header('Location: index.php');
    }
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
                <div class="img"><img src="<?= $item['img']; ?>"></div>
            </td>
            <td>
                <form method="post" action="cart.php">
                    <input type="hidden" name="id" value="<?= $item['id']; ?>">
                    <input type="submit" value="Remove" class="btn btn-primary">
                </form>
            </td>
        </tr>
    <?php
    endforeach; ?>
    </tbody>

</table>

<form method="post" id="details-form" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?= $name ?>"
               required>
    </div>
    <div class="form-group">
        <label for="contact">Contact details</label>
        <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter contact details"
               value="<?= $contact ?>" required>
    </div>
    <div class="form-group">
        <label for="comments">Comments</label>
        <input type="text" class="form-control" id="comments" name="comments" placeholder="Enter comments"
               value="<?= $comments ?>">
    </div>
    <button type="submit" class="btn btn-primary">Checkout</button>
</form>

<a href="index.php ">
    <button type="button" class="btn btn-primary">Go to index</button>
</a>

</body>

</html>
