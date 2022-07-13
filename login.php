<?php

require_once 'common.php';

$_SESSION['admin_logged_in'] = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? test_input($_POST['username']) : "";
    $password = isset($_POST['password']) ? test_input($_POST['password']) : "";

    if ($username == ADMIN && $password == ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: products.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Music Shop</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <form method="post" id="details-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="contact">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password"required>
        </div>
        <button type="submit" class="btn btn-primary" id="login">Login</button>
    </form>
</body>

</html>

