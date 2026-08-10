<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_login();

$userId = $_SESSION['user']['id'];

if (isset($_GET['mark_read'])) {
    if ($_GET['mark_read'] === 'all') {
        $u = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
        $u->execute([$userId]);
        flash('success', 'All notifications marked as read.');
    } else {
        $id = (int)$_GET['mark_read'];
        $u = $pdo->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
        $u->execute([$id, $userId]);
    }
    redirect('user/notifications.php');
}

$page_title = 'Notifications';
$s = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$s->execute([$userId]);
$rows = $s->fetchAll();

include __DIR__ . '/../includes/header.php';
?>
<div class="py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <div class="text-primary fw-bold">ALERTS & MESSAGES</div>
      <h2 class="section-title mb-0">Notifications</h2>
    </div>
    <?php if (!empty($rows)): ?>
      <a href="<?= BASE_URL ?>/user/notifications.php?mark_read=all" class="btn btn-sm btn-glass">
        <i class="bi bi-check2-all me-1"></i>Mark all as read
      </a>
    <?php endif; ?>
  </div>

  <?php if (empty($rows)): ?>
    <div class="glass-panel p-5 text-center text-secondary">
      <i class="bi bi-bell-slash fs-1 d-block mb-3 opacity-50"></i>
      <h5>No notifications yet</h5>
      <p class="small mb-0">When your reports or claims are reviewed, you will see notifications here.</p>
    </div>
  <?php else: ?>
    <div class="d-flex flex-column gap-3">
      <?php foreach ($rows as $n): ?>
        <div class="glass-panel p-4 position-relative <?= $n['is_read'] ? 'opacity-75' : 'border-start border-4 border-primary' ?>">
          <div class="d-flex justify-content-between align-items-start gap-2">
            <div>
              <div class="d-flex align-items-center gap-2 mb-1">
                <h5 class="fw-bold mb-0 text-body"><?= e($n['title']) ?></h5>
                <?php if (!$n['is_read']): ?>
                  <span class="badge bg-primary rounded-pill px-2">New</span>
                <?php endif; ?>
              </div>
              <p class="text-secondary mb-2 mt-1"><?= e($n['message']) ?></p>
              <small class="text-muted">
                <i class="bi bi-clock me-1"></i><?= date('d M Y, h:i A', strtotime($n['created_at'])) ?>
              </small>
            </div>
            <?php if (!$n['is_read']): ?>
              <a href="<?= BASE_URL ?>/user/notifications.php?mark_read=<?= $n['id'] ?>" class="btn btn-sm btn-link text-decoration-none" title="Mark as read">
                <i class="bi bi-check-circle fs-5"></i>
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>