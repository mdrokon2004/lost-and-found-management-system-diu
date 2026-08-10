<?php
require_once __DIR__.'/config/config.php'; require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/auth.php'; require_login();
$type=$_GET['type']??'lost';$item_id=(int)($_GET['id']??0);
if($_SERVER['REQUEST_METHOD']==='POST'){ $details=trim($_POST['details']??''); if($details){$s=$pdo->prepare("INSERT INTO claims(user_id,item_type,item_id,details,status_id) VALUES(?,?,?,?,(SELECT id FROM statuses WHERE code='pending' LIMIT 1))");$s->execute([$_SESSION['user']['id'],$type,$item_id,$details]);flash('success','Claim submitted for review.');redirect('user/claims.php');} }
$page_title='Submit Claim';include __DIR__.'/includes/header.php'; ?>
<div class="row justify-content-center py-5"><div class="col-lg-7"><div class="form-card p-4 p-lg-5"><div class="text-primary fw-bold">OWNERSHIP</div><h2 class="section-title">Submit a claim</h2><p class="text-secondary">Explain information that only the genuine owner would reasonably know.</p><form method="post"><textarea class="form-control mb-3" name="details" rows="7" required placeholder="Example: unique marks, contents, purchase details, identifying features..."></textarea><button class="btn btn-primary-glass px-4 py-3">Submit claim</button></form></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
