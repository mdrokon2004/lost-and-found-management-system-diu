<?php
require_once __DIR__ . '/auth.php';
$flashes = get_flashes();
$user = current_user();
$page_title = $page_title ?? SITE_NAME;

$unread_count = 0;
$recent_notifications = [];
if ($user && isset($pdo)) {
    $unread_count = get_unread_notification_count($pdo, $user['id']);
    $recent_notifications = get_recent_notifications($pdo, $user['id'], 5);
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="DIU Lost & Found Management System">
<title><?= e($page_title) ?> · <?= e(SITE_NAME) ?></title>
<script>
(function(){try{var t=localStorage.getItem('diu-theme');if(!t)t=matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light';document.documentElement.dataset.theme=t;}catch(e){}})();
</script>
<?php if ($user): ?>
<script>
(function() {
  var isFresh = <?= !empty($_SESSION['fresh_login']) ? 'true' : 'false' ?>;
  var tabSession = sessionStorage.getItem('diu_tab_active');
  if (isFresh) {
    sessionStorage.setItem('diu_tab_active', '1');
  } else if (!tabSession) {
    window.location.href = "<?= BASE_URL ?>/logout.php?reason=tab_closed";
  }
})();
</script>
<?php 
  if (!empty($_SESSION['fresh_login'])) {
      unset($_SESSION['fresh_login']);
  }
endif; 
?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/app.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg glass-nav sticky-top">
  <div class="container py-2">
    <a class="navbar-brand fw-800" href="<?= BASE_URL ?>/index.php">
      <span class="brand-mark"><i class="bi bi-search"></i></span> DIU Lost & Found
    </a>
    <button class="navbar-toggler border-0" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Open navigation"><i class="bi bi-list fs-2"></i></button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
        <li><a class="nav-link" href="<?= BASE_URL ?>/index.php">Home</a></li>
        <li><a class="nav-link" href="<?= BASE_URL ?>/browse_lost.php">Lost Items</a></li>
        <li><a class="nav-link" href="<?= BASE_URL ?>/browse_found.php">Found Items</a></li>
        <li><a class="nav-link" href="<?= BASE_URL ?>/about.php">About</a></li>
        <?php if ($user): ?>
          <li class="nav-item dropdown">
            <a class="nav-link position-relative p-2 me-lg-2 text-reset" href="#" id="notifBellDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
              <i class="bi bi-bell-fill fs-5"></i>
              <?php if ($unread_count > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                  <?= $unread_count > 99 ? '99+' : $unread_count ?>
                </span>
              <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end p-0 shadow-lg border-0 glass-dropdown" aria-labelledby="notifBellDropdown" style="min-width: 320px; max-width: 380px;">
              <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Notifications</h6>
                <?php if ($unread_count > 0): ?>
                  <a href="<?= BASE_URL ?>/user/notifications.php?mark_read=all" class="badge text-primary bg-primary-subtle text-decoration-none">Mark all read</a>
                <?php endif; ?>
              </div>
              <div class="notif-dropdown-body overflow-auto" style="max-height: 320px;">
                <?php if (empty($recent_notifications)): ?>
                  <div class="p-3 text-center text-muted small">No notifications yet</div>
                <?php else: ?>
                  <?php foreach ($recent_notifications as $notif): ?>
                    <a href="<?= BASE_URL ?>/user/notifications.php" class="dropdown-item p-3 border-bottom text-wrap <?= $notif['is_read'] ? 'text-secondary' : 'fw-bold bg-primary-subtle' ?>">
                      <div class="d-flex justify-content-between align-items-start mb-1">
                        <strong class="small text-body"><?= e($notif['title']) ?></strong>
                        <small class="text-muted ms-2" style="font-size: 0.7rem;"><?= date('M d, H:i', strtotime($notif['created_at'])) ?></small>
                      </div>
                      <p class="mb-0 small text-secondary" style="font-size: 0.825rem; line-height: 1.3;"><?= e($notif['message']) ?></p>
                    </a>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
              <div class="p-2 text-center border-top">
                <a href="<?= BASE_URL ?>/user/notifications.php" class="small fw-semibold text-primary text-decoration-none">View All Notifications <i class="bi bi-chevron-right"></i></a>
              </div>
            </div>
          </li>
          <?php if ($user['role'] === 'admin'): ?>
            <li><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a></li>
          <?php else: ?>
            <li><a class="nav-link" href="<?= BASE_URL ?>/user/dashboard.php">Dashboard</a></li>
          <?php endif; ?>
          <li><a class="btn btn-glass btn-sm px-3" href="<?= BASE_URL ?>/logout.php"><i class="bi bi-box-arrow-right me-1"></i>Logout</a></li>
        <?php else: ?>
          <li><a class="portal-btn" href="<?= BASE_URL ?>/login.php"><i class="bi bi-person"></i>User Login</a></li>
          <li><a class="portal-btn" href="<?= BASE_URL ?>/admin/login.php"><i class="bi bi-shield-lock"></i>Admin Login</a></li>
          <li><a class="btn btn-primary-glass btn-sm px-3" href="<?= BASE_URL ?>/register.php">Get Started</a></li>
        <?php endif; ?>
        <li class="ms-lg-2">
          <div class="theme-switcher" role="group" aria-label="Choose website theme">
            <button type="button" class="theme-choice" data-theme-choice="light" aria-pressed="false"><i class="bi bi-sun-fill"></i><span>Light</span></button>
            <button type="button" class="theme-choice" data-theme-choice="dark" aria-pressed="false"><i class="bi bi-moon-stars-fill"></i><span>Dark</span></button>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<main class="container py-4">
<?php foreach ($flashes as $f): ?>
<div class="alert alert-<?= e($f['type']) ?> glass-alert"><?= e($f['message']) ?></div>
<?php endforeach; ?>
