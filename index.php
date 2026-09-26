<?php
session_start();
require_once 'config/db.php';

if (!empty($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

if (!empty($_SESSION['admin_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Welcome | VisitHunnasgiriya</title>
<link rel="stylesheet" href="<?=BASE_URL?>assets/css/style.css?v=5">
</head>
<body class="role-page">
<main class="role-wrapper">
    <section class="role-card">
        <a class="role-logo" href="<?=BASE_URL?>index.php">
            <img src="<?=BASE_URL?>assets/images/logo.png" alt="VisitHunnasgiriya Logo">
            <span>Visit<strong>Hunnasgiriya</strong></span>
        </a>
        <span class="role-label">WELCOME</span>
        <h1>How would you like to continue?</h1>
        <p class="role-intro">Choose your account type to continue to VisitHunnasgiriya.</p>

        <div class="role-options">
            <a class="role-option user-option" href="<?=BASE_URL?>login.php">
                <span class="role-icon">👤</span>
                <span class="role-option-text"><strong>User Login</strong><small>Explore attractions, plan your day and use the visitor features.</small></span>
                <span class="role-arrow">→</span>
            </a>

            <a class="role-option admin-option" href="<?=BASE_URL?>admin/login.php">
                <span class="role-icon">🔐</span>
                <span class="role-option-text"><strong>Admin Login</strong><small>Manage attractions, categories, restaurants and emergency information.</small></span>
                <span class="role-arrow">→</span>
            </a>
        </div>

        <a class="role-visitor" href="<?=BASE_URL?>home.php">Continue to website without logging in</a>
    </section>
</main>
</body>
</html>
