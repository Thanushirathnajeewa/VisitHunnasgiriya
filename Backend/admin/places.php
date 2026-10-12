<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

/* ---------- delete a place and everything linked to it ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $id = (int)($_POST['id'] ?? 0);
    $st = $pdo->prepare("SELECT image_path FROM attraction_images WHERE attraction_id = ?"); $st->execute([$id]);
    $paths = $st->fetchAll(PDO::FETCH_COLUMN);
    foreach (['attraction_images', 'travel_tips', 'restaurants', 'itinerary_items'] as $t)
        $pdo->prepare("DELETE FROM $t WHERE attraction_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM attractions WHERE attraction_id = ?")->execute([$id]);
    foreach ($paths as $p) {                         // remove photo files nobody else uses
        $used = $pdo->prepare("SELECT COUNT(*) FROM attraction_images WHERE image_path = ?"); $used->execute([$p]);
        $file = dirname(__DIR__) . '/' . $p;
        if (!$used->fetchColumn() && strpos($p, 'assets/images/') === 0 && is_file($file)) @unlink($file);
    }
    flash_set("Place deleted.");
    redirect('places.php');
}

$rows = $pdo->query("SELECT a.attraction_id, a.name, a.distance_km, c.category_name,
          (SELECT COUNT(*) FROM attraction_images i WHERE i.attraction_id = a.attraction_id) AS photos
        FROM attractions a JOIN categories c ON c.category_id = a.category_id ORDER BY a.distance_km")->fetchAll();

$pageTitle = "Places";
include __DIR__ . '/../includes/admin_header.php';
?>
  <section>
    <h2>Places</h2>
    <p class="sub">Add, edit or delete tourist places</p>
    <?= flash_show() ?>
    <div class="admin">
      <p><a class="btn" href="place-form.php">+ Add a place</a></p>
      <table>
        <tr><th>Place</th><th>Category</th><th>km</th><th>Photos</th><th>Actions</th></tr>
        <?php foreach ($rows as $r): ?>
          <tr>
            <td><?= e($r['name']) ?></td><td><?= e($r['category_name']) ?></td>
            <td><?= e(rtrim(rtrim($r['distance_km'], '0'), '.')) ?></td><td><?= (int)$r['photos'] ?></td>
            <td>
              <a class="btn light" href="place-form.php?id=<?= $r['attraction_id'] ?>">Edit</a>
              <form method="post" onsubmit="return confirm('Delete this place and its photos, tips and restaurants?')">
                <?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['attraction_id'] ?>">
                <button class="btn light" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </section>
</body>
</html>
