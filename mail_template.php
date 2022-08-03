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

<p>Name: <?= $details['name'] ?></p>
<p>Contact: <?= $details['contact'] ?></p>
<p>Comments: <?= $details['comments'] ?></p>

<p>Items ordered:</p>

<?php foreach ($items as $item): ?>
<ul>
    <li><?= $item['title'] ?></li>
    <li><?= $item['description'] ?></li>
    <li><?= $item['price'] ?></li>
</ul>
<?php endforeach; ?>

</html>
