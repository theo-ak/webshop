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

$details = [
        'name' => '',
        'contact' => '',
        'comments' => ''
];

$errors = [
        'name' => '',
        'contact' => '',
        'comments' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $details['name'] = isset($_POST['name']) ? testInput($_POST['name']) : '';
    $details['contact'] = isset($_POST['contact']) ? testInput($_POST['contact']) : '';
    $details['comments'] = isset($_POST['comments']) ? testInput($_POST['comments']) : '';

    if ($details['name'] && $details['comments']) {
        $details['date'] = date('Y-m-d h:i:s');
        $item_ids = '';

        foreach ($items as $item) {
            if (in_array($item['id'], $_SESSION['cart'])) {
                $item_ids = $item_ids . ' ' . $item['id'];
            }
        }

        $sql = 'INSERT INTO orders (date, name, details, comments) VALUES (:date, :name, :details, :comments)';
        $query = $connection->prepare($sql);
        $query->execute([
            'date' => $details['date'],
            'name' => $details['name'],
            'details' => $details['contact'],
            'comments' => $details['comments']
        ]);

        $items_arr = explode(' ', $item_ids);
        $items_arr = array_filter($items_arr);

        $sql = 'SELECT id FROM orders WHERE date = :date';
        $query = $connection->prepare($sql);
        $query->execute([
                'date' => $details['date']
        ]);

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

        $to = MANAGER;

        $subject = 'New Order';

        $message = file_get_contents('html/mail_template.html');
        $item_list = file_get_contents('html/items_list.html');

        foreach ($details as $key => $value) {
            $message = str_replace("{{ $key }}", $value, $message);
        }

        foreach ($items as $item) {
            $message = $message . str_replace(
                    ["{{ title }}", "{{ description }}", "{{ price }}"],
                    [$item['title'], $item['description'], $item['price']],
                    $item_list
                );
        }

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';

        $headers[] = 'From: <dummy_email@provider.eu>';

        mail($to, $subject, $message, implode("\r\n", $headers));

        $_SESSION['cart'] = [];

        header('Location: index.php');
    }
}

require 'header.php';

?>

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
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="<?= $details['name'] ?>"
               required>
    </div>
    <div class="form-group">
        <label for="contact"><?= translate('Contact details'); ?></label>
        <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter contact details"
               value="<?= $details['contact'] ?>" required>
    </div>
    <div class="form-group">
        <label for="comments"><?= translate('Comments'); ?></label>
        <input type="text" class="form-control" id="comments" name="comments" placeholder="Enter comments"
               value="<?= $details['comments'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Checkout</button>
</form>

<a href="index.php ">
    <button type="button" class="btn btn-primary"><?= translate('Go to index'); ?></button>
</a>

<?php
require 'footer.php'; ?>
