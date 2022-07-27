<?php

require_once 'common.php';

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
}

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

$title = $description = $price = null;

$id = isset($_GET['id']) ? $_GET['id'] : '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && $id) {
    $sql = 'SELECT * FROM products WHERE id=:id';
    $query = $connection->prepare($sql);
    $query->execute([
        'id' => $id
    ]);

    $item = $query->fetch();

    $title = $item['title'];
    $description = $item['description'];
    $price = $item['price'];
    $image = $item['img'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = testInput($_POST['id']) ?? '';
    $title = testInput($_POST['title']) ?? '';
    $description = testInput($_POST['description']) ?? '';
    $price = testInput($_POST['price']) ?? '';

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

    if ($id) {
        if ($title && $description && $price && $img) {
            $sql = 'UPDATE products SET title = :title, description = :description, price = :price, img = :img 
                    WHERE id = :id';
            $query = $connection->prepare($sql);
            $query->execute([
                'title' => $title,
                'description' => $description,
                'price' => $price,
                'img' => $img,
                'id' => $id
            ]);

            header("Location: product.php?id=$id");
            exit;
        }
    } else {
        $sql = 'INSERT INTO products (title, description, price, img) VALUES (:title, :description, :price, :img)';
        $query = $connection->prepare($sql);
        $query->execute([
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'img' => $img
        ]);

        header('Location: products.php');
        exit;
    }
}

?>

<?php require 'header.php'; ?>

<form method="post" id="details-form" action="product.php" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"><?= translate('Title'); ?></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="<?= translate('Enter title'); ?>" value="<?= $title; ?>"
               required>
    </div>
    <div class="form-group">
        <label for="description"><?= translate('Description'); ?></label>
        <input type="text" class="form-control" id="description" name="description" placeholder="<?= translate('Enter description'); ?>"
               value="<?= $description; ?>" required>
    </div>
    <div class="form-group">
        <label for="price"><?= translate('Price'); ?></label>
        <input type="number" step=".01" class="form-control" id="price" name="price" placeholder="<?= translate('Enter price'); ?>"
               value="<?= $price ?>" required>
    </div>

    <div class="form-group">
        <?= translate('Select image to upload'); ?>:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="hidden" name="id" value="<?= $id ?>">

        <?php if (isset($image)): ?>
            <p><?= translate('Current image') ?>:</p>
            <img src="<?= $image ?>" alt="no image">
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

