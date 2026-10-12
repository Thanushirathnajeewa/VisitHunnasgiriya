<?php
// Visitor header. Set $pageTitle before including.
require_once __DIR__ . '/functions.php';
$planCount = count($_SESSION['plan'] ?? []);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? 'VisitHunnasgiriya') ?> - VisitHunnasgiriya</title>
  <link rel="icon" href="logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <a class="brand" href="index.php"><img src="logo.png" alt="Logo"> VisitHunnasgiriya</a>
    <nav>
      <a href="index.php#places">Explore</a>
      <a href="planner.php">My Day Plan<?= $planCount ? " ($planCount)" : '' ?></a>
      <a href="emergency.php">Emergency</a>
      <?php if (is_admin()): ?>
        <a href="admin/dashboard.php">Admin Panel</a>
        <a href="logout.php">Logout</a>
      <?php elseif (is_user()): ?>
        <a href="my-plans.php">My Plans</a>
        <a href="logout.php">Logout (<?= e(strtok($_SESSION['user_name'] ?? 'You', ' ')) ?>)</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a class="cta" href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>
