// Open Upload Box
function openUploadBox() {
    document.getElementById("uploadBox").style.display = "block";
}

// Close Upload Box
function closeUploadBox() {
    document.getElementById("uploadBox").style.display = "none";
}

// Open Admin Popup
function openAdminPopup() {
    document.getElementById("adminPopup").style.display = "block";
}

// Close Admin Popup
function closeAdminPopup() {
    document.getElementById("adminPopup").style.display = "none";
}

// Show Notification Popup
function showNotification(message, isError = false) {
    const notificationPopup = document.getElementById("notificationPopup");
    const notificationMessage = document.getElementById("notificationMessage");

    // Set the message and style
    notificationMessage.textContent = message;
    notificationPopup.classList.toggle("error", isError);

    // Show the popup
    notificationPopup.style.display = "block";

    // Hide the popup after 3 seconds
    setTimeout(() => {
        notificationPopup.style.display = "none";
    }, 3000);
}

// Handle form submission
function handleUpdatePermissionFormSubmit(e) {
    e.preventDefault(); // Prevent the default form submission

    // Get form data
    const formData = new FormData(e.target);

    // Send AJAX request
    fetch("update_permission.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        // Show notification
        showNotification(data.message, !data.success);
    })
    .catch(error => {
        console.error("Error:", error);
        showNotification("An error occurred. Please try again.", true);
    });
}

// Attach the event listener to the form
document.addEventListener("DOMContentLoaded", function () {
    const updatePermissionForm = document.getElementById("updatePermissionForm");
    if (updatePermissionForm) {
        updatePermissionForm.addEventListener("submit", handleUpdatePermissionFormSubmit);
    }
});
