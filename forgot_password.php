<?php 
require_once __DIR__ . '/config/config.php'; 
$page_title = 'Forgot Password'; 
include __DIR__ . '/includes/header.php'; 
?>

<div class="row justify-content-center py-5">
    <div class="col-lg-5 col-md-8">
        <div class="form-card p-4 p-lg-5 text-center">
            <div class="mb-3 text-primary">
                <i class="bi bi-shield-lock display-4"></i>
            </div>
            <h2 class="section-title mb-2">Password Recovery</h2>
            <p class="text-secondary mb-4">
                For security purposes in this academic build, direct password reset is disabled. Please contact the system administrator to reset your account password.
            </p>
            <a class="btn btn-primary-glass w-100 py-2" href="<?= BASE_URL ?>/login.php">
                <i class="bi bi-arrow-left me-1"></i> Back to Login
            </a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>