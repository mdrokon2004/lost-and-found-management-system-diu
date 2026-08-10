<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

$reason = $_GET['reason'] ?? '';

logout_user();

if ($reason === 'tab_closed') {
    flash('info', 'Your session was closed because the browser tab was closed.');
} elseif ($reason === 'server_restarted') {
    flash('warning', 'Server was restarted. Please log in again.');
} else {
    flash('success', 'You have been logged out successfully.');
}

redirect('login.php');