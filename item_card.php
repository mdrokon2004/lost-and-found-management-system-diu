<div class="item-card">
  <?php if(!empty($item['image_path'])): ?><img class="w-100 item-thumb" src="<?=BASE_URL.'/'.e($item['image_path'])?>" alt="<?=e($item['title'])?>"><?php else: ?><div class="placeholder-thumb"><i class="bi bi-image"></i></div><?php endif; ?>
  <div class="p-4">
    <div class="d-flex justify-content-between gap-2"><span class="badge rounded-pill badge-soft"><?=e($item['category_name'])?></span><span class="small text-secondary"><?=date('d M Y',strtotime($item['created_at']))?></span></div>
    <h5 class="mt-3 mb-2"><?=e($item['title'])?></h5>
    <p class="small text-secondary mb-3"><?=e(mb_strimwidth($item['description'],0,100,'…'))?></p>
    <div class="small text-secondary mb-3"><i class="bi bi-geo-alt"></i> <?=e($item['location_name'])?></div>
    <a class="btn btn-glass w-100" href="<?=BASE_URL?>/item.php?type=<?=isset($item['lost_date'])?'lost':'found'?>&id=<?=$item['id']?>">View details</a>
  </div>
</div>
