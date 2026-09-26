<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
$pageTitle = $pageTitle ?? 'VisitHunnasgiriya';
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?=htmlspecialchars($pageTitle)?> | VisitHunnasgiriya</title>
<link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css?v=3"><script src="<?=BASE_URL?>assets/js/main.js" defer></script>
</head><body>
<header class="site-header"><div class="container nav">
<a class="logo" href="<?=BASE_URL?>home.php"><img src="<?=BASE_URL?>assets/images/logo.png" alt="VisitHunnasgiriya logo"><span>Visit<strong>Hunnasgiriya</strong></span></a>
<nav class="main-nav" data-nav>
<a href="<?=BASE_URL?>home.php">Home</a><a href="<?=BASE_URL?>attractions.php">Attractions</a><a href="<?=BASE_URL?>planner.php">Day Planner</a><a href="<?=BASE_URL?>emergency.php">Emergency</a>
<?php if(!empty($_SESSION['user_id'])): ?><span class="welcome">Hi, <?=htmlspecialchars($_SESSION['user_name'])?></span><a class="nav-btn" href="<?=BASE_URL?>logout.php">Logout</a>
<?php else: ?><a class="nav-btn" href="<?=BASE_URL?>login.php">User Login</a><?php endif; ?>
</nav></div></header><main>
