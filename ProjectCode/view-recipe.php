<?php
require_once "db_connect.php";

/* Get recipe id from URL */
$recipe_id = $_GET['id'] ?? null;

if (!is_numeric($recipe_id) || (int)$recipe_id <= 0) {
  die("Error: Invalid or missing Recipe ID.");
}

$recipe_id = (int)$recipe_id;

/* Fetch recipe */
$sql = "SELECT * FROM recipes WHERE id = $recipe_id LIMIT 1";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
  mysqli_close($conn);
  die("Error: Recipe not found.");
}

$recipe = mysqli_fetch_assoc($result);
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Dessert Recipe</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="view-page">

<header class="header"></header>
<h1>View Your Recipe:</h1>

<div class="view-box">
  <p><strong>Dessert Name:</strong> <span id="name"><?php echo htmlspecialchars($recipe["dessert_name"] ?? ""); ?></span></p>
  <p><strong>Type:</strong> <span id="type"><?php echo htmlspecialchars($recipe["type"] ?? ""); ?></span></p>
  <p><strong>Tags:</strong> <span id="tags"><?php echo htmlspecialchars($recipe["tags"] ?? ""); ?></span></p>
  <?php if (!empty($recipe["image"])): ?>
  <img src="uploads/<?php echo htmlspecialchars($recipe["image"]); ?>" alt="Dessert Image" style="max-width:250px; display:block; margin:15px 0; border-radius:10px;">
  <?php else: ?>
  <p><em>No image uploaded</em></p>
  <?php endif; ?>
  <h3>Ingredients:</h3>
  <p id="ingredients"><?php echo nl2br(htmlspecialchars($recipe["ingredients"] ?? "")); ?></p>

  <h3>Instructions:</h3>
  <p id="instructions"><?php echo nl2br(htmlspecialchars($recipe["instructions"] ?? "")); ?></p>

  <a href="edit-recipe.php?id=<?php echo $recipe_id; ?>" class="back-btn">Edit Recipe</a>
</div>

<footer>
  <p>© 2025 Code & Crumble.</p>
</footer>

</body>
</html>
