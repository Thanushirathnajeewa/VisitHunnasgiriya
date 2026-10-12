<?php
require __DIR__ . '/../config/db.php';
$pageTitle = "Dashboard";
include __DIR__ . '/../includes/admin_header.php';

$count = fn($sql) => (int)$pdo->query($sql)->fetchColumn();
$stats = [
  ['📍', $count("SELECT COUNT(*) FROM attractions"), 'Places', 'places.php'],
  ['🗂️', $count("SELECT COUNT(*) FROM categories"), 'Categories', 'categories.php'],
  ['🖼️', $count("SELECT COUNT(*) FROM attraction_images"), 'Photos', 'places.php'],
  ['💡', $count("SELECT COUNT(*) FROM travel_tips"), 'Tips', 'tips.php'],
  ['🍽️', $count("SELECT COUNT(*) FROM restaurants"), 'Restaurants', 'restaurants.php'],
  ['🚑', $count("SELECT COUNT(*) FROM emergency_contacts"), 'Emergency contacts', 'contacts.php'],
  ['🗓️', $count("SELECT COUNT(*) FROM itineraries"), 'Saved day plans', 'dashboard.php'],
];
?>
  <section>
    <h2>Dashboard</h2>
    <p class="sub">Welcome back. Manage everything shown on the site.</p>
    <?= flash_show() ?>
    <div class="stats">
      <?php foreach ($stats as [$icon, $n, $label, $link]): ?>
        <a class="stat" href="<?= $link ?>" style="text-decoration:none"><span><?= $icon ?></span><b><?= $n ?></b><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
    <p style="margin-top:24px"><a class="btn" href="place-form.php">+ Add a place</a></p>
  </section>
</body>
</html>
