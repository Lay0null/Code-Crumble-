<?php
require_once "db_connect.php";

// get 1 random recipe
$sql = "SELECT * FROM recipes ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $sql);

$recipe = null;
if ($result && mysqli_num_rows($result) > 0) {
  $recipe = mysqli_fetch_assoc($result);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Recipe of the Day</title>
  <link rel="stylesheet" href="style.css">
</head>

<body class="view-page">

<h1>Recipe of the Day</h1>

<div class="view-box">
  <?php if (!$recipe): ?>
    <p>No recipes found.</p>
  <?php else: ?>
    <p><strong>Dessert Name:</strong> <?= htmlspecialchars($recipe["dessert_name"]) ?></p>
    <p><strong>Type:</strong> <?= htmlspecialchars($recipe["type"]) ?></p>
    <p><strong>Tags:</strong> <?= htmlspecialchars($recipe["tags"]) ?></p>

    <?php if (!empty($recipe["image"])): ?>
      <img
        src="uploads/<?= htmlspecialchars($recipe["image"]) ?>"
        alt="Recipe Image"
        style="max-width:250px; border-radius:12px; margin:10px 0;"
      >
    <?php endif; ?>

    <h3>Ingredients:</h3>
    <p><?= nl2br(htmlspecialchars($recipe["ingredients"])) ?></p>

    <h3>Instructions:</h3>
    <p><?= nl2br(htmlspecialchars($recipe["instructions"])) ?></p>
  <?php endif; ?>
</div>

<!-- reload page to get another random recipe -->
<p style="text-align:center; margin-top:20px;">
  <a class="back-btn" href="recipe_of_day.php">Pick Another Random Recipe</a>
</p>

</body>
</html>
