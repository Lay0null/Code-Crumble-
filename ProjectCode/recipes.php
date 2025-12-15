<?php
include "db.php";

// 1. DATABASE QUERY 
$sql = "SELECT * FROM recipes ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>


<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>All Recipes | Code & Crumble</title>

</head>
 <body>   

 <header class = "main-header">
        <nav class="navBar">
            <a href="index.php" class= "navLogo">
                <h2 class = "logoText">Code & Crumble</h2>
            </a>
               <ul class ="nav-menu">
                <li class="navItem">
                    <a href="index.php" class ="navLink">Home</a>
                </li> 
                <li class="navItem">
                    <a href="recipes.php" class ="navLink">Recipes</a>
                </li> 
                    
                <li class="navItem">
                    <a href="login.php" class ="navLink">Login</a>
                </li> 

                <li class="navItem">
                    <a href="about.php" class ="navLink">About</a>
                </li>  
                
                 <li class="navItem">
                    <a href="contact.php" class ="navLink">Contact</a>
                </li>     
                
            </ul>
        </nav>
    </header>

<main class="page-content-recipes">
    
    <h1>All Code & Crumble Recipes</h1>

    <a href="add_recipe.php" class="btn-primary" style="margin-bottom: 20px;">
        + Submit Your Own Recipe
    </a>


    <section class="filter-controls">
    
    <form action="recipes.php" method="GET" class="search-form">
        <label for="search">Search Recipes:</label>
        <input type="text" id="search" name="query" placeholder="e.g., Cake or vegan FOR EXAMPLE ">
        <button type="submit" class="btn-search">Search</button>
    </form>

    <form action="recipes.php" method="GET" class="category-filter">
        <label for="category">Filter by Category:</label>
        <select id="category" name="cat">
            <option value="">All Categories</option> 
            <option value="cake">Cake</option>
            <option value="cookie">Cookie</option>
            <option value="pie">Pie</option>
            <option value="other">Other</option>
            </select>
        <button type="submit" class="btn-filter">Filter</button>
    </form>

</section>

<div class="recipe-grid">
<?php 
// CRITICAL: Start the loop here. It checks if there are any results and assigns the data to $row for each cycle.
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>

    <div class="recipe-card">
        
        <img src="<?= htmlspecialchars($row['image_url'] ?? 'images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($row['dessert_name']) ?>">

        <div class="card-content">
            
            <h4><?= htmlspecialchars($row['dessert_name']) ?></h4> 
            
            <p>
            <?php 
                // Display the description, or a snippet of instructions if description is empty
                if (!empty($row['description'])) {
                    echo htmlspecialchars($row['description']);
                } else {
                    echo htmlspecialchars(substr($row['instructions'], 0, 100)) . '...';
                }
            ?>
            </p>

            <a href="view_recipe.php?id=<?= $row['id'] ?>">View Recipe</a>
            
        </div>
    </div>

<?php 
    } // End of the while loop
} else {
    // Message if no recipes are found
    echo "<p style='text-align:center;'>No recipes found! Start by submitting one.</p>";
}
?>
</div>
</main>    
</body>
</html>
