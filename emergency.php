<?php
require "config/db.php";

$pageTitle = "Emergency Contacts";

$rows = $pdo->query("
    SELECT * 
    FROM emergency_contacts 
    ORDER BY service_name
")->fetchAll();

include "includes/header.php";
?>

<section class="section container">

    <h1>Emergency Contacts</h1>

    <p class="section-intro">
        Find important emergency services, police stations, hospitals,
        government offices and support contacts around Hunnasgiriya.
    </p>

    <!-- Search Box -->
    <div class="search-box">
        <input 
            type="text" 
            id="contactSearch"
            placeholder="Search emergency contacts..."
            autocomplete="off"
        >
    </div>

    <!-- Contact Grid -->
    <div class="grid" id="contactGrid">

        <?php foreach($rows as $r): ?>

            <div class="card contact-card">

                <h3 class="contact-name">
                    <?= htmlspecialchars($r['service_name']) ?>
                </h3>

                <p class="contact-number">
                    <strong>
                        <?= htmlspecialchars($r['contact_number']) ?>
                    </strong>
                </p>

                <p class="contact-description">
                    <?= htmlspecialchars($r['description']) ?>
                </p>

            </div>

        <?php endforeach; ?>

    </div>

    <!-- No Results Message -->
    <div 
        class="notice" 
        id="noResults" 
        style="display:none;"
    >
        No emergency contacts found.
    </div>

    <?php if(!$rows): ?>

        <div class="notice">
            No emergency contacts have been added yet.
            Admin can add them from the dashboard.
        </div>

    <?php endif; ?>

</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("contactSearch");
    const cards = document.querySelectorAll(".contact-card");
    const noResults = document.getElementById("noResults");

    searchInput.addEventListener("input", function () {

        const searchText = this.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach(function (card) {

            const name = card
                .querySelector(".contact-name")
                .textContent
                .toLowerCase();

            const number = card
                .querySelector(".contact-number")
                .textContent
                .toLowerCase();

            const description = card
                .querySelector(".contact-description")
                .textContent
                .toLowerCase();

            if (
                name.includes(searchText) ||
                number.includes(searchText) ||
                description.includes(searchText)
            ) {

                card.style.display = "";

                visibleCount++;

            } else {

                card.style.display = "none";

            }

        });

        if (visibleCount === 0 && searchText !== "") {

            noResults.style.display = "block";

        } else {

            noResults.style.display = "none";

        }

    });

});
</script>

<?php include "includes/footer.php"; ?>