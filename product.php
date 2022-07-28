<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
    exit;
}

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $item = selectById($connection, 'products', 'id', $_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'id' => testInput($_POST['id']) ?? '',
        'title' => testInput($_POST['title']) ?? '',
        'description' => testInput($_POST['description']) ?? '',
        'price' => testInput($_POST['price']) ?? '',
    ];

    if (isset($_FILES['fileToUpload'])) {
        $targetDir = 'img/';
        $extension = pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);
        $_FILES['fileToUpload']['name'] = date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . $extension;
        $img = $targetDir . basename($_FILES["fileToUpload"]["name"]);

        $fileType = mime_content_type($_FILES['fileToUpload']['tmp_name']);

        if (str_contains($fileType, 'image')) {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $img);
        }
    }

    if ($item['id']) {
        if ($item['title'] && $item['description'] && $item['price'] && $img) {
            $sql = 'UPDATE products SET title = ?, description = ?, price = ?, img = ? 
                    WHERE id = ?';
            $query = $connection->prepare($sql);
            $query->execute([
                $item['title'],
                $item['description'],
                $item['price'],
                $img,
                $item['id']
            ]);

            header('Location: product.php?id=' . $item['id']);
            exit;
        }
    } else {
        $sql = 'INSERT INTO products (title, description, price, img) VALUES (?, ?, ?, ?)';
        $query = $connection->prepare($sql);
        $query->execute([
            $item['title'],
            $item['description'],
            $item['price'],
            $img
        ]);

        header('Location: product.php?id=' . $connection->lastInsertId());
        exit;
    }
}

?>

<?php require 'header.php'; ?>

<form method="post" id="details-form" action="product.php" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"><?= translate('Title'); ?></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="<?= translate('Enter title'); ?>" value="<?= $item['title'] ?? ''; ?>"
               required>
    </div>
    <div class="form-group">
        <label for="description"><?= translate('Description'); ?></label>
        <input type="text" class="form-control" id="description" name="description" placeholder="<?= translate('Enter description'); ?>"
               value="<?= $item['description'] ?? ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="price"><?= translate('Price'); ?></label>
        <input type="number" step=".01" class="form-control" id="price" name="price" placeholder="<?= translate('Enter price'); ?>"
               value="<?= $item['price'] ?? ''; ?>" required>
    </div>

    <div class="form-group">
        <?= translate('Select image to upload'); ?>:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="hidden" name="id" value="<?= $_GET['id'] ?? ''; ?>">

        <?php if (isset($item['img'])): ?>
            <p><?= translate('Current image') ?>:</p>
            <img src="<?= $item['img'] ?>" alt="no image">
        <?php endif; ?>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="save"><?= translate('Save'); ?></button>
        <a href="products.php">
            <button class="btn btn-primary" type="button"><?= translate('Back to products page'); ?></button>
        </a>
    </div>

</form>

<?php require 'footer.php'; ?>