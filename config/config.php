<?php
if (session_status() === PHP_SESSION_NONE) session_start();

define('SITE_NAME', 'DIU Lost & Found');

if (!defined('BASE_URL')) {
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    if (strpos($scriptName, '/lost-and-found-management-system') !== false) {
        define('BASE_URL', '/lost-and-found-management-system');
    } else {
        define('BASE_URL', '');
    }
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function flash($type, $message) {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function get_flashes() {
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $items;
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function is_admin() {
    return is_logged_in() && ($_SESSION['user']['role'] ?? '') === 'admin';
}

function require_login() {
    if (!is_logged_in()) {
        flash('warning', 'Please log in to continue.');
        redirect('login.php');
    }
}

function require_user() {
    if (!is_logged_in()) {
        flash('warning', 'Please log in as a student/user to continue.');
        redirect('login.php');
    }
    if (is_admin()) redirect('admin/dashboard.php');
}

function require_admin() {
    if (!is_admin()) {
        flash('danger', 'Administrator access is required.');
        redirect('admin/login.php');
    }
}
