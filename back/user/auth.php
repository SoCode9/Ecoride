<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Checks whether a user is logged in.
 * If not, redirects to the login page with an error message.
 *
 * @param string $redirectUrl  Redirect URL to the login page
 */
function requireLogin(string $redirectUrl = '../../controllers/login.php'): void
{
    if (empty($_SESSION['user_id'])) {
        $_SESSION['error_message'] = "Connectez-vous pour accéder à cette page.";
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Checks whether the user is an administrator.
 * If not, redirects to the home page with an error message.
 *
 * @param string $redirectUrl  Redirect URL to the home page
 */
function requireAdmin(string $redirectUrl = '../index.php'): void
{
    if (empty($_SESSION['role_user']) || $_SESSION['role_user'] !== 5) {
        $_SESSION['error_message'] = "Seul un administrateur peut accéder à cette page.";
        header('Location: ' . $redirectUrl);
        exit;
    }
}

/**
 * Checks whether the user is a driver.
 * If not, redirects to the home page with an error message.
 *
 * @param string $redirectUrl  Redirect URL to the home page
 */
function requireDriver(string $redirectUrl = '../index.php'): void
{
    if (empty($_SESSION['role_user']) || ($_SESSION['role_user'] !== 2 && $_SESSION['role_user'] !== 3)) {
        $_SESSION['error_message'] = "Seul un chauffeur peut accéder à cette page.";
        header('Location: ' . $redirectUrl);
        exit;
    }
}
