<?php
include "db.php";

// 1. Get the Recipe ID from the URL
$recipe_id = $_GET['id'] ?? null;

// Ensure ID is present and is an integer
if (!is_numeric($recipe_id) || $recipe_id <= 0) {

    die("Error: Invalid or missing Recipe ID.");
}

// 2. Prepare and Execute the Database Query
$safe_id = mysqli_real_escape_string($conn, $recipe_id);
$sql = "SELECT * FROM recipes WHERE id = '$safe_id'";

$result = mysqli_query($conn, $sql);

// 3. Check if the recipe was found
if ($result && mysqli_num_rows($result) > 0) {
    $recipe = mysqli_fetch_assoc($result);
} else {

    die("Error: Recipe not found.");
}

mysqli_close($conn);

// Helper function to safely output text
function e($text) {
    return htmlspecialchars($text ?? '[N/A]');
}
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
  <p><strong>Dessert Name:</strong> <span id="name"><?php echo htmlspecialchars($row["dessert_name"]); ?></span></p>
  <p><strong>Type:</strong> <span id="type"><?php echo htmlspecialchars($row["type"]); ?></span></p>
  <p><strong>Tags:</strong> <span id="tags"><?php echo htmlspecialchars($row["tags"]); ?></span></p>

  <h3>Ingredients:</h3>
  <p id="ingredients"><?php echo nl2br(htmlspecialchars($row["ingredients"])); ?></p>

  <h3>Instructions:</h3>
  <p id="instructions"><?php echo nl2br(htmlspecialchars($row["instructions"])); ?></p>

  <a href="edit-recipe.php?id=<?php echo $id; ?>" class="back-btn">Edit Recipe</a>
</div>

<footer>
  <p>© 2025 Code & Crumble.</p>
</footer>

</body>
</html>
