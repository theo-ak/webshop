<?php

require_once 'common.php';

$title = $description = $price = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = isset($_POST['title']) ? test_input($_POST['title']) : "";
    $description = isset($_POST['description']) ? test_input($_POST['description']) : "";
    $price = isset($_POST['price']) ? (float)test_input($_POST['price']) : "";

    if (isset($_FILES['fileToUpload'])) {
        $target_dir = "img/";
        $img = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($img, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
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

    if ($title && $description && $price && $img) {
        $sql = 'INSERT INTO products (title, description, price, img) VALUES (:title, :description, :price, :img)';
        $query = $connection->prepare($sql);
        $query->execute([
            'title' => $title,
            'description' => $description,
            'price' => $price,
            'img' => $img
        ]);
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

<form method="post" id="details-form" action="<?php
echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title" value="<?= $title; ?>"
               required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <input type="text" class="form-control" id="description" name="description" placeholder="Enter description"
               value="<?= $description; ?>" required>
    </div>
    <div class="form-group">
        <label for="price">Price</label>
        <input type="number" step=".01" class="form-control" id="price" name="price" placeholder="Enter price"
               value="<?= $price ?>" required>
    </div>

    <div class="form-group">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload" required>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary" id="save">Save</button>
        <a href="products.php"><button class="btn btn-primary" type="button">Back to products page</button></a>
    </div>

</form>

</body>

</html>

