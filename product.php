<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    $_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $item = selectById($connection, 'products', 'id', $_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = [
        'id' => testInput($_POST['id']) ?? '',
        'title' => testInput($_POST['title']) ?? '',
        'description' => testInput($_POST['description']) ?? '',
        'price' => testInput($_POST['price']) ?? ''
    ];

    if ($_FILES['fileToUpload']['tmp_name']) {
        $targetDir = 'img/';
        $extension = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
        $_FILES['fileToUpload']['name'] = date('Y-m-d-H-i-s') . '_' . uniqid() . '.' . $extension;
        $item['img'] = $targetDir . basename($_FILES['fileToUpload']['name']);

        $fileType = mime_content_type($_FILES['fileToUpload']['tmp_name']);

        if (str_contains($fileType, 'image')) {
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $item['img']);
        } else {
            $item['img'] = '';
            $errors['img'] = translate('Invalid file type');
        }
    }

    if ($item['title'] && $item['description'] && $item['price']) {
        if ($item['id']) {
            $sql = 'UPDATE products SET title = ?, description = ?, price = ?, img = ? 
                    WHERE id = ?';
            $query = $connection->prepare($sql);
            $query->execute([
                $item['title'],
                $item['description'],
                $item['price'],
                $item['img'],
                $item['id']
            ]);

            header('Location: product.php?id=' . $item['id']);
            exit;
        } else {
            $sql = 'INSERT INTO products (title, description, price, img) VALUES (?, ?, ?, ?)';
            $query = $connection->prepare($sql);
            $query->execute([
                $item['title'],
                $item['description'],
                $item['price'],
                $item['img']
            ]);

            header('Location: product.php?id=' . $connection->lastInsertId());
            exit;
        }
    }

    $errors['title'] = $item['title'] ?
        '' :
        translate('Title should not be empty');
    $errors['description'] = $item['description'] ?
        '' :
        translate('Description should not be empty');
    $errors['price'] = $item['price'] ?
        '' :
        translate('Price should not be empty');
}

?>

<?php require 'header.php'; ?>

<form method="post" id="details-form" action="product.php" enctype="multipart/form-data">

    <?php if (isset($error)): ?>
    <span><?= $error; ?></span>
    <?php endif; ?>
    <div class="form-group">
        <label for="title"><?= translate('Title'); ?></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="<?= translate('Enter title'); ?>" value="<?= $item['title'] ?? ''; ?>"
        >

        <?php if (isset($errors['title'])): ?>
            <span><?= $errors['title']; ?></span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="description"><?= translate('Description'); ?></label>
        <input type="text" class="form-control" id="description" name="description" placeholder="<?= translate('Enter description'); ?>"
               value="<?= $item['description'] ?? ''; ?>"
        >

        <?php if (isset($errors['description'])): ?>
            <span><?= $errors['description']; ?></span>
        <?php endif; ?>
    </div>
    <div class="form-group">
        <label for="price"><?= translate('Price'); ?></label>
        <input type="number" step=".01" class="form-control" id="price" name="price" placeholder="<?= translate('Enter price'); ?>"
               value="<?= $item['price'] ?? ''; ?>"
        >

        <?php if (isset($errors['price'])): ?>
            <span><?= $errors['price']; ?></span>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <?= translate('Select image to upload'); ?>:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="hidden" name="id" value="<?= $_GET['id'] ?? ''; ?>"
        >

        <?php if (isset($errors['img'])): ?>
            <span><?= $errors['img']; ?></span>
        <?php endif; ?>

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