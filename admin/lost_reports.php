<?php
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../config/db.php';
require_once __DIR__.'/../includes/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($id && in_array($action, ['approve','reject'], true)) {
        $code = $action === 'approve' ? 'approved' : 'rejected';
        $status = $pdo->prepare("SELECT id FROM statuses WHERE code=? LIMIT 1");
        $status->execute([$code]);
        $statusId = $status->fetchColumn();

        $s = $pdo->prepare("SELECT user_id, title FROM lost_items WHERE id=?");
        $s->execute([$id]);
        $item = $s->fetch();

        if ($item && $statusId) {
            $pdo->beginTransaction();
            try {
                $u = $pdo->prepare("UPDATE lost_items SET status_id=? WHERE id=?");
                $u->execute([$statusId, $id]);

                $n = $pdo->prepare("INSERT INTO notifications(user_id,title,message) VALUES(?,?,?)");
                $n->execute([
                    $item['user_id'],
                    'Lost report '.ucfirst($action).'d',
                    'Your lost item report "'.$item['title'].'" has been '.$action.'d by an administrator.'
                ]);

                $log = $pdo->prepare("INSERT INTO activity_logs(user_id,action,entity_type,entity_id,details) VALUES(?,?,?,?,?)");
                $log->execute([$_SESSION['user']['id'], 'report_'.$action, 'lost_item', $id, 'Administrator reviewed lost report.']);

                $pdo->commit();
                flash('success', 'Lost report '.$action.'d successfully.');
            } catch (Throwable $e) {
                $pdo->rollBack();
                flash('danger', 'Could not update the report.');
            }
        }
    }
    redirect('admin/lost_reports.php');
}

$page_title='Lost Reports';
$rows=$pdo->query("SELECT l.id,l.title,u.name reporter,s.name status_name,l.created_at FROM lost_items l JOIN users u ON u.id=l.user_id JOIN statuses s ON s.id=l.status_id ORDER BY l.created_at DESC")->fetchAll();
include __DIR__.'/../includes/header.php';
?>
<div class="admin-layout py-3">
<?php include __DIR__.'/../includes/sidebar.php'; ?>
<section>
<div class="mb-4"><div class="text-primary fw-bold">REVIEW QUEUE</div><h2 class="section-title">Lost Reports</h2></div>
<div class="glass-panel table-wrap">
<table class="table align-middle">
<thead><tr><th>ID</th><th>Item</th><th>Reporter</th><th>Status</th><th>Created</th><th>Action</th></tr></thead>
<tbody>
<?php foreach($rows as $row): ?>
<tr>
<td>#<?=e($row['id'])?></td>
<td><strong><?=e($row['title'])?></strong></td>
<td><?=e($row['reporter'])?></td>
<td><span class="badge badge-soft"><?=e($row['status_name'])?></span></td>
<td><?=date('d M Y H:i',strtotime($row['created_at']))?></td>
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
