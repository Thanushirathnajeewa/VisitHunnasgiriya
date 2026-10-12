<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
$pageTitle = "My Day Plan";

$_SESSION['plan']     = $_SESSION['plan'] ?? [];
$_SESSION['start']    = $_SESSION['start'] ?? '08:00';
$_SESSION['lunch']    = $_SESSION['lunch'] ?? 45;      // minutes, 0 = no lunch
$plan = &$_SESSION['plan'];

/* ---------- actions (POST) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';
    $id     = (int)($_POST['id'] ?? 0);
    $pos    = array_search($id, $plan);

    if ($action === 'add' && $pos === false) {
        $st = $pdo->prepare("SELECT 1 FROM attractions WHERE attraction_id = ?"); $st->execute([$id]);
        if ($st->fetch()) { $plan[] = $id; flash_set("Added to your plan."); }
    } elseif ($action === 'remove' && $pos !== false) {
        array_splice($plan, $pos, 1);
    } elseif ($action === 'up' && $pos > 0) {
        [$plan[$pos - 1], $plan[$pos]] = [$plan[$pos], $plan[$pos - 1]];
    } elseif ($action === 'down' && $pos !== false && $pos < count($plan) - 1) {
        [$plan[$pos + 1], $plan[$pos]] = [$plan[$pos], $plan[$pos + 1]];
    } elseif ($action === 'clear') {
        $plan = [];
    } elseif ($action === 'settings') {
        if (preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $_POST['start'] ?? '')) $_SESSION['start'] = $_POST['start'];
        $_SESSION['lunch'] = isset($_POST['lunch']) ? 45 : 0;
    } elseif ($action === 'save' && $plan) {
        if (!is_user()) { flash_set("Please log in to save your plan.", 'err'); redirect('login.php?next=planner.php'); }
        $name = trim($_POST['name'] ?? '');
        $hasCol = (bool)$pdo->query("SHOW COLUMNS FROM itineraries LIKE 'user_id'")->fetch();
        if (!$hasCol) { flash_set("Run database/upgrade_users.sql in phpMyAdmin first.", 'err'); }
        elseif ($name === '' || str_len($name) > 150) { flash_set("Enter a plan name (up to 150 characters).", 'err'); }
        else {
            $pdo->prepare("INSERT INTO itineraries (itinerary_name, user_id) VALUES (?, ?)")->execute([$name, $_SESSION['user_id']]);
            $newId = (int)$pdo->lastInsertId();
            $ins = $pdo->prepare("INSERT INTO itinerary_items (itinerary_id, attraction_id, visit_order) VALUES (?,?,?)");
            foreach ($plan as $i => $aid) $ins->execute([$newId, $aid, $i + 1]);
            flash_set("Plan saved. Find it any time under My Plans.");
        }
    }
    redirect('planner.php');
}

/* ---------- load a saved plan (GET) ---------- */
if (isset($_GET['plan'])) {
    $st = $pdo->prepare("SELECT attraction_id FROM itinerary_items WHERE itinerary_id = ? ORDER BY visit_order");
    $st->execute([(int)$_GET['plan']]);
    $ids = $st->fetchAll(PDO::FETCH_COLUMN);
    if ($ids) { $plan = array_map('intval', $ids); flash_set("Saved plan loaded."); }
    else flash_set("Saved plan not found.", 'err');
    redirect('planner.php');
}

/* ---------- build the timetable ---------- */
$stops = [];
if ($plan) {
    $in = implode(',', array_fill(0, count($plan), '?'));
    $st = $pdo->prepare("SELECT attraction_id, name, distance_km, travel_time, estimated_visit_duration
                         FROM attractions WHERE attraction_id IN ($in)");
    $st->execute($plan);
    $byId = [];
    foreach ($st->fetchAll() as $r) $byId[$r['attraction_id']] = $r;

    [$h, $m] = array_map('intval', explode(':', $_SESSION['start']));
    $t = $h * 60 + $m;
    $prevKm = 0; $dist = 0; $lunchDone = false; $last = count($plan) - 1;

    foreach ($plan as $i => $aid) {
        if (!isset($byId[$aid])) continue;
        $a  = $byId[$aid];
        $km = (float)$a['distance_km'];
        $drive = $i === 0 ? minutes($a['travel_time'], 15) : max(5, (int)round(abs($km - $prevKm) * 3));
        $dist += $i === 0 ? $km : abs($km - $prevKm);
        $t += $drive;
        $stay = minutes($a['estimated_visit_duration'], 60);
        $stops[] = ['type' => 'visit', 'id' => $aid, 'time' => $t, 'name' => $a['name'], 'drive' => $drive, 'stay' => $stay];
        $t += $stay; $prevKm = $km;
        if ($_SESSION['lunch'] && !$lunchDone && $t >= 12 * 60 && $i < $last) {
            $stops[] = ['type' => 'lunch', 'time' => $t, 'stay' => $_SESSION['lunch']];
            $t += $_SESSION['lunch']; $lunchDone = true;
        }
    }
    $back = max(5, (int)round($prevKm * 3));
    $dist += $prevKm; $t += $back;
    $stops[] = ['type' => 'end', 'time' => $t, 'drive' => $back];
    $finish = $t;
}

include __DIR__ . '/includes/header.php';
?>
  <section>
    <a class="back" href="index.php#places">&larr; Add more places</a>
    <h2>My Day Plan</h2>
    <p class="sub">Your stops for the day, in order</p>
    <?= flash_show() ?>

    <?php if (!$plan): ?>
      <div class="box"><p>Your day is empty. <a href="index.php#places">Choose places to visit</a>.</p></div>
    <?php else: ?>
    <div class="detail">
      <div class="plan">
        <?php foreach ($stops as $s): ?>
          <div class="stop">
            <span class="time"><?= fmt_time($s['time']) ?></span>
            <div class="grow">
              <?php if ($s['type'] === 'visit'): ?>
                <b><a href="attraction.php?id=<?= $s['id'] ?>"><?= e($s['name']) ?></a></b><br>
                <small><?= $s['drive'] ?> min drive · stay about <?= $s['stay'] ?> min</small>
              <?php elseif ($s['type'] === 'lunch'): ?>
                <b>Lunch break</b><br><small><?= $s['stay'] ?> min</small>
              <?php else: ?>
                <b>Back in Hunnasgiriya</b><br><small><?= $s['drive'] ?> min drive</small>
              <?php endif; ?>
            </div>
            <?php if ($s['type'] === 'visit'): foreach (['up' => '↑', 'down' => '↓', 'remove' => 'Remove'] as $act => $label): ?>
              <form class="inl" method="post"><?= csrf_field() ?>
                <input type="hidden" name="action" value="<?= $act ?>"><input type="hidden" name="id" value="<?= $s['id'] ?>">
                <button class="btn light" type="submit" aria-label="<?= $act ?>"><?= $label ?></button>
              </form>
            <?php endforeach; endif; ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div>
        <form class="box white" method="post">
          <?= csrf_field() ?><input type="hidden" name="action" value="settings">
          <h3>Plan settings</h3>
          <p>Start time</p>
          <p><input type="time" name="start" value="<?= e($_SESSION['start']) ?>"></p>
          <p><label><input type="checkbox" name="lunch" <?= $_SESSION['lunch'] ? 'checked' : '' ?>> Include lunch break</label></p>
          <button class="btn" type="submit">Update</button>
        </form>

        <div class="summary">
          <div><b><?= count($plan) ?></b>stops</div>
          <div><b>~<?= round($dist) ?> km</b>distance</div>
          <div><b><?= fmt_time($finish) ?></b>finish</div>
        </div>
        <p class="note">Times are estimates. Check road and weather before you travel.</p>

        <?php if (is_user()): ?>
        <form class="box white" method="post">
          <?= csrf_field() ?><input type="hidden" name="action" value="save">
          <h3>Save this plan</h3>
          <p><input type="text" name="name" maxlength="150" placeholder="Plan name" required></p>
          <button class="btn" type="submit">Save plan</button>
        </form>
        <?php else: ?>
        <div class="box white">
          <h3>Save this plan</h3>
          <p><a href="login.php?next=planner.php">Log in</a> or <a href="register.php?next=planner.php">register</a> to save your plan and open it later.</p>
        </div>
        <?php endif; ?>
        <div class="btn-row">
          <button class="btn light" type="button" onclick="window.print()">Print plan</button>
          <form class="inl" method="post"><?= csrf_field() ?><input type="hidden" name="action" value="clear">
            <button class="btn light" type="submit">Clear plan</button></form>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
