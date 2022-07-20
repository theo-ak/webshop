<?php

require_once 'common.php';

$_SESSION['admin_logged_in'] = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? test_input($_POST['username']) : "";
    $password = isset($_POST['password']) ? test_input($_POST['password']) : "";


    if ($username == ADMIN && $password == ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;

        if(isset($_SESSION['rdrurl']))
            header('location: '. $_SESSION['rdrurl']);
        else
            header('location: products.php');
    }
}

?>

<?php
require 'header.php'; ?>

    <form method="post" id="details-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="form-group">
            <label for="username"><?= translate('Username') ?></label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <label for="contact"><?= translate('Password') ?></label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password"required>
        </div>
        <button type="submit" class="btn btn-primary" id="login">Login</button>
    </form>

<?php
require 'footer.php'; ?>

