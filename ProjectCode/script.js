
// Update image status when user selects a file
function updateStatus() {
  const fileInput = document.getElementById('recipe_file');
  const status = document.getElementById('status');

  if (fileInput.files.length === 0) {
    status.value = "No image uploaded";
    status.classList.remove('uploaded-status');
  } else {
    status.value = "Image: " + fileInput.files[0].name;
    status.classList.add('uploaded-status');
  }
}

// Validate form and show alert before redirect
function Submet(event) {
  event.preventDefault(); // Prevent default form submission

  const dessertName = document.getElementById('dessert_name').value.trim();
  const typeRadios = document.getElementsByName('type');
  const ingredients = document.getElementById('ingredients').value.trim();
  const instructions = document.getElementById('instructions').value.trim();
  const fileInput = document.getElementById('recipe_file');

  // Check if a type is selected
  let typeSelected = false;
  for (let i = 0; i < typeRadios.length; i++) {
    if (typeRadios[i].checked) {
      typeSelected = true;
      break;
    }
  }

  // If any required field is missing, show a simple browser alert
  if (!dessertName || !typeSelected || !ingredients || !instructions || fileInput.files.length === 0) {
    alert("Please fill in all required fields and upload an image!");
    return;
  }

  // Show the custom alert box
  const box = document.getElementById("miniAlert");
  box.style.display = "flex";
  document.body.style.overflow = "hidden";

  // Redirect to view-recipe.html after OK
  document.getElementById("okAlert").onclick = () => {
    
    document.body.style.overflow = "auto";
    location.href = "view-recipe.html";
  };
}

// Confirm deletion (for Edit page)
function deleteRecipe() {
  const box = document.getElementById("miniConfirm");
  box.style.display = "flex";
  document.body.style.overflow = "hidden";

  document.getElementById("yesBtn").onclick = () => {
    document.body.style.overflow = "auto";
    location.href = "view-recipe.html";
  };

  document.getElementById("noBtn").onclick = () => {
      document.body.style.overflow = "auto";
    box.style.display = "none";
  };
}
