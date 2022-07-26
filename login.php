<?php

require_once 'common.php';

if ($_SESSION['admin_logged_in']) {
    header('Location: products.php');
    exit;
}

$_SESSION['login_err'] = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? testInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? testInput($_POST['password']) : '';


    if ($username == ADMIN && $password == ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;

        if (isset($_SESSION['rdrurl'])) {
            header('location: '.$_SESSION['rdrurl']);
            exit;
        }
        else {
            header('location: products.php');
            exit;
        }
    } else {
        $_SESSION['login_err'] = 'Invalid credentials.';
    }
}

require 'header.php';

?>

    <form method="post" id="details-form" action="login.php">
        <span><?= isset($_SESSION['login_err']) ? $_SESSION['login_err'] : ''; ?></span>
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

<?php require 'footer.php'; ?>

