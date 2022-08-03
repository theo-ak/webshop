<?php

require_once 'common.php';

$items = [];

if (isset($_POST['id'])) {
    $index = array_search($_POST['id'], $_SESSION['cart']);
    array_splice($_SESSION['cart'], $index, 1);
    header('Location: cart.php');
    exit;
}

if ($_SESSION['cart']) {
    $questionMarks = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
    $sql   = "SELECT * FROM products WHERE id IN ($questionMarks)";
    $query = $connection->prepare($sql);
    $query->execute($_SESSION['cart']);

    $items = $query->fetchAll();

    $total = (array_sum(array_column($items, 'price')));
}

$details = [
    'name' => '',
    'contact' => '',
    'comments' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $details = [
        'name' => $_POST['name'] ?? '',
        'contact' => $_POST['contact'] ?? '',
        'comments' => $_POST['comments'] ?? ''
    ];

    if (isValid($details['name']) &&
        $details['contact']
    ) {
        $details['date'] = date('Y-m-d h:i:s');

        $sql = 'INSERT INTO orders (date, name, details, comments, total) 
                VALUES (?, ?, ?, ?, ?)';
        $query = $connection->prepare($sql);
        $query->execute([
            $details['date'],
            $details['name'],
            $details['contact'],
            $details['comments'],
            $total
        ]);

        $lastId = $connection->lastInsertId();

        $sql = 'INSERT INTO order_items (order_id, product_id) VALUES (?, ?)';

        foreach ($_SESSION['cart'] as $itemId) {
            $query = $connection->prepare($sql);
            $query->execute([
                $lastId,
                $itemId
            ]);
        }

        ob_start();

        include 'mail_template.php';
        $message = ob_get_clean();

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=iso-8859-1',
            'From: <dummy_email@provider.eu>'
        ];

        mail(MANAGER, translate('New order'), $message, implode("\r\n", $headers));

        $_SESSION['cart'] = [];

        header('Location: index.php');
        exit;
    }

    $errors = [
        'name' => isset($details['name']) ?
            translate('Name should not be empty') :
            '',
        'contact' => isset($details['contact']) ?
            translate('Contact should not be empty') :
            ''
    ];

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
    <?php foreach ($items as $item): ?>
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
    <?php endforeach; ?>
    </tbody>
</table>

<form method="post" id="details-form" action="cart.php">
    <div class="form-group">
        <label for="name"><?= translate('Name'); ?></label>
        <input type="text" class="form-control" id="name" name="name" placeholder="<?= translate('Enter name'); ?>"
               value="<?= $details['name'] ?>">
        <span id="name_err"></span>

        <?php if (isset($errors['name'])): ?>
        <span><?= $errors['name']; ?></span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="contact"><?= translate('Contact details'); ?></label>
        <input type="text" class="form-control" id="contact" name="contact" placeholder="<?= translate('Enter contact details'); ?>"
               value="<?= $details['contact'] ?>">

        <?php if (isset($errors['contact'])): ?>
            <span><?= $errors['contact']; ?></span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="comments"><?= translate('Comments'); ?></label>
        <input type="text" class="form-control" id="comments" name="comments" placeholder="<?= translate('Enter comments'); ?>"
               value="<?= $details['comments'] ?>">
    </div>
    <button type="submit" class="btn btn-primary" id="checkout-btn"><?= translate('Checkout'); ?></button>

</form>

<a href="index.php ">
    <button type="button" class="btn btn-primary"><?= translate('Go to index'); ?></button>
</a>

<script>
    $(document).ready(function () {
        $('#name').keyup(function () {
            var regex = /^[a-zA-Z\s]*$/;
            if (!regex.test($('#name').val())) {
                $('#name_err').html('Invalid characters in name field');
                $('#checkout-btn').prop('disabled', true);
            } else {
                $('#name_err').html('');
                $('#checkout-btn').prop('disabled', false);
            }
        });
    });
</script>

<?php require 'footer.php'; ?>
