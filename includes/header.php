<?php
require_once __DIR__ . '/auth.php';
$flashes = get_flashes();
$user = current_user();
$page_title = $page_title ?? SITE_NAME;
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
