<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../class/User.php";

$administrator = new User($pdo, $_SESSION['user_id']);