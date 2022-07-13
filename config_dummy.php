<?php

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

define('SERVER', '{servername}');
define('USERNAME', '{username}');
define('PASSWORD', '{password}');
define('DBNAME', '{database}');

define('ADMIN', '{admin_name}');
define('ADMIN_PASSWORD', '{admin_password}');

define('MANAGER', '{manager_email}');

