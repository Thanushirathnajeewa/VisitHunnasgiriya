<?php
require "config/db.php";
$id=(int)($_GET['id']??0);
$stmt=$pdo->prepare("SELECT a.*,c.category_name FROM attractions a JOIN categories c ON a.category_id=c.category_id WHERE a.attraction_id=?");
$stmt->execute([$id]);$a=$stmt->fetch();
if(!$a){http_response_code(404);exit("Attraction not found.");}
$tip=$pdo->prepare("SELECT * FROM travel_tips WHERE attraction_id=? ORDER BY tip_type");$tip->execute([$id]);$tips=$tip->fetchAll();
$rest=$pdo->prepare("SELECT * FROM restaurants WHERE attraction_id=?");$rest->execute([$id]);$restaurants=$rest->fetchAll();
$imgs=$pdo->prepare("SELECT * FROM attraction_images WHERE attraction_id=?");$imgs->execute([$id]);$images=$imgs->fetchAll();
$pageTitle=$a['name'];include "includes/header.php";
?>
<section class="section container"><div class="detail"><div class="panel">
<span class="tag"><?=htmlspecialchars($a['category_name'])?></span><h1><?=htmlspecialchars($a['name'])?></h1><p><?=nl2br(htmlspecialchars($a['description']))?></p>
<p><strong>Location:</strong> <?=htmlspecialchars($a['location'])?></p><p><strong>Opening hours:</strong> <?=htmlspecialchars($a['opening_hours'])?></p><p><strong>Best time:</strong> <?=htmlspecialchars($a['best_time_to_visit'])?></p><p><strong>Visit duration:</strong> <?=htmlspecialchars($a['estimated_visit_duration'])?></p><p><strong>Distance:</strong> <?=htmlspecialchars($a['distance_km'])?> km</p><p><strong>Travel time:</strong> <?=htmlspecialchars($a['travel_time'])?></p><p><strong>Road condition:</strong> <?=nl2br(htmlspecialchars($a['road_condition']))?></p>
<a class="btn" href="planner.php?add=<?=$id?>">Add to Day Plan</a> <a class="btn secondary" target="_blank" href="<?=htmlspecialchars($a['google_maps_link'])?>">Open Google Maps</a>
</div><div class="panel"><h2>Map</h2><iframe class="map" src="https://www.google.com/maps?q=<?=urlencode($a['location'])?>&output=embed" loading="lazy"></iframe>
<?php if($images):?><h2>Photos</h2><?php foreach($images as $im):?><img style="max-width:100%;border-radius:8px;margin-bottom:8px" src="<?=htmlspecialchars($im['image_path'])?>" alt="<?=htmlspecialchars($a['name'])?>"><?php endforeach;endif;?></div></div>
<div class="grid" style="margin-top:25px"><div class="card"><h2>Travel & Safety Tips</h2><?php if($tips):?><ul><?php foreach($tips as $t):?><li><strong><?=htmlspecialchars($t['tip_type'])?>:</strong> <?=htmlspecialchars($t['tip_description'])?></li><?php endforeach;?></ul><?php else:?><p>No tips have been added yet.</p><?php endif;?></div>
<div class="card"><h2>Nearby Restaurants & Cafés</h2><?php if($restaurants):?><ul><?php foreach($restaurants as $r):?><li><strong><?=htmlspecialchars($r['name'])?></strong> — <?=htmlspecialchars($r['location'])?> — Estimated Rs. <?=htmlspecialchars($r['estimated_spending'])?></li><?php endforeach;?></ul><?php else:?><p>No restaurant information has been added yet.</p><?php endif;?></div></div>
</section>
<?php include "includes/footer.php"; ?>