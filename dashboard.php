<?php
session_start();

// Check if the user is logged in and has permission level 3 or higher
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 3) {
    echo "You do not have permission to access this page.";
    exit();
}

require_once "Database.php";
require_once "User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$user->UserID = $_SESSION['UserID'];

// Fetch all users for the dropdown menu
$query = "SELECT UserID, Username FROM Users";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the current user's profile picture and bio
$query = "SELECT ProfilePicture, Bio FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $user->UserID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePicture = $userData['ProfilePicture'] ?? 'default.jpg'; // Default image if none is set
$bio = $userData['Bio'] ?? '';

// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profilePicture'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["profilePicture"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($_FILES["profilePicture"]["tmp_name"]);
    if ($check !== false) {
        // Move the file to the uploads directory
        if (move_uploaded_file($_FILES["profilePicture"]["tmp_name"], $targetFile)) {
            // Update the profile picture in the database
            $user->updateProfilePicture($targetFile);
            $profilePicture = $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "File is not an image.";
    }
}

// Handle bio update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bio'])) {
    $bio = $_POST['bio'];
    $user->updateBio($bio);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=1.0.3">
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <h1>Dashboard</h1>

        <!-- Profile Section -->
        <div class="profile-section">
            <!-- Profile Picture -->
            <div class="profile-picture">
                <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" width="100">
            </div>

            <!-- Bio Box and Upload Section -->
            <div class="bio-upload-section">
                <!-- Bio Box -->
                <div class="bio-box">
                    <form action="dashboard.php" method="POST">
                        <div class="form-group">
                            <label for="bio">Bio:</label>
                            <textarea id="bio" name="bio" rows="4" cols="50"><?php echo htmlspecialchars($bio); ?></textarea>
                        </div>
                        <button type="submit" class="submit-button">Update Bio</button>
                    </form>
                </div>

                <!-- Upload Profile Picture Form -->
                <div class="upload-box">
                    <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="profilePicture">Upload Profile Picture:</label>
                            <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
                        </div>
                        <button type="submit" class="submit-button">Upload Picture</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Update Permission Form -->
        <div class="permission-section">
            <form action="dashboard.php" method="POST">
                <div class="form-group">
                    <label for="userID">Select User:</label>
                    <select id="userID" name="userID" required>
                        <option value="">-- Select a User --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo $user['UserID']; ?>"><?php echo htmlspecialchars($user['Username']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="permissionLevelID">Select Permission Level:</label>
                    <select id="permissionLevelID" name="permissionLevelID" required>
                        <option value="">-- Select a Permission Level --</option>
                        <option value="1">1 - Normal User</option>
                        <option value="2">2 - Moderator</option>
                        <option value="3">3 - Admin</option>
                        <option value="4">4 - Super Admin</option>
                    </select>
                </div>
                <button type="submit" class="submit-button">Update Permission</button>
            </form>
        </div>
    </div>
</body>
</html>
