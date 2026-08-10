<?php
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/auth.php';

if (is_admin()) redirect('admin/dashboard.php');
if (is_logged_in()) redirect('user/dashboard.php');
$page_title='Admin Login';

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email=trim($_POST['email']??'');
    $pass=$_POST['password']??'';
    $s=$pdo->prepare("SELECT u.*,r.code role_name FROM users u JOIN roles r ON r.id=u.role_id WHERE u.email=? AND r.code='admin' AND u.is_active=1 LIMIT 1");
    $s->execute([$email]);
    $u=$s->fetch();
    if ($u && password_verify($pass,$u['password_hash'])) {
        login_user($u);
        redirect('admin/dashboard.php');
    }
    flash('danger','Invalid administrator email or password.');
}
include __DIR__.'/../includes/header.php';
?>
<div class="auth-shell auth-shell-admin">
  <div class="auth-card auth-card-modern glass-panel">
    <div class="auth-visual auth-visual-admin">
      <div class="auth-visual-top"><span class="brand-mark auth-brand-mark"><i class="bi bi-shield-check"></i></span><span>ADMINISTRATOR PORTAL</span></div>
      <div class="auth-visual-content">
        <div class="auth-orb"><i class="bi bi-grid-1x2"></i></div>
        <h1>Control the platform.</h1>
        <p>Review lost and found reports, verify claims, manage users, and keep the DIU community workflow safe and organized.</p>
      </div>
      <div class="auth-visual-points">
        <span><i class="bi bi-check2-circle"></i> Review reports</span>
        <span><i class="bi bi-check2-circle"></i> Verify claims</span>
        <span><i class="bi bi-check2-circle"></i> Manage users</span>
      </div>
    </div>
    <div class="auth-form-pane">
      <div class="auth-pane-head">
        <div><div class="text-primary fw-bold small text-uppercase letter-spaced">Restricted Access</div><h2>Admin sign in</h2></div>
        <div class="auth-mini-icon auth-mini-admin"><i class="bi bi-shield-lock"></i></div>
      </div>
      <p class="text-secondary mb-4">Authorized administrators only. Your role is checked before access is granted.</p>
      <form method="post">
        <label class="form-label">Administrator email</label>
        <div class="input-glass-wrap mb-3"><i class="bi bi-envelope-at"></i><input class="form-control" type="email" name="email" autocomplete="username" placeholder="admin@example.com" required></div>
        <label class="form-label">Password</label>
        <div class="input-glass-wrap mb-2"><i class="bi bi-key"></i><input class="form-control" type="password" name="password" autocomplete="current-password" placeholder="Enter administrator password" required></div>
        <div class="security-note mb-4"><i class="bi bi-shield-check"></i><span>Protected administrator area · role-based access enabled</span></div>
        <button class="btn btn-primary-glass w-100 py-3 auth-submit"><i class="bi bi-box-arrow-in-right me-2"></i>Continue to Admin Panel</button>
      </form>
      <div class="auth-bottom-grid mt-4">
        <div>Student/User?<br><a href="<?=BASE_URL?>/login.php"><i class="bi bi-person me-1"></i>User Login</a></div>
        <div>Need an account?<br><a href="<?=BASE_URL?>/register.php">Create User Account</a></div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
