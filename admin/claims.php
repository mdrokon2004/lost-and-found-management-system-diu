<?php
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id && in_array($action, ['approve','reject'], true)) {
        $claimCode = $action === 'approve' ? 'claim_approved' : 'rejected';
        $status = $pdo->prepare("SELECT id FROM statuses WHERE code=? LIMIT 1");
        $status->execute([$claimCode]);
        $claimStatus = $status->fetchColumn();

        $s = $pdo->prepare("SELECT user_id,item_type,item_id FROM claims WHERE id=?");
        $s->execute([$id]);
        $claim = $s->fetch();

        if ($claim && $claimStatus) {
            $pdo->beginTransaction();
            try {
                $u = $pdo->prepare("UPDATE claims SET status_id=?, reviewed_at=NOW() WHERE id=?");
                $u->execute([$claimStatus, $id]);

                if ($action === 'approve') {
                    $resolved = $pdo->query("SELECT id FROM statuses WHERE code='resolved' LIMIT 1")->fetchColumn();
                    $table = $claim['item_type'] === 'found' ? 'found_items' : 'lost_items';
                    $u2 = $pdo->prepare("UPDATE {$table} SET status_id=? WHERE id=?");
                    $u2->execute([$resolved, $claim['item_id']]);
                }

                $n = $pdo->prepare("INSERT INTO notifications(user_id,title,message) VALUES(?,?,?)");
                $n->execute([
                    $claim['user_id'],
                    'Claim '.ucfirst($action).'d',
                    'Your ownership claim has been '.$action.'d by an administrator.'
                ]);

                $log = $pdo->prepare("INSERT INTO activity_logs(user_id,action,entity_type,entity_id,details) VALUES(?,?,?,?,?)");
                $log->execute([$_SESSION['user']['id'], 'claim_'.$action, 'claim', $id, 'Administrator reviewed ownership claim.']);

                $pdo->commit();
                flash('success', 'Claim '.$action.'d successfully.');
            } catch (Throwable $e) {
                $pdo->rollBack();
                flash('danger', 'Could not update the claim.');
            }
        }
    }
    redirect('admin/claims.php');
}

$page_title='Claims';
$rows=$pdo->query("SELECT c.id,c.item_type,c.item_id,c.details,u.name claimant,s.name status_name,c.created_at FROM claims c JOIN users u ON u.id=c.user_id JOIN statuses s ON s.id=c.status_id ORDER BY c.created_at DESC")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="admin-layout py-3">
<?php include __DIR__.'/../includes/sidebar.php'; ?>
<section>
<div class="mb-4"><div class="text-primary fw-bold">VERIFICATION QUEUE</div><h2 class="section-title">Claims</h2></div>
<div class="glass-panel table-wrap">
<table class="table align-middle">
<thead><tr><th>ID</th><th>Type</th><th>Item</th><th>Claimant</th><th>Details</th><th>Status</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($rows as $row): ?>
<tr>
<td>#<?=e($row['id'])?></td>
<td><?=e(ucfirst($row['item_type']))?></td>
<td>#<?=e($row['item_id'])?></td>
<td><?=e($row['claimant'])?></td>
<td><?=e(mb_strimwidth($row['details'],0,70,'…'))?></td>
<td><span class="badge badge-soft"><?=e($row['status_name'])?></span></td>
<td>
<?php if($row['status_name']==='Pending Review'): ?>
<div class="d-flex gap-2">
<form method="post"><input type="hidden" name="id" value="<?=$row['id']?>"><button name="action" value="approve" class="btn btn-sm btn-primary-glass">Approve</button></form>
<form method="post"><input type="hidden" name="id" value="<?=$row['id']?>"><button name="action" value="reject" class="btn btn-sm btn-glass">Reject</button></form>
</div>
<?php else: ?><span class="text-secondary small">Reviewed</span><?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
</tbody></table></div>
</section></div>
<?php include __DIR__.'/../includes/footer.php'; ?>
