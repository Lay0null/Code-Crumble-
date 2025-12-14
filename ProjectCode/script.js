
// Update image status when user selects a file
function updateStatus() {
    const fileInput = document.getElementById('recipe_file');
    const status = document.getElementById('status');

    if (!fileInput || !status) return;

    if (fileInput.files.length === 0) {
        status.value = "No image uploaded";
        status.classList.remove('uploaded-status');
    } else {
        status.value = "Image: " + fileInput.files[0].name;
        status.classList.add('uploaded-status');
    }
}


// Validate form before submission
function validateForm() {
    const dessertName = document.getElementById('dessert_name').value.trim();
    const description = document.getElementById('description').value.trim();
    const typeRadios = document.getElementsByName('type');
    const ingredients = document.getElementById('ingredients').value.trim();
    const instructions = document.getElementById('instructions').value.trim();
    const fileInput = document.getElementById('recipe_file');

    let typeSelected = false;
    for (let i = 0; i < typeRadios.length; i++) {
        if (typeRadios[i].checked) {
            typeSelected = true;
            break;
        }
    }

    if (!dessertName || !description || !typeSelected || !ingredients || !instructions) {
        alert("Please fill in all required fields!");
        return false;
    }

    // For Add page: require image
    if (fileInput && fileInput.files.length === 0 && document.body.classList.contains('add-page')) {
        alert("Please upload an image!");
        return false;
    }

    return true;
}


// Delete recipe with confirmation (Edit page)

function deleteRecipe() {
    const box = document.getElementById("miniConfirm");
    if (!box) return;

    box.style.display = "flex";
    document.body.style.overflow = "hidden";

    const yesBtn = document.getElementById("yesBtn");
    const noBtn = document.getElementById("noBtn");

    if (yesBtn) {
        yesBtn.onclick = () => {
            const hiddenInput = document.getElementById("delete_recipe");
            if (hiddenInput) hiddenInput.value = 1;

            const form = document.querySelector("form");
            if (form) form.submit();
        };
    }

    if (noBtn) {
        noBtn.onclick = () => {
            box.style.display = "none";
            document.body.style.overflow = "auto";
        };
    }
}

