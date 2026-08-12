<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/db.php';
require_once __DIR__.'/includes/auth.php';

if(is_admin()) redirect('admin/dashboard.php');
if(is_logged_in()) redirect('user/dashboard.php');

$page_title='User Login';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $email=trim($_POST['email'] ?? '');
    $pass=$_POST['password'] ?? '';

    if($email==='' || $pass===''){
        flash('danger','Please enter your email and password.');
    }else{
        $s=$pdo->prepare("SELECT u.*,r.code role_name FROM users u JOIN roles r ON r.id=u.role_id WHERE u.email=? AND r.code='user' AND u.is_active=1 LIMIT 1");
        $s->execute([$email]);
        $u=$s->fetch();

        if($u && password_verify($pass,$u['password_hash'])){
            login_user($u);
            redirect('user/dashboard.php');
        }

        flash('danger','Invalid user email or password. Admins should use the Admin Login portal.');
    }
}

include __DIR__.'/includes/header.php';
?>

<div class="auth-shell auth-shell-user">
  <div class="auth-card auth-card-modern glass-panel">

    <div class="auth-visual auth-visual-user">
      <div class="auth-visual-top">
        <span class="brand-mark auth-brand-mark"><i class="bi bi-person-check"></i></span>
        <span>STUDENT PORTAL</span>
      </div>

      <div class="auth-visual-content">
        <div class="auth-orb"><i class="bi bi-search"></i></div>
        <h1>Welcome back.</h1>
        <p>Sign in to report a lost item, share something you found, track your reports, and manage ownership claims.</p>
      </div>

      <div class="auth-visual-points">
        <span><i class="bi bi-check2-circle"></i> Report items</span>
        <span><i class="bi bi-check2-circle"></i> Track claims</span>
        <span><i class="bi bi-check2-circle"></i> Stay notified</span>
      </div>
    </div>

    <div class="auth-form-pane">
      <div class="auth-pane-head">
        <div>
          <div class="text-primary fw-bold small text-uppercase letter-spaced">User Login</div>
          <h2>Sign in to your account</h2>
        </div>
        <div class="auth-mini-icon"><i class="bi bi-person"></i></div>
      </div>

      <p class="text-secondary mb-4">Use your DIU account details to continue.</p>

      <form method="post">
        <label class="form-label">Email address</label>
        <div class="input-glass-wrap mb-3">
          <i class="bi bi-envelope"></i>
          <input class="form-control" type="email" name="email" autocomplete="username" placeholder="you@example.com" required>
        </div>

        <label class="form-label">Password</label>
        <div class="input-glass-wrap mb-2">
          <i class="bi bi-lock"></i>
          <input class="form-control" type="password" name="password" autocomplete="current-password" placeholder="Enter your password" required>
        </div>

        <div class="d-flex justify-content-end mb-4">
          <a class="small fw-semibold" href="<?=BASE_URL?>/forgot_password.php">Forgot password?</a>
        </div>

        <button type="submit" class="btn btn-primary-glass w-100 py-3 auth-submit">
          <i class="bi bi-arrow-right-circle me-2"></i>Continue as User
        </button>
      </form>

      <div class="auth-bottom-grid mt-4">
        <div>New here?<br><a href="<?=BASE_URL?>/register.php">Create an account</a></div>
        <div>Administrator?<br><a href="<?=BASE_URL?>/admin/login.php"><i class="bi bi-shield-lock me-1"></i>Admin Login</a></div>
      </div>
    </div>

  </div>
</div>

<?php include __DIR__.'/includes/footer.php'; ?>