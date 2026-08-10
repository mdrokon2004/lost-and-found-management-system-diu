<?php
require_once __DIR__.'/config/config.php'; require_once __DIR__.'/config/db.php';
$page_title='Browse Found Items'; include __DIR__.'/includes/header.php';
$q=trim($_GET['q']??''); $cat=(int)($_GET['category']??0); $loc=(int)($_GET['location']??0);
$sql="SELECT l.*,c.name category_name,loc.name location_name FROM found_items l JOIN categories c ON c.id=l.category_id JOIN locations loc ON loc.id=l.location_id JOIN statuses s ON s.id=l.status_id WHERE s.code='approved'";
$params=[];
if($q!==''){ $sql.=" AND (l.title LIKE ? OR l.description LIKE ?)"; $params[]="%$q%"; $params[]="%$q%"; }
if($cat){$sql.=" AND l.category_id=?";$params[]=$cat;} if($loc){$sql.=" AND l.location_id=?";$params[]=$loc;}
$sql.=" ORDER BY l.created_at DESC"; $st=$pdo->prepare($sql);$st->execute($params);$items=$st->fetchAll();
$categories=$pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();$locations=$pdo->query("SELECT * FROM locations ORDER BY name")->fetchAll();
?>
<div class="py-4"><div class="mb-4"><div class="text-primary fw-bold">DISCOVER</div><h1 class="section-title">Found items</h1><p class="text-secondary">Search by item name, description, category or location.</p></div>
<form class="glass-panel p-3 mb-4"><div class="row g-2"><div class="col-lg-5"><input class="form-control" name="q" value="<?=e($q)?>" placeholder="Search items..."></div><div class="col-md-3"><select class="form-select" name="category"><option value="0">All categories</option><?php foreach($categories as $c):?><option value="<?=$c['id']?>" <?=$cat==$c['id']?'selected':''?>><?=e($c['name'])?></option><?php endforeach;?></select></div><div class="col-md-3"><select class="form-select" name="location"><option value="0">All locations</option><?php foreach($locations as $l):?><option value="<?=$l['id']?>" <?=$loc==$l['id']?'selected':''?>><?=e($l['name'])?></option><?php endforeach;?></select></div><div class="col-md-1"><button class="btn btn-primary-glass w-100 h-100"><i class="bi bi-search"></i></button></div></div></form>
<div class="row g-4"><?php foreach($items as $item):?><div class="col-md-6 col-lg-4"><?php include __DIR__.'/item_card.php';?></div><?php endforeach;?></div>
<?php if(!$items):?><div class="glass-panel p-5 text-center mt-4"><i class="bi bi-search fs-1 text-secondary"></i><h4 class="mt-3">No lost items found</h4><p class="text-secondary">Try another search or filter.</p></div><?php endif;?></div>
<?php include __DIR__.'/includes/footer.php'; ?>
