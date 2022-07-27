<?php

require_once 'common.php';

if ($_SESSION['admin_logged_in']) {
    header('Location: products.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = testInput($_POST['username']) ?? '';
    $password = testInput($_POST['password']) ?? '';

    if ($username == ADMIN && $password == ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;

        $rdrurl = $_SESSION['rdrurl'] ?? 'products.php';
        header('Location: ' . $rdrurl);
        exit;
    } elseif (!$username || !$password) {
        $error = translate('Please make sure to fill out all fields');
    } else {
        $error = translate('Invalid credentials.');
    }
}

require 'header.php';

?>

<form method="post" id="details-form" action="login.php">
    <?php if ($error): ?>
    <span><?= $error; ?></span>
    <?php endif; ?>
    <div class="form-group">
        <label for="username"><?= translate('Username') ?></label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username">
    </div>
    <div class="form-group">
        <label for="contact"><?= translate('Password') ?></label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <button type="submit" class="btn btn-primary" id="login"><?= translate('Login'); ?></button>
</form>

<?php require 'footer.php'; ?>

