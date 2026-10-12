<?php
require __DIR__ . '/../config/db.php';
require __DIR__ . '/../includes/auth.php';
require_admin();

$id   = (int)($_GET['id'] ?? 0);
$cats = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name")->fetchAll(PDO::FETCH_KEY_PAIR);
$text = ['name' => 150, 'location' => 255, 'opening_hours' => 100, 'best_time_to_visit' => 150,
         'estimated_visit_duration' => 100, 'travel_time' => 100];       // short text fields and max length
$a = array_fill_keys(array_merge(array_keys($text), ['category_id', 'description', 'distance_km', 'google_maps_link', 'road_condition']), '');

if ($id) {
    $st = $pdo->prepare("SELECT * FROM attractions WHERE attraction_id = ?"); $st->execute([$id]);
    $a = $st->fetch();
    if (!$a) { flash_set("Place not found.", 'err'); redirect('places.php'); }
    $a = array_map(fn($v) => (string)$v, $a);       // NULL columns become empty text
}
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach (array_keys($a) as $k) if (isset($_POST[$k])) $a[$k] = trim($_POST[$k]);

    if ($a['name'] === '') $errors[] = "Place name is required.";
    foreach ($text as $k => $max) if (str_len($a[$k]) > $max) $errors[] = "$k is too long (max $max).";
    if (!isset($cats[(int)$a['category_id']])) $errors[] = "Choose a category.";
    if (!is_numeric($a['distance_km']) || $a['distance_km'] < 0 || $a['distance_km'] > MAX_KM)
        $errors[] = "Distance must be a number from 0 to " . MAX_KM . " km.";
    if ($a['google_maps_link'] !== '' && !preg_match('~^https?://~i', $a['google_maps_link'])) $errors[] = "Google Maps link must start with http:// or https://";

    if (!$errors) {
        $cols = array_merge(array_keys($text), ['category_id', 'description', 'distance_km', 'google_maps_link', 'road_condition']);
        $vals = array_map(fn($k) => $a[$k] === '' && $k !== 'name' ? null : $a[$k], $cols);
        if ($id) {
            $pdo->prepare("UPDATE attractions SET " . implode(',', array_map(fn($c) => "$c=?", $cols)) . " WHERE attraction_id=?")
                ->execute([...$vals, $id]);
        } else {
            $pdo->prepare("INSERT INTO attractions (" . implode(',', $cols) . ") VALUES (" . implode(',', array_fill(0, count($cols), '?')) . ")")
                ->execute($vals);
            $id = (int)$pdo->lastInsertId();
        }
        /* remove ticked photos */
        foreach ((array)($_POST['delete_images'] ?? []) as $imgId) {
            $st = $pdo->prepare("SELECT image_path FROM attraction_images WHERE image_id=? AND attraction_id=?");
            $st->execute([(int)$imgId, $id]);
            if ($p = $st->fetchColumn()) {
                $pdo->prepare("DELETE FROM attraction_images WHERE image_id=?")->execute([(int)$imgId]);
                $used = $pdo->prepare("SELECT COUNT(*) FROM attraction_images WHERE image_path=?"); $used->execute([$p]);
                $file = dirname(__DIR__) . '/' . $p;
                if (!$used->fetchColumn() && strpos($p, 'assets/images/') === 0 && is_file($file)) @unlink($file);
            }
        }
        /* new photos */
        [$saved, $upErr] = save_uploads($_FILES['photos'] ?? [], preg_replace('~[^a-z0-9]+~', '-', strtolower($a['name'])));
        foreach ($saved as $p) $pdo->prepare("INSERT INTO attraction_images (attraction_id, image_path) VALUES (?,?)")->execute([$id, $p]);

        flash_set($upErr ? "Place saved, but: " . implode(' ', $upErr) : "Place saved.", $upErr ? 'err' : 'ok');
        redirect('places.php');
    }
}

$images = [];
if ($id) { $st = $pdo->prepare("SELECT * FROM attraction_images WHERE attraction_id=? ORDER BY image_id"); $st->execute([$id]); $images = $st->fetchAll(); }

$pageTitle = $id ? "Edit place" : "Add place";
include __DIR__ . '/../includes/admin_header.php';
$field = fn($k, $label, $type = 'text') => '<label>' . $label . '<input type="' . $type . '" name="' . $k . '" value="' . e($a[$k]) . '"' . ($type === 'number' ? ' step="0.01" min="0" max="' . MAX_KM . '"' : '') . '></label>';
?>
  <section>
    <a class="back" href="places.php">&larr; Back to places</a>
    <h2><?= $pageTitle ?></h2>
    <p class="sub">Fill in the details and save</p>
    <?php foreach ($errors as $er): ?><div class="msg err"><?= e($er) ?></div><?php endforeach; ?>

    <div class="admin">
      <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="form-grid">
          <?= $field('name', 'Place name') ?>
          <label>Category
            <select name="category_id"><option value="">Choose...</option>
              <?php foreach ($cats as $cid => $cn): ?><option value="<?= $cid ?>" <?= (int)$a['category_id'] === $cid ? 'selected' : '' ?>><?= e($cn) ?></option><?php endforeach; ?>
            </select></label>
          <?= $field('distance_km', 'Distance (km)', 'number') ?>
          <?= $field('location', 'Location / address') ?>
          <?= $field('opening_hours', 'Opening hours') ?>
          <?= $field('best_time_to_visit', 'Best time to visit') ?>
          <?= $field('estimated_visit_duration', 'Visit duration (e.g. 1-2 hours)') ?>
          <?= $field('travel_time', 'Travel time (e.g. 20 minutes)') ?>
          <?= $field('google_maps_link', 'Google Maps link') ?>
          <label>Description<textarea name="description"><?= e($a['description']) ?></textarea></label>
          <label>Road condition<textarea name="road_condition"><?= e($a['road_condition']) ?></textarea></label>
        </div>

        <?php if ($images): ?>
          <h3>Current photos <small>(tick to remove)</small></h3>
          <div class="thumbs">
            <?php foreach ($images as $im): ?>
              <div><img src="../<?= e(img_url($im['image_path'])) ?>" alt="" onerror="this.style.display='none'">
                <label><input type="checkbox" name="delete_images[]" value="<?= $im['image_id'] ?>"> Remove</label></div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <p><label>Add photos (JPG, PNG or WEBP, up to 5 MB each)<br>
          <input type="file" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple></label></p>

        <button class="btn" type="submit">Save place</button>
        <a class="btn light" href="places.php">Cancel</a>
      </form>
    </div>
  </section>
</body>
</html>
