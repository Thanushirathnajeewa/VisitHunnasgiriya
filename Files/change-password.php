<?php
// Change password - works for a logged-in admin or visitor.
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
if (!is_admin() && !is_user()) redirect('login.php?next=change-password.php');

[$table, $idCol, $id] = is_admin() ? ['admins', 'admin_id', $_SESSION['admin_id']] : ['users', 'user_id', $_SESSION['user_id']];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $st = $pdo->prepare("SELECT password FROM $table WHERE $idCol = ?"); $st->execute([$id]);
    if (!password_verify($_POST['current'] ?? '', (string)$st->fetchColumn())) $errors[] = "Your current password is wrong.";
    if (strlen($_POST['new'] ?? '') < 8) $errors[] = "New password must be at least 8 characters.";
    if (($_POST['new'] ?? '') !== ($_POST['confirm'] ?? '')) $errors[] = "The new passwords do not match.";
    if (!$errors) {
        $pdo->prepare("UPDATE $table SET password = ? WHERE $idCol = ?")->execute([password_hash($_POST['new'], PASSWORD_DEFAULT), $id]);
        session_regenerate_id(true);
        flash_set("Password changed.");
        redirect(is_admin() ? 'admin/dashboard.php' : 'index.php');
    }
}
$pageTitle = "Change password";
include __DIR__ . '/includes/header.php';
?>
  <section style="max-width:520px">
    <a class="back" href="<?= is_admin() ? 'admin/dashboard.php' : 'index.php' ?>">&larr; Back</a>
    <h2>Change password</h2>
    <p class="sub">Use at least 8 characters</p>
    <?php foreach ($errors as $er): ?><div class="msg err"><?= e($er) ?></div><?php endforeach; ?>
    <form class="box white" method="post">
      <?= csrf_field() ?>
      <p><input type="password" name="current" placeholder="Current password" required style="width:100%"></p>
      <p><input type="password" name="new" placeholder="New password" required style="width:100%"></p>
      <p><input type="password" name="confirm" placeholder="Confirm new password" required style="width:100%"></p>
      <button class="btn" type="submit">Change password</button>
    </form>
    <script src="password-toggle.js"></script>
  </section>
<?php include __DIR__ . '/includes/footer.php'; ?>
