<?php
// One login page for admins and visitors.
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
if (is_admin()) redirect('admin/dashboard.php');
if (is_user())  redirect('index.php');

$next  = safe_next($_GET['next'] ?? $_POST['next'] ?? '');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    $st = $pdo->prepare("SELECT admin_id, password FROM admins WHERE email = ?");
    $st->execute([$email]);
    $admin = $st->fetch();
    if ($admin && password_verify($pass, $admin['password'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['admin_id'];
        unset($_SESSION['user_id'], $_SESSION['user_name']);
        redirect('admin/dashboard.php');
    }

    $st = $pdo->prepare("SELECT user_id, full_name, password FROM users WHERE email = ?");
    $st->execute([$email]);
    $user = $st->fetch();
    if ($user && password_verify($pass, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id']   = (int)$user['user_id'];
        $_SESSION['user_name'] = $user['full_name'];
        unset($_SESSION['admin_id']);
        redirect($next ?: 'index.php');
    }
    usleep(500000);                                   // slows down password guessing
    $error = 'Wrong email or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - VisitHunnasgiriya</title>
  <link rel="icon" href="logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-page">
    <form class="login-box" method="post">
      <img src="logo.png" alt="Logo">
      <h2>Login</h2>
      <p class="sub">Visitors and admins sign in here</p>
      <?php if ($error): ?><div class="msg err"><?= e($error) ?></div><?php endif; ?>
      <?= flash_show() ?>
      <?= csrf_field() ?>
      <input type="hidden" name="next" value="<?= e($next) ?>">
      <input type="email" name="email" placeholder="Email" required autofocus>
      <input type="password" name="password" placeholder="Password" required>
      <button class="btn" type="submit">Login</button>
      <p class="alt">New here? <a href="register.php<?= $next ? '?next=' . urlencode($next) : '' ?>">Create an account</a></p>
      <a class="back" href="index.php">&larr; Back to site</a>
    </form>
  </div>
  <script src="password-toggle.js"></script>
</body>
</html>
