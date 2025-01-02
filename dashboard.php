<?php
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

// Fetch the current user's profile picture and username
$query = "SELECT ProfilePicture, Username FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $user->UserID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePicture = $userData['ProfilePicture'] ?? 'default.png'; // Default to PNG
$username = $userData['Username'] ?? 'User';

// Handle permission level update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['userID']) && isset($_POST['permissionLevelID'])) {
    $userID = $_POST['userID'];
    $permissionLevelID = $_POST['permissionLevelID'];

    // Validate inputs
    if (empty($userID) || !is_numeric($permissionLevelID)) {
        echo "Invalid input.";
        exit();
    }

    // Update the user's permission level
    $query = "UPDATE Users SET PermissionLevelID = :permissionLevelID WHERE UserID = :userID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":permissionLevelID", $permissionLevelID);
    $stmt->bindParam(":userID", $userID);

    if ($stmt->execute()) {
        echo "Permission level updated successfully.";
    } else {
        echo "Failed to update permission level.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Sidebar Section -->
        <div class="sidebar">
            <div class="user-info">
                <div class="profile-picture">
                    <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" width="50" height="50"> <!-- Use PNG file -->
                </div>
                <div class="user-name">
                    <?php echo htmlspecialchars($username); ?>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h1>Dashboard</h1>

            <!-- Update Permission Section -->
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
    </div>
</body>
</html>
