<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare("SELECT a.*, c.category_name FROM attractions a
                     JOIN categories c ON c.category_id = a.category_id WHERE a.attraction_id = ?");
$st->execute([$id]);
$a = $st->fetch();
if (!$a) { http_response_code(404); exit("Attraction not found. <a href='index.php'>Back</a>"); }

$q = $pdo->prepare("SELECT * FROM attraction_images WHERE attraction_id = ? ORDER BY image_id"); $q->execute([$id]); $images = $q->fetchAll();
$q = $pdo->prepare("SELECT * FROM travel_tips WHERE attraction_id = ? ORDER BY tip_type, tip_id"); $q->execute([$id]); $tips = $q->fetchAll();
$q = $pdo->prepare("SELECT * FROM restaurants WHERE attraction_id = ? ORDER BY name"); $q->execute([$id]); $restaurants = $q->fetchAll();

$pageTitle = $a['name'];
$inPlan = in_array($id, $_SESSION['plan'] ?? []);
include __DIR__ . '/includes/header.php';
?>
  <section>
    <a class="back" href="index.php#places">&larr; Back to all places</a>

    <article class="place show">
      <span class="tag"><?= e($a['category_name']) ?> · <?= e(rtrim(rtrim($a['distance_km'], '0'), '.')) ?> km</span>
      <h2><?= e($a['name']) ?></h2>
      <p class="lead"><?= nl2br(e($a['description'])) ?></p>

      <div class="detail">
        <div>
          <?php if ($images): ?>
            <div class="gallery">
              <?php foreach ($images as $im): ?>
                <img src="<?= e(img_url($im['image_path'])) ?>" alt="<?= e($a['name']) ?>" onerror="this.style.display='none'">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          <div class="facts">
            <div><small>Location</small><?= e($a['location']) ?></div>
            <div><small>Opening hours</small><?= e($a['opening_hours']) ?></div>
            <div><small>Best time</small><?= e($a['best_time_to_visit']) ?></div>
            <div><small>Visit time</small><?= e($a['estimated_visit_duration']) ?></div>
            <div><small>Travel time</small><?= e($a['travel_time']) ?></div>
            <div><small>Road condition</small><?= nl2br(e($a['road_condition'])) ?></div>
          </div>
        </div>

        <div>
          <iframe class="map" loading="lazy" title="Map of <?= e($a['name']) ?>"
                  src="https://www.google.com/maps?q=<?= urlencode($a['name'] . ' ' . $a['location']) ?>&output=embed"></iframe>
          <form class="inl" method="post" action="planner.php">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="id" value="<?= $id ?>">
            <?php if ($inPlan): ?><a class="btn light" href="planner.php">In your plan ✓</a>
            <?php else: ?><button class="btn" type="submit">Add to Day Plan</button><?php endif; ?>
          </form>
          <a class="btn light" target="_blank" rel="noopener" href="<?= e(maps_link($a)) ?>">Open in Google Maps</a>
        </div>
      </div>

      <div class="two">
        <div class="box">
          <h3>Travel &amp; safety tips</h3>
          <?php if ($tips): ?>
            <ul><?php foreach ($tips as $t): ?>
              <li><strong><?= e($t['tip_type']) ?>:</strong> <?= e($t['tip_description']) ?></li>
            <?php endforeach; ?></ul>
          <?php else: ?><p>No tips have been added yet.</p><?php endif; ?>
        </div>
        <div class="box">
          <h3>Nearby restaurants &amp; cafés</h3>
          <?php if ($restaurants): ?>
            <ul><?php foreach ($restaurants as $r): ?>
              <li><strong><?= e(trim($r['name'])) ?></strong> — <?= e(trim($r['location'])) ?>
                  <?php if ($r['estimated_spending'] !== null): ?> — about Rs. <?= number_format((float)$r['estimated_spending']) ?><?php endif; ?>
                  <?php if ($r['description']): ?><br><small><?= e($r['description']) ?></small><?php endif; ?></li>
            <?php endforeach; ?></ul>
          <?php else: ?><p>No restaurant information has been added yet.</p><?php endif; ?>
        </div>
      </div>
    </article>
  </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
