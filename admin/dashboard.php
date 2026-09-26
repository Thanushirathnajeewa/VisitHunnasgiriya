<?php
require "../config/db.php";require "auth.php";
$n=$pdo->query("SELECT COUNT(*) FROM attractions")->fetchColumn();$c=$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();$r=$pdo->query("SELECT COUNT(*) FROM restaurants")->fetchColumn();$e=$pdo->query("SELECT COUNT(*) FROM emergency_contacts")->fetchColumn();
$pageTitle="Admin Dashboard";include "header.php";?>
<section class="admin-shell container"><h1>Admin Dashboard</h1><p>Welcome, <?=htmlspecialchars($_SESSION['admin_username']??'Admin')?>.</p>
<div class="grid"><div class="card"><div class="stat"><?=$n?></div>Attractions</div><div class="card"><div class="stat"><?=$c?></div>Categories</div><div class="card"><div class="stat"><?=$r?></div>Restaurants</div><div class="card"><div class="stat"><?=$e?></div>Emergency Contacts</div></div>
<div class="section"><div class="grid"><div class="card"><h2>Attractions</h2><a class="btn" href="attractions.php">Manage Attractions</a></div><div class="card"><h2>Categories</h2><a class="btn" href="categories.php">Manage Categories</a></div><div class="card"><h2>Restaurants</h2><a class="btn" href="restaurants.php">Manage Restaurants</a></div><div class="card"><h2>Emergency</h2><a class="btn" href="emergency.php">Manage Contacts</a></div></div></div>
</section><?php include "footer.php"; ?>