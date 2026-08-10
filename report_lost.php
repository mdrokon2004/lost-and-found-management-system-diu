<?php
require_once __DIR__.'/config/config.php'; require_once __DIR__.'/config/db.php'; require_once __DIR__.'/includes/auth.php'; require_login();
$type='lost'; $page_title='Report '.ucfirst($type).' Item'; $datecol=$type==='lost'?'lost_date':'found_date'; $table=$type==='lost'?'lost_items':'found_items';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $title=trim($_POST['title']??'');$description=trim($_POST['description']??'');$cat=(int)$_POST['category_id'];$loc=(int)$_POST['location_id'];$date=$_POST['item_date']??'';
 $status=$pdo->query("SELECT id FROM statuses WHERE code='pending' LIMIT 1")->fetchColumn();$imagePath=null;
 if(isset($_FILES['image'])&&$_FILES['image']['error']===UPLOAD_ERR_OK){
   $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];$mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image']['tmp_name']);
   if(!isset($allowed[$mime])||$_FILES['image']['size']>3*1024*1024){flash('danger','Image must be JPG, PNG or WEBP and under 3MB.');}
   else{$name=bin2hex(random_bytes(12)).'.'.$allowed[$mime];$dest=__DIR__.'/uploads/'.$name;if(move_uploaded_file($_FILES['image']['tmp_name'],$dest))$imagePath='uploads/'.$name;}
 }
 if($title&&$description&&$cat&&$loc&&$date){
   $s=$pdo->prepare("INSERT INTO $table(user_id,title,description,category_id,location_id,$datecol,status_id,image_path) VALUES(?,?,?,?,?,?,?,?)");
   $s->execute([$_SESSION['user']['id'],$title,$description,$cat,$loc,$date,$status,$imagePath]);
   if ($type === 'lost') {
       notify_admins($pdo, 'New Lost Item Report', $_SESSION['user']['name'] . ' reported a lost item: "' . $title . '". Pending review.');
   }
   flash('success','Your report has been submitted for admin review.');redirect('user/dashboard.php');
 } else flash('danger','Please complete all required fields.');
}
$categories=$pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();$locations=$pdo->query("SELECT * FROM locations ORDER BY name")->fetchAll();
include __DIR__.'/includes/header.php'; ?>
<div class="row justify-content-center py-4"><div class="col-xl-8"><div class="form-card p-4 p-lg-5"><div class="text-primary fw-bold">NEW REPORT</div><h2 class="section-title">Report a <?=ucfirst($type)?> Item</h2><p class="text-secondary">Provide accurate details so the community can identify the item.</p><form method="post" enctype="multipart/form-data" class="row g-3 mt-2"><div class="col-md-8"><label class="form-label">Item title</label><input class="form-control" name="title" required></div><div class="col-md-4"><label class="form-label">Date</label><input class="form-control" type="date" name="item_date" required></div><div class="col-md-6"><label class="form-label">Category</label><select class="form-select" name="category_id" required><option value="">Select</option><?php foreach($categories as $c):?><option value="<?=$c['id']?>"><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col-md-6"><label class="form-label">Location</label><select class="form-select" name="location_id" required><option value="">Select</option><?php foreach($locations as $l):?><option value="<?=$l['id']?>"><?=e($l['name'])?></option><?php endforeach;?></select></div><div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5" required></textarea></div><div class="col-12"><label class="form-label">Item image <span class="text-secondary">(optional)</span></label><input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp"></div><div class="col-12 mt-4"><button class="btn btn-primary-glass px-4 py-3">Submit report <i class="bi bi-arrow-up-right"></i></button></div></form></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
