<?php

require_once 'common.php';

$_SESSION['rdrurl'] = $_SERVER['REQUEST_URI'];

if (!$_SESSION['admin_logged_in']) {
    header('Location: login.php');
}

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
    $id = isset($_POST['id']) ? test_input($_POST['id']) : "";
    $title = isset($_POST['title']) ? test_input($_POST['title']) : "";
    $description = isset($_POST['description']) ? test_input($_POST['description']) : "";
    $price = isset($_POST['price']) ? (float)test_input($_POST['price']) : "";

    if (isset($_FILES['fileToUpload'])) {
        $target_dir = "img/";
        $img = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $img)) {
                echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
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
    }
}

?>

<?php
require 'header.php'; ?>

<form method="post" id="details-form" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title"><?= translate('Title'); ?></label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?= $title; ?>"
               required>
    </div>
    <div class="form-group">
        <label for="description"><?= translate('Description'); ?></label>
        <input type="text" class="form-control" id="description" name="description" placeholder="Enter description"
               value="<?= $description; ?>" required>
    </div>
    <div class="form-group">
        <label for="price"><?= translate('Price'); ?></label>
        <input type="number" step=".01" class="form-control" id="price" name="price" placeholder="Enter price"
               value="<?= $price ?>" required>
    </div>

    <div class="form-group">
        <?= translate('Select image to upload'); ?>:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
        <input type="hidden" name="id" value="<?= $id ?>">

        <?php
        if (isset($image)): ?>
            <p><?= translate('Current image') ?>:</p>
            <img src="<?= $image ?>" alt="no image">
        <?php
        endif; ?>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="save"><?= translate('Save'); ?></button>
        <a href="products.php">
            <button class="btn btn-primary" type="button"><?= translate('Back to products page'); ?></button>
        </a>
    </div>

</form>

<?php
require 'footer.php'; ?>

