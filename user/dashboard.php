<?php
require_once __DIR__.'/../config/config.php';require_once __DIR__.'/../config/db.php';require_once __DIR__.'/../includes/auth.php';require_login();if(is_admin())redirect('admin/dashboard.php');
$page_title='User Dashboard';$uid=$_SESSION['user']['id'];
$stats=[
 'Total reports'=>(int)$pdo->query("SELECT (SELECT COUNT(*) FROM lost_items WHERE user_id=$uid)+(SELECT COUNT(*) FROM found_items WHERE user_id=$uid)")->fetchColumn(),
 'Lost reports'=>(int)$pdo->query("SELECT COUNT(*) FROM lost_items WHERE user_id=$uid")->fetchColumn(),
 'Found reports'=>(int)$pdo->query("SELECT COUNT(*) FROM found_items WHERE user_id=$uid")->fetchColumn(),
 'Claims'=>(int)$pdo->query("SELECT COUNT(*) FROM claims WHERE user_id=$uid")->fetchColumn()
];
include __DIR__.'/../includes/header.php'; ?>
<div class="py-3"><div class="d-flex justify-content-between align-items-end mb-4"><div><div class="text-primary fw-bold">YOUR WORKSPACE</div><h1 class="section-title">Welcome, <?=e($_SESSION['user']['name'])?></h1></div><div class="d-flex gap-2"><a href="<?=BASE_URL?>/report_lost.php" class="btn btn-primary-glass">Report lost</a><a href="<?=BASE_URL?>/report_found.php" class="btn btn-glass">Report found</a></div></div>
<div class="row g-3"><?php foreach($stats as $label=>$value):?><div class="col-6 col-lg-3"><div class="stat-card"><div class="text-secondary small"><?=$label?></div><div class="display-6 fw-bold mt-2"><?=$value?></div></div></div><?php endforeach;?></div>
<div class="row g-4 mt-2"><div class="col-lg-4"><div class="glass-panel p-4"><h5>Quick actions</h5><a class="d-block py-2" href="<?=BASE_URL?>/user/lost_reports.php">My lost reports <i class="bi bi-arrow-right"></i></a><a class="d-block py-2" href="<?=BASE_URL?>/user/found_reports.php">My found reports <i class="bi bi-arrow-right"></i></a><a class="d-block py-2" href="<?=BASE_URL?>/user/claims.php">My claims <i class="bi bi-arrow-right"></i></a><a class="d-block py-2" href="<?=BASE_URL?>/user/profile.php">My profile <i class="bi bi-arrow-right"></i></a></div></div><div class="col-lg-8"><div class="glass-panel p-4"><h5>How it works</h5><div class="row g-3 mt-1"><?php foreach([['1','Report','Submit a lost or found report.'],['2','Review','Admin verifies the report.'],['3','Claim','Potential owner submits evidence.'],['4','Resolve','Approved claims close the case.']] as $x):?><div class="col-md-6"><div class="d-flex gap-3"><span class="badge rounded-pill badge-soft"><?=$x[0]?></span><div><strong><?=$x[1]?></strong><div class="small text-secondary"><?=$x[2]?></div></div></div></div><?php endforeach;?></div></div></div></div></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
