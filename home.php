<?php
require "config/db.php";
$pageTitle="Home";
$cats=$pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();
$featured=$pdo->query("SELECT a.*,c.category_name FROM attractions a JOIN categories c ON a.category_id=c.category_id ORDER BY a.distance_km LIMIT 6")->fetchAll();
include "includes/header.php";
?>
<section class="hero">
<div class="container"><div class="hero-content">
<h1>Explore Hunnasgiriya in One Day</h1>
<p>Discover nature, religious and historical attractions around Hunnasgiriya, compare places, view travel information and create your own day itinerary.</p>
<div class="hero-actions"><a class="btn" href="attractions.php">Explore Attractions</a><a class="btn secondary" href="planner.php">Plan My Day</a></div>
</div></div>
</section>
<section class="section">
<div class="container"><div class="section-heading"><span class="section-label">EXPLORE</span><h2>Browse by Category</h2></div>
<div class="grid">
<?php foreach($cats as $c): ?><div class="card"><span class="tag"><?=htmlspecialchars($c['category_name'])?></span><h3><?=htmlspecialchars($c['category_name'])?> Places</h3><a class="btn" href="attractions.php?category=<?=$c['category_id']?>">View Places</a></div><?php endforeach;?>
</div></div>
</section>
<section class="section attractions-section">
<div class="container"><div class="section-heading"><span class="section-label">DISCOVER</span><h2>Nearby Attractions</h2></div>
<div class="grid">
<?php foreach($featured as $a): ?><article class="card"><span class="tag"><?=htmlspecialchars($a['category_name'])?></span><h3><?=htmlspecialchars($a['name'])?></h3><p><?=htmlspecialchars($a['description'])?></p><p class="meta"><?=htmlspecialchars($a['distance_km'])?> km · <?=htmlspecialchars($a['travel_time'])?></p><a class="btn" href="attraction.php?id=<?=$a['attraction_id']?>">View Details</a></article><?php endforeach;?>
</div></div>
</section>
<?php include "includes/footer.php"; ?>