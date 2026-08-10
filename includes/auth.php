<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';

function current_user() {
    return $_SESSION['user'] ?? null;
}

function login_user($user) {
    session_regenerate_id(true);
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['name'],
        'email' => $user['email'],
        'role' => $user['role_name']
    ];
}

function logout_user() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'], $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}

function notify_user($pdo, $userId, $title, $message) {
    if (!$userId || !$title || !$message) return false;
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
    return $stmt->execute([$userId, $title, $message]);
}

function notify_admins($pdo, $title, $message) {
    if (!$title || !$message) return false;
    $stmt = $pdo->query("SELECT u.id FROM users u JOIN roles r ON r.id = u.role_id WHERE r.code = 'admin'");
    $adminIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $insertStmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
    foreach ($adminIds as $adminId) {
        $insertStmt->execute([$adminId, $title, $message]);
    }
    return true;
}

function get_unread_notification_count($pdo, $userId) {
    if (!$userId) return 0;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0");
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

function get_recent_notifications($pdo, $userId, $limit = 5) {
    if (!$userId) return [];
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit);
    $stmt->execute([$userId]);
    return $stmt->fetchAll();
}

