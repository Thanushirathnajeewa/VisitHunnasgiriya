<?php
/*
  Generic add / edit / delete page used by categories, tips, restaurants and emergency contacts.
  The page that includes this file defines $cfg (table, fields, list query).
*/
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';
require_admin();

$self = basename($_SERVER['SCRIPT_NAME']);
$id   = (int)($_GET['id'] ?? 0);

/* options for dropdown fields */
foreach ($cfg['fields'] as $name => &$f) {
    if (($f['type'] ?? '') === 'select' && isset($f['sql'])) $f['options'] = $pdo->query($f['sql'])->fetchAll(PDO::FETCH_KEY_PAIR);
}
unset($f);

$errors = [];
$row = array_fill_keys(array_keys($cfg['fields']), '');
if ($id) {
    $st = $pdo->prepare("SELECT * FROM {$cfg['table']} WHERE {$cfg['pk']} = ?"); $st->execute([$id]);
    $row = $st->fetch() ?: redirect($self);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    /* ----- delete ----- */
    if (($_POST['action'] ?? '') === 'delete') {
        $did = (int)($_POST['id'] ?? 0);
        if (!empty($cfg['delete_check'])) {
            $st = $pdo->prepare($cfg['delete_check']['sql']); $st->execute([$did]);
            if ($st->fetchColumn() > 0) { flash_set($cfg['delete_check']['msg'], 'err'); redirect($self); }
        }
        $pdo->prepare("DELETE FROM {$cfg['table']} WHERE {$cfg['pk']} = ?")->execute([$did]);
        flash_set("Deleted."); redirect($self);
    }

    /* ----- add / edit ----- */
    $vals = [];
    foreach ($cfg['fields'] as $k => $f) {
        $v = trim($_POST[$k] ?? ''); $row[$k] = $v;
        $type = $f['type'] ?? 'text';
        if ($v === '' && !empty($f['required'])) { $errors[] = $f['label'] . " is required."; continue; }
        if ($v !== '' && isset($f['max']) && str_len($v) > $f['max']) $errors[] = $f['label'] . " is too long (max {$f['max']}).";
        if ($v !== '' && $type === 'number' && (!is_numeric($v) || $v < 0)) $errors[] = $f['label'] . " must be a positive number.";
        if ($v !== '' && $type === 'select' && !isset($f['options'][$v])) $errors[] = "Choose a valid " . strtolower($f['label']) . ".";
        $vals[$k] = $v === '' ? null : $v;
    }
    if (!$errors) {
        $cols = array_keys($vals);
        if ($id) $pdo->prepare("UPDATE {$cfg['table']} SET " . implode(',', array_map(fn($c) => "$c=?", $cols)) . " WHERE {$cfg['pk']}=?")->execute([...array_values($vals), $id]);
        else     $pdo->prepare("INSERT INTO {$cfg['table']} (" . implode(',', $cols) . ") VALUES (" . implode(',', array_fill(0, count($cols), '?')) . ")")->execute(array_values($vals));
        flash_set($id ? "Changes saved." : "Added."); redirect($self);
    }
}

$list = $pdo->query($cfg['list_sql'])->fetchAll();
$pageTitle = $cfg['title'];
include __DIR__ . '/admin_header.php';
?>
  <section>
    <h2><?= e($cfg['title']) ?></h2>
    <p class="sub"><?= e($cfg['sub']) ?></p>
    <?= flash_show() ?>
    <?php foreach ($errors as $er): ?><div class="msg err"><?= e($er) ?></div><?php endforeach; ?>

    <div class="admin">
      <h3><?= $id ? 'Edit' : 'Add new' ?></h3>
      <form method="post" action="<?= $self ?><?= $id ? '?id=' . $id : '' ?>">
        <?= csrf_field() ?>
        <div class="form-grid">
          <?php foreach ($cfg['fields'] as $k => $f): $type = $f['type'] ?? 'text'; ?>
            <label><?= e($f['label']) ?>
              <?php if ($type === 'textarea'): ?>
                <textarea name="<?= $k ?>"><?= e($row[$k]) ?></textarea>
              <?php elseif ($type === 'select'): ?>
                <select name="<?= $k ?>">
                  <option value=""><?= !empty($f['required']) ? 'Choose...' : 'None' ?></option>
                  <?php foreach ($f['options'] as $val => $label): ?>
                    <option value="<?= e($val) ?>" <?= (string)$row[$k] === (string)$val ? 'selected' : '' ?>><?= e($label) ?></option>
                  <?php endforeach; ?>
                </select>
              <?php else: ?>
                <input type="<?= $type === 'number' ? 'number' : 'text' ?>" <?= $type === 'number' ? 'step="0.01" min="0"' : '' ?> name="<?= $k ?>" value="<?= e($row[$k]) ?>">
              <?php endif; ?>
            </label>
          <?php endforeach; ?>
        </div>
        <button class="btn" type="submit">Save</button>
        <?php if ($id): ?><a class="btn light" href="<?= $self ?>">Cancel</a><?php endif; ?>
      </form>

      <h3 style="margin-top:28px">Saved items</h3>
      <table>
        <tr><?php foreach ($cfg['columns'] as $label): ?><th><?= e($label) ?></th><?php endforeach; ?><th>Actions</th></tr>
        <?php foreach ($list as $r): ?>
          <tr>
            <?php foreach ($cfg['columns'] as $k => $label): ?><td><?= e($r[$k]) ?></td><?php endforeach; ?>
            <td>
              <a class="btn light" href="<?= $self ?>?id=<?= $r['id'] ?>">Edit</a>
              <form method="post" onsubmit="return confirm('Delete this item?')">
                <?= csrf_field() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>">
                <button class="btn light" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </section>
</body>
</html>
