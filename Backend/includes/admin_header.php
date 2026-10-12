<?php
// Admin header. Set $pageTitle before including.
require_once __DIR__ . '/auth.php';
require_admin();
$here = basename($_SERVER['SCRIPT_NAME']);
$links = ['dashboard.php' => 'Dashboard', 'places.php' => 'Places', 'categories.php' => 'Categories', 'tips.php' => 'Tips',
          'restaurants.php' => 'Restaurants', 'contacts.php' => 'Emergency'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'Admin') ?> - Admin</title>
  <link rel="icon" href="../logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../style.css">
</head>
<body>
  <header>
    <div class="brand"><img src="../logo.png" alt="Logo"> Admin Panel</div>
    <nav>
      <?php foreach ($links as $file => $label): ?>
        <a href="<?= $file ?>" class="<?= $here === $file || ($file === 'places.php' && $here === 'place-form.php') ? 'active' : '' ?>"><?= $label ?></a>
      <?php endforeach; ?>
      <a href="../index.php">View Site</a>
      <a href="../change-password.php">Password</a>
      <a href="../logout.php">Logout</a>
    </nav>
  </header>
