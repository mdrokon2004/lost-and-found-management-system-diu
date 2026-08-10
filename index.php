<?php
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/db.php';
$page_title='Home';
include __DIR__.'/includes/header.php';
$lost = $pdo->query("SELECT l.*, c.name category_name, loc.name location_name FROM lost_items l JOIN categories c ON c.id=l.category_id JOIN locations loc ON loc.id=l.location_id WHERE l.status_id=(SELECT id FROM statuses WHERE code='approved' LIMIT 1) ORDER BY l.created_at DESC LIMIT 6")->fetchAll();
?>
<section class="hero">
  <div class="row align-items-center g-5">
    <div class="col-lg-7">
      <div class="badge rounded-pill badge-soft px-3 py-2 mb-3">Daffodil International University</div>
      <h1>Lost something?<br><span class="gradient-text">Let's bring it home.</span></h1>
      <p class="lead mt-4">A trusted campus platform to report lost items, share found belongings, submit claims, and help the DIU community reconnect with what matters.</p>
      <div class="d-flex flex-wrap gap-3 mt-4">
        <a href="<?=BASE_URL?>/login.php" class="btn btn-primary-glass px-4 py-3"><i class="bi bi-person me-2"></i>User Login</a>
        <a href="<?=BASE_URL?>/admin/login.php" class="btn btn-glass px-4 py-3"><i class="bi bi-shield-lock me-2"></i>Admin Login</a>
        <a href="<?=BASE_URL?>/browse_found.php" class="btn btn-glass px-4 py-3">Browse Found Items</a>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="glass-panel p-4">
        <div class="d-flex justify-content-between align-items-center mb-4"><span class="fw-bold">Community activity</span><i class="bi bi-activity text-primary"></i></div>
        <div class="row g-3">
          <?php foreach ([
            ['bi-box-arrow-down','Lost reports','Track reported belongings'],
            ['bi-box-arrow-up','Found reports','Help owners find them'],
            ['bi-patch-check','Verified claims','Safer handovers']
          ] as $s): ?>
          <div class="col-12"><div class="d-flex gap-3 align-items-center"><div class="stat-icon"><i class="bi <?=$s[0]?>"></i></div><div><strong><?=$s[1]?></strong><div class="small text-secondary"><?=$s[2]?></div></div></div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="py-5">
  <div class="d-flex justify-content-between align-items-end mb-4"><div><div class="text-primary fw-bold">RECENT</div><h2 class="section-title">Recently reported items</h2></div><a href="<?=BASE_URL?>/browse_lost.php" class="btn btn-glass">View all</a></div>
  <div class="row g-4">
    <?php foreach($lost as $item): ?>
    <div class="col-md-6 col-lg-4"><?php include __DIR__.'/item_card.php'; ?></div>
    <?php endforeach; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>
