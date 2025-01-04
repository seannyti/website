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

// Check if a user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user->UserID = $_GET['user_id'];
} else {
    $user->UserID = $_SESSION['UserID']; // Default to the logged-in user's profile
}

// Fetch the user's data
$query = "SELECT UserID, Username, ProfilePicture, Bio FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $user->UserID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    echo "User not found.";
    exit();
}

// Handle profile picture upload (only for the logged-in user)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePicture']) && $user->UserID == $_SESSION['UserID']) {
    // Your existing file upload logic here...
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="styles.css?v=1.0.1">
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
            <div class="profile-info">
                <div class="profile-picture">
                    <img src="uploads/<?php echo htmlspecialchars($userData['ProfilePicture']); ?>" alt="Profile Picture" width="100" height="100">
                </div>
                <div class="profile-details">
                    <h2><?php echo htmlspecialchars($userData['Username']); ?></h2>
                    <p><?php echo htmlspecialchars($userData['Bio']); ?></p>
                </div>
            </div>
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
