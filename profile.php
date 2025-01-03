<?php
session_start();

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

    // Check if the file is an image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        exit();
    }

    // Check file size (max 2MB)
    if ($_FILES["profilePicture"]["size"] > 2000000) {
        echo "File is too large. Max size is 2MB.";
        exit();
    }

    // Allow only certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Only JPG, JPEG, and PNG files are allowed.";
        exit();
    }

    // Upload the file
    if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
        // Update the user's profile picture in the database
        $user->updateProfilePicture(basename($_FILES["profilePicture"]["name"]));
        header("Location: profile.php"); // Refresh the page
        exit();
    } else {
        echo "Error uploading file.";
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
        <!-- Sidebar Section -->
        <div class="sidebar">
            <div class="user-info">
                <div class="profile-picture" onclick="openUploadBox()">
                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" width="50" height="50">
                    <div class="upload-text">Upload</div>
                </div>
                <div class="user-name">
                    <?php echo htmlspecialchars($username); ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Profile</h1>
        </div>
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
