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
    $name = isset($_POST['name']) ? testInput($_POST['name']) : "";
    $contact = isset($_POST['contact']) ? testInput($_POST['contact']) : "";
    $comments = isset($_POST['comments']) ? testInput($_POST['comments']) : "";

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

<?php
require 'header.php'; ?>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col"><?= translate('Title') ?></th>
        <th scope="col"><?= translate('Description') ?></th>
        <th scope="col"><?= translate('Price') ?></th>
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
                    <input type="submit" value="<?= translate('Remove'); ?>" class="btn btn-primary">
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
        <label for="name"><?= translate('Name'); ?></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?= $name ?>"
               required>
    </div>
    <div class="form-group">
        <label for="contact"><?= translate('Contact details'); ?></label>
        <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter contact details"
               value="<?= $contact ?>" required>
    </div>
    <div class="form-group">
        <label for="comments"><?= translate('Comments'); ?></label>
        <input type="text" class="form-control" id="comments" name="comments" placeholder="Enter comments"
               value="<?= $comments ?>">
    </div>
    <button type="submit" class="btn btn-primary">Checkout</button>
</form>

<a href="index.php ">
    <button type="button" class="btn btn-primary"><?= translate('Go to index'); ?></button>
</a>

<?php
require 'footer.php'; ?>
