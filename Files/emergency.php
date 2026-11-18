<?php
require __DIR__ . '/config/db.php';
require __DIR__ . '/includes/functions.php';
$pageTitle = "Emergency Contacts";
$rows = $pdo->query("SELECT * FROM emergency_contacts ORDER BY service_name")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
  <section>
    <h2>Emergency contacts</h2>
    <p class="sub">Police, hospitals, tourist help and government offices around Hunnasgiriya</p>

    <div class="searchbar" style="margin:0 0 22px;box-shadow:var(--shadow)">
      <input type="text" id="contactSearch" placeholder="Search emergency contacts..." autocomplete="off">
    </div>

    <div class="cards" id="contactGrid">
      <?php foreach ($rows as $r):
        $first = preg_split('~\s*/\s*~', $r['contact_number'])[0];
        $tel   = preg_replace('~[^0-9+]~', '', $first); ?>
        <div class="emerg contact-card">
          <span class="contact-name"><?= e($r['service_name']) ?></span>
          <a class="<?= strlen($r['contact_number']) > 12 ? 'small' : '' ?> contact-number" href="tel:<?= e($tel) ?>"><?= e($r['contact_number']) ?></a>
          <small class="contact-description"><?= e($r['description']) ?></small>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="box" id="noResults" style="display:none"><p>No emergency contacts found.</p></div>
    <?php if (!$rows): ?><div class="box"><p>No emergency contacts have been added yet.</p></div><?php endif; ?>
  </section>

<script>
document.getElementById("contactSearch").addEventListener("input", function () {
  var text = this.value.toLowerCase().trim(), shown = 0;
  document.querySelectorAll(".contact-card").forEach(function (card) {
    var match = card.textContent.toLowerCase().includes(text);
    card.style.display = match ? "" : "none";
    if (match) shown++;
  });
  document.getElementById("noResults").style.display = (shown === 0 && text !== "") ? "block" : "none";
});
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>
