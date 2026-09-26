<?php
require "config/db.php";

$pageTitle = "Attractions";

$q = trim($_GET['q'] ?? "");
$category = (int)($_GET['category'] ?? 0);

/* Get categories */
$cats = $pdo->query("
    SELECT *
    FROM categories
    ORDER BY category_name
")->fetchAll();

/* Get attractions + first image */
$sql = "
    SELECT 
        a.*,
        c.category_name,
        (
            SELECT ai.image_path
            FROM attraction_images ai
            WHERE ai.attraction_id = a.attraction_id
            ORDER BY ai.image_id ASC
            LIMIT 1
        ) AS image_path
    FROM attractions a
    JOIN categories c 
        ON a.category_id = c.category_id
    WHERE 1
";

$params = [];

/* Search */
if ($q !== "") {
    $sql .= "
        AND (
            a.name LIKE ?
            OR a.description LIKE ?
            OR a.location LIKE ?
        )
    ";

    $params = [
        "%$q%",
        "%$q%",
        "%$q%"
    ];
}

/* Category filter */
if ($category) {
    $sql .= " AND a.category_id = ?";
    $params[] = $category;
}

/* Order by distance */
$sql .= " ORDER BY a.distance_km";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$rows = $stmt->fetchAll();

include "includes/header.php";
?>

<section class="section container">

    <h1>Tourist Attractions</h1>

    <!-- Search and Category Filter -->
    <form class="searchbar" method="get">

        <input
            class="form-control"
            name="q"
            placeholder="Search attractions..."
            value="<?= htmlspecialchars($q) ?>"
        >

        <select name="category">

            <option value="0">
                All categories
            </option>

            <?php foreach ($cats as $c): ?>

                <option
                    value="<?= $c['category_id'] ?>"
                    <?= $category == $c['category_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($c['category_name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <button class="btn" type="submit">
            Search
        </button>

    </form>


    <!-- Attraction Cards -->
    <div class="grid">

        <?php foreach ($rows as $a): ?>

            <article class="card">

                <!-- Attraction Image -->
                <?php if (!empty($a['image_path'])): ?>

                    <img
                        src="<?= htmlspecialchars($a['image_path']) ?>"
                        alt="<?= htmlspecialchars($a['name']) ?>"
                        class="attraction-image"
                    >

                <?php else: ?>

                    <div class="no-image">
                        No image available
                    </div>

                <?php endif; ?>


                <!-- Category -->
                <span class="tag">
                    <?= htmlspecialchars($a['category_name']) ?>
                </span>


                <!-- Name -->
                <h3>
                    <?= htmlspecialchars($a['name']) ?>
                </h3>


                <!-- Description -->
                <p>
                    <?= htmlspecialchars($a['description']) ?>
                </p>


                <!-- Information -->
                <p class="meta">
                    <?= htmlspecialchars($a['distance_km']) ?> km
                    ·
                    <?= htmlspecialchars($a['travel_time']) ?>
                    ·
                    <?= htmlspecialchars($a['estimated_visit_duration']) ?>
                </p>


                <!-- Details Button -->
                <a
                    class="btn"
                    href="attraction.php?id=<?= $a['attraction_id'] ?>"
                >
                    Details
                </a>

            </article>

        <?php endforeach; ?>

    </div>


    <!-- No Results -->
    <?php if (!$rows): ?>

        <div class="notice">
            No attractions found.
        </div>

    <?php endif; ?>

</section>

<?php include "includes/footer.php"; ?>