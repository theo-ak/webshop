<?php

require_once 'common.php';

if ($_SESSION['cart']) {
    $questionMarks = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
    $sql   = "SELECT * FROM products WHERE id IN ($questionMarks)";
    $query = $connection->prepare($sql);
    $query->execute($_SESSION['cart']);

    $items = $query->fetchAll();
}

?>

<html lang="en">

<p>Name: <?= $_SESSION['details']['name'] ?></p>
<p>Contact: <?= $_SESSION['details']['contact'] ?></p>
<p>Comments: <?= $_SESSION['details']['comments'] ?></p>

<p>Items ordered:</p>

<?php foreach ($items as $item): ?>
    <ul>
        <li><?= $item['title'] ?></li>
        <li><?= $item['description'] ?></li>
        <li><?= $item['price'] ?></li>
    </ul>
<?php endforeach; ?>

</html>
