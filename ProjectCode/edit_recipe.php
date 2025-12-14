<?php
require_once "db_connect.php";  // Connect to the database

session_start();
$user_id = 1; 
$message = "";
$recipe = null;

//  Get recipe ID from GET
$recipe_id = $_GET['id'] ?? null;
if (!$recipe_id) {
    die("Recipe not found.");
}

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Delete recipe
    if (isset($_POST['delete_recipe']) && $_POST['delete_recipe'] == 1) {
        $stmt = $conn->prepare("DELETE FROM recipes WHERE id=? AND user_id=?");
        $stmt->bind_param("ii", $recipe_id, $user_id);

        if ($stmt->execute()) { 
            $message = "deleted"; 
        } 
        $stmt->close();

    } else { // Update recipe

        //  Get form values
        $dessert_name = htmlspecialchars($_POST['dessert_name']);
        $description  = htmlspecialchars($_POST['description']);
        $type         = htmlspecialchars($_POST['type']);
        $ingredients  = htmlspecialchars($_POST['ingredients']);
        $instructions = htmlspecialchars($_POST['instructions']);
        $tags         = isset($_POST['tag']) ? $_POST['tag'] : [];
        $tags_str     = implode(",", $tags);

        //  Simple validation
        if ($dessert_name === "" || $description === "" || $type === "" ||
            $ingredients === "" || $instructions === "") {
            $message = "error";
        } else {

            //  Process image
            $image_name = $_POST['existing_image'] ?? "";
            if (!empty($_FILES['recipe_file']['name'])) {
                $image_name = basename($_FILES['recipe_file']['name']);
                move_uploaded_file($_FILES['recipe_file']['tmp_name'], "uploads/" . $image_name);
            }

            //  Prepare update statement
            $stmt = $conn->prepare("UPDATE recipes SET dessert_name=?, description=?, type=?, tags=?, ingredients=?, instructions=?, image=? WHERE id=? AND user_id=?");
            $stmt->bind_param("ssssssiii", $dessert_name, $description, $type, $tags_str, $ingredients, $instructions, $image_name, $recipe_id, $user_id);

            //  Execute update
            if ($stmt->execute()) { 
                $message = "success"; 
            } else {
                $message = "error";
            }
            $stmt->close();
        }
    }
}

// Fetch recipe data for form display
$stmt = $conn->prepare("SELECT * FROM recipes WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $recipe_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$recipe = $result->fetch_assoc();

if (!$recipe) { 
    die("Recipe not found."); 
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Recipe</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body class="edit-page">

<header class="header"></header>
<h1>Edit Your Recipe:</h1>

<form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

  <div class="form-container">

    <div class="column-left">
      <div class="section-details">
        <label for="dessert_name">Dessert Name: *</label>
        <input type="text" id="dessert_name" name="dessert_name" value="<?php echo htmlspecialchars($recipe['dessert_name']); ?>" required>

        <label for="description">Description: *</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($recipe['description']); ?>" required>

        <div class="recipe-type">
            <label>Type: *</label>
            <input type="radio" id="type-cake" name="type" value="Cake" <?php if($recipe['type']=="Cake") echo "checked"; ?> required><label for="type-cake">Cake</label>
            <input type="radio" id="type-cookie" name="type" value="Cookie" <?php if($recipe['type']=="Cookie") echo "checked"; ?>><label for="type-cookie">Cookie</label>
            <input type="radio" id="type-pie" name="type" value="Pie" <?php if($recipe['type']=="Pie") echo "checked"; ?>><label for="type-pie">Pie</label><br><br>
        </div>
      </div>

      <div class="section-tags">
        <label>Tags: </label>
        <?php 
        $all_tags = ["Quick","gluten-free","Healthy"];
        foreach($all_tags as $tag):
            $checked = in_array($tag, explode(",",$recipe['tags'])) ? "checked" : "";
        ?>
        <input type="checkbox" name="tag[]" value="<?php echo $tag; ?>" <?php echo $checked; ?>><label><?php echo $tag; ?></label>
        <?php endforeach; ?>
      </div>

      <div class="section-instructions">
        <label for="ingredients">Ingredients: *</label>
        <textarea id="ingredients" name="ingredients" rows="4"><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

        <label for="instructions">Instructions: *</label>
        <textarea id="instructions" name="instructions" rows="4" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
      </div>

    </div> 

    <div class="column-right">
      <div class="section-photo">
        <label for="recipe_file" class="custom-file-upload">
          <img src="images/camera-icon.png" alt="Upload Dessert Image">
        </label>
        <input type="file" id="recipe_file" name="recipe_file" accept=".png,.jpg,.jpeg" onchange="updateStatus()">
        <label for="status">Image Status</label>
        <input type="text" id="status" value="<?php echo $recipe['image'] ? $recipe['image'] : "No image uploaded"; ?>" disabled>
        <input type="hidden" name="existing_image" value="<?php echo $recipe['image']; ?>">
      </div>
    </div>

  </div>

  <button type="submit" class="save-changes-button" >Update Recipe</button>
  <button type="button" class="delete-button" onclick="deleteRecipe()">Delete Recipe</button>
  <input type="hidden" name="delete_recipe" id="delete_recipe" value="0">

</form>

<footer>
    <p>&copy; 2025 Code & Crumble.</p>
</footer>

<div id="miniConfirm">
  <div>
    <h5>Are you sure you want to delete? (,,>﹏<,,)</h5>
    <button id="yesBtn">Yes</button>
    <button id="noBtn">No</button>
  </div>
</div>

<div id="miniAlert">
  <div id="miniAlertText">
    <h5>Changes Saved successfully!<br> <br>ദ്ദി(˵ •̀ ᴗ - ˵ ) </h5>
    <button id="okAlert">OK</button>
  </div>
</div>

<?php if ($message === "success"): ?>
<script>
   window.onload = () => {
    const box = document.getElementById("miniAlert");
    box.style.display = "flex";

    document.getElementById("okAlert").onclick = () => {
        document.body.style.overflow = "auto";
        window.location.href = "recipes.php"; 
    };
  }; 
</script>

<?php elseif($message === "deleted"): ?>
<script>
window.onload = () => {
    alert("Recipe deleted successfully!");
    window.location.href = "recipes.php";
};
</script>
<?php endif; ?>

<script src="script.js"></script>
</body>
</html>
