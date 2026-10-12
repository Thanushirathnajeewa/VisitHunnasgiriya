<?php
// Visitor registration (saved in the `users` table).
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
if (is_admin() || is_user()) redirect('index.php');

$next = safe_next($_GET['next'] ?? $_POST['next'] ?? '');
$name = $email = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $name  = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (str_len($name) < 2 || str_len($name) > 100) $errors[] = "Enter your name (2 to 100 characters).";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || str_len($email) > 150) $errors[] = "Enter a valid email address.";
    if (strlen($pass) < 8) $errors[] = "Password must be at least 8 characters.";
    if ($pass !== ($_POST['confirm'] ?? '')) $errors[] = "The two passwords do not match.";
    if (!$errors) {
        $st = $pdo->prepare("SELECT (SELECT COUNT(*) FROM users WHERE email = ?) + (SELECT COUNT(*) FROM admins WHERE email = ?)");
        $st->execute([$email, $email]);
        if ($st->fetchColumn() > 0) $errors[] = "That email is already registered. Try logging in.";
    }
    if (!$errors) {
        $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?,?,?)")
            ->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user_id']   = (int)$pdo->lastInsertId();
        $_SESSION['user_name'] = $name;
        flash_set("Welcome, $name! Your account is ready.");
        redirect($next ?: 'index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - VisitHunnasgiriya</title>
  <link rel="icon" href="logo.png">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="login-page">
    <form class="login-box" method="post">
      <img src="logo.png" alt="Logo">
      <h2>Create account</h2>
      <p class="sub">Save your day plans and open them anytime</p>
      <?php foreach ($errors as $er): ?><div class="msg err"><?= e($er) ?></div><?php endforeach; ?>
      <?= csrf_field() ?>
      <input type="hidden" name="next" value="<?= e($next) ?>">
      <input type="text" name="full_name" placeholder="Full name" value="<?= e($name) ?>" required autofocus>
      <input type="email" name="email" placeholder="Email" value="<?= e($email) ?>" required>
      <input type="password" name="password" placeholder="Password (8+ characters)" required>
      <input type="password" name="confirm" placeholder="Confirm password" required>
      <button class="btn" type="submit">Register</button>
      <p class="alt">Already have an account? <a href="login.php<?= $next ? '?next=' . urlencode($next) : '' ?>">Login</a></p>
      <a class="back" href="index.php">&larr; Back to site</a>
    </form>
  </div>
  <script src="password-toggle.js"></script>
</body>
</html>
