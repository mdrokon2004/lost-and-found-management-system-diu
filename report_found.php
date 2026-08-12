<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/auth.php';
require_login();

$page_title = 'Report Found Item';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $cat = (int)($_POST['category_id'] ?? 0);
    $loc = (int)($_POST['location_id'] ?? 0);
    $date = $_POST['item_date'] ?? '';
    
    $status = $pdo->query("SELECT id FROM statuses WHERE code='pending' LIMIT 1")->fetchColumn();
    $imagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        if (!is_dir(__DIR__ . '/uploads')) mkdir(__DIR__ . '/uploads', 0755, true);
        if (move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $name)) {
            $imagePath = 'uploads/' . $name;
        }
    }

    if ($title && $description && $cat && $loc && $date) {
        $s = $pdo->prepare("INSERT INTO found_items (user_id, title, description, category_id, location_id, found_date, status_id, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $s->execute([$_SESSION['user']['id'], $title, $description, $cat, $loc, $date, $status, $imagePath]);
        
        if (function_exists('notify_admins')) {
            notify_admins($pdo, 'New Found Item Report', $_SESSION['user']['name'] . ' reported a found item: "' . $title . '".');
        }

        flash('success', 'Your report has been submitted.');
        redirect('user/dashboard.php');
    } else {
        flash('danger', 'Please fill in all required fields.');
    }
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$locations = $pdo->query("SELECT * FROM locations ORDER BY name")->fetchAll();

include __DIR__ . '/includes/header.php'; 
?>

<div class="container py-4">
    <h2>Report a Found Item</h2>
    <form method="post" enctype="multipart/form-data" class="row g-3 mt-2">
        <div class="col-md-8"><label>Item title</label><input class="form-control" name="title" required></div>
        <div class="col-md-4"><label>Date</label><input class="form-control" type="date" name="item_date" required></div>
        <div class="col-md-6">
            <label>Category</label>
            <select class="form-select" name="category_id" required>
                <option value="">Select</option>
                <?php foreach ($categories as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <label>Location</label>
            <select class="form-select" name="location_id" required>
                <option value="">Select</option>
                <?php foreach ($locations as $l): ?><option value="<?= $l['id'] ?>"><?= e($l['name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="col-12"><label>Description</label><textarea class="form-control" name="description" rows="4" required></textarea></div>
        <div class="col-12"><label>Item image</label><input class="form-control" type="file" name="image" accept="image/*"></div>
        <div class="col-12"><button class="btn btn-primary mt-3">Submit Report</button></div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
