<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL & ~E_NOTICE); //Disable notices
ini_set('display_errors', 1);

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

require_once "Database.php";
require_once "User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->UserID = $_SESSION['UserID'];

// Fetch the current user's profile picture and username
$query = "SELECT ProfilePicture, Username FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $user->UserID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePicture = $userData['ProfilePicture'] ?? 'default.png'; // Default to PNG
$username = $userData['Username'] ?? 'User';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePicture'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Debugging: Print file details
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";

    // Check if the file is an image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check === false) {
        echo "Error: File is not an image.";
        exit();
    }

    // Check file size (max 2MB)
    if ($_FILES["profilePicture"]["size"] > 2000000) {
        echo "Error: File is too large. Max size is 2MB.";
        exit();
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Error: Only JPG, JPEG, and PNG files are allowed.";
        exit();
    }

    // Ensure the uploads directory exists and is writable
    if (!is_dir($targetDir)) {
        echo "Error: The uploads directory does not exist.";
        exit();
    }
    if (!is_writable($targetDir)) {
        echo "Error: The uploads directory is not writable.";
        exit();
    }

    // Debugging: Print target file path
    echo "Target File Path: " . $targetFile . "<br>";

    // Upload the file
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
        // Debugging: Print success message
        echo "File uploaded successfully.<br>";

        // Update the user's profile picture in the database
        if ($user->updateProfilePicture(basename($_FILES["profilePicture"]["name"]))) {
            echo "Profile picture updated in the database.<br>";
            header("Location: profile.php"); // Refresh the page
            exit();
        } else {
            echo "Error: Failed to update profile picture in the database.";
            exit();
        }
    } else {
        // Debugging: Print detailed error message
        echo "Error: Failed to move uploaded file.<br>";
        echo "Upload Error Code: " . $_FILES["profilePicture"]["error"] . "<br>";
        echo "Temp File Path: " . $_FILES["profilePicture"]["tmp_name"] . "<br>";
        echo "Target File Path: " . $targetFile . "<br>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Profile Content -->
    <div class="dashboard-container">
        <!-- Include Profile Left Section (Sidebar) -->
        <?php include 'profile_left.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Profile</h1>
        </div>

        <!-- Include Profile Right Section -->
        <?php include 'profile_right.php'; ?>
    </div>

    <!-- Upload Box -->
    <div id="uploadBox" class="upload-box">
        <form action="profile.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="profilePicture" accept="image/*" required>
            <button type="submit">Upload</button>
            <button type="button" onclick="closeUploadBox()">Cancel</button>
        </form>
    </div>

    <!-- Include the external JavaScript file -->
    <script src="profile.js"></script>
</body>
</html>
