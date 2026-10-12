<?php
// A visitor's saved day plans.
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
require_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pid = (int)($_POST['id'] ?? 0);
    $st = $pdo->prepare("SELECT 1 FROM itineraries WHERE itinerary_id = ? AND user_id = ?"); $st->execute([$pid, $_SESSION['user_id']]);
    if ($st->fetch()) {
        $pdo->prepare("DELETE FROM itinerary_items WHERE itinerary_id = ?")->execute([$pid]);
        $pdo->prepare("DELETE FROM itineraries WHERE itinerary_id = ?")->execute([$pid]);
        flash_set("Plan deleted.");
    }
    redirect('my-plans.php');
}

try {
    $st = $pdo->prepare("SELECT i.itinerary_id, i.itinerary_name, i.created_at,
                           (SELECT COUNT(*) FROM itinerary_items x WHERE x.itinerary_id = i.itinerary_id) AS stops
                         FROM itineraries i WHERE i.user_id = ? ORDER BY i.created_at DESC");
    $st->execute([$_SESSION['user_id']]);
    $plans = $st->fetchAll();
} catch (PDOException $e) { $plans = []; flash_set("Run database/upgrade_users.sql in phpMyAdmin first.", 'err'); }

$pageTitle = "My Plans";
include __DIR__ . '/includes/header.php';
?>
  <section>
    <h2>My saved plans</h2>
    <p class="sub">Plans you saved from the day planner</p>
    <?= flash_show() ?>
    <?php if (!$plans): ?>
      <div class="box"><p>You have no saved plans yet. <a href="index.php#places">Choose places</a>, build your day, then press "Save plan".</p></div>
    <?php else: ?>
      <div class="plan">
        <?php foreach ($plans as $p): ?>
          <div class="stop">
            <div class="grow"><b><?= e($p['itinerary_name']) ?></b><br>
              <small><?= (int)$p['stops'] ?> stops · saved <?= e(substr($p['created_at'], 0, 10)) ?></small></div>
            <a class="btn" href="planner.php?plan=<?= $p['itinerary_id'] ?>">Open</a>
            <form class="inl" method="post" onsubmit="return confirm('Delete this plan?')">
              <?= csrf_field() ?><input type="hidden" name="id" value="<?= $p['itinerary_id'] ?>">
              <button class="btn light" type="submit">Delete</button></form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
