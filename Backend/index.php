<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
$pageTitle = "Home";

$q   = trim($_GET['q'] ?? '');
$cat = (int)($_GET['category'] ?? 0);

$cats = $pdo->query("SELECT * FROM categories ORDER BY category_name")->fetchAll();

$sql = "SELECT a.*, c.category_name,
          (SELECT i.image_path FROM attraction_images i WHERE i.attraction_id = a.attraction_id
           ORDER BY i.image_id LIMIT 1) AS image_path
        FROM attractions a JOIN categories c ON c.category_id = a.category_id WHERE 1=1";
$params = [];
if ($q !== '') {
    $sql .= " AND (a.name LIKE ? OR a.description LIKE ? OR a.location LIKE ?)";
    $params = ["%$q%", "%$q%", "%$q%"];
}
if ($cat) { $sql .= " AND a.category_id = ?"; $params[] = $cat; }
$sql .= " ORDER BY a.distance_km";
$st = $pdo->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll();
$inPlan = $_SESSION['plan'] ?? [];

include __DIR__ . '/includes/header.php';
?>
  <div class="hero">
    <h1>One day in Hunnasgiriya</h1>
    <p>Discover places within 25 km and plan your day.</p>
    <form class="searchbar" method="get" action="index.php#places">
      <input type="text" name="q" value="<?= e($q) ?>" placeholder="Search a waterfall, viewpoint, temple...">
      <select name="category">
        <option value="0">All categories</option>
        <?php foreach ($cats as $c): ?>
          <option value="<?= $c['category_id'] ?>" <?= $cat == $c['category_id'] ? 'selected' : '' ?>><?= e($c['category_name']) ?></option>
        <?php endforeach; ?>
      </select>
      <button class="btn" type="submit">Search</button>
    </form>
  </div>

  <div class="chips">
    <a class="chip <?= !$cat ? 'active' : '' ?>" href="index.php#places">All</a>
    <?php foreach ($cats as $c): ?>
      <a class="chip <?= $cat == $c['category_id'] ? 'active' : '' ?>" href="index.php?category=<?= $c['category_id'] ?>#places"><?= e($c['category_name']) ?></a>
    <?php endforeach; ?>
  </div>

  <section id="places">
    <?= flash_show() ?>
    <h2>Places to visit</h2>
    <p class="sub"><?= count($rows) ?> place<?= count($rows) === 1 ? '' : 's' ?> within <?= MAX_KM ?> km of Hunnasgiriya</p>

    <div class="cards">
      <?php foreach ($rows as $a): ?>
        <div class="card <?= cat_class($a['category_name']) ?>">
          <div class="thumb"><?= cat_icon($a['category_name']) ?>
            <?php if ($a['image_path']): ?>
              <img src="<?= e(img_url($a['image_path'])) ?>" alt="<?= e($a['name']) ?>" onerror="this.style.display='none'">
            <?php endif; ?>
          </div>
          <div class="info">
            <span class="tag"><?= e($a['category_name']) ?> · <?= e(rtrim(rtrim($a['distance_km'], '0'), '.')) ?> km</span>
            <h3><?= e($a['name']) ?></h3>
            <p><?= e(shorten($a['description'] ?? '', 110)) ?></p>
            <form class="inl" method="post" action="planner.php">
              <?= csrf_field() ?>
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="id" value="<?= $a['attraction_id'] ?>">
              <?php if (in_array($a['attraction_id'], $inPlan)): ?>
                <a class="btn light" href="planner.php">In your plan ✓</a>
              <?php else: ?>
                <button class="btn" type="submit">Add to plan</button>
              <?php endif; ?>
            </form>
            <a class="btn light" href="attraction.php?id=<?= $a['attraction_id'] ?>">Details</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (!$rows): ?>
      <div class="box"><p>No places found. <a href="index.php">Show all places</a></p></div>
    <?php endif; ?>
  </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
