<?php
session_start();

// Check if the user is logged in and has permission to update permissions
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 3) {
    echo "You do not have permission to perform this action.";
    exit();
}

require_once "Database.php";

$database = new Database();
$db = $database->getConnection();

// Fetch all users for the dropdown, excluding the current user
$query = "SELECT UserID, Username FROM Users WHERE UserID != :currentUserID";
$stmt = $db->prepare($query);
$stmt->bindParam(":currentUserID", $_SESSION['UserID'], PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Controls</title>
    <link rel="stylesheet" href="styles.css?v=1.0.1">
</head>
<body>
    <div class="popup-content">
        <h2>User Controls</h2>

        <!-- Update Permissions Form -->
        <form action="update_permission.php" method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <select id="username" name="username" required>
                    <option value="">Select a user</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['Username']); ?>">
                            <?php echo htmlspecialchars($user['Username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="permissionLevelID">Permission Level:</label>
                <select id="permissionLevelID" name="permissionLevelID" required>
                    <option value="">Select a permission level</option>
                    <option value="1">1 - Normal User</option>
                    <option value="2">2 - Moderator</option>
                    <option value="3">3 - Admin</option>
                    <option value="4">4 - Super Admin</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="update-button">Update Permissions</button>
            </div>
        </form>

        <!-- Delete User Form -->
        <form action="delete_user.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
            <div class="form-group">
                <label for="deleteUsername">Select User to Delete:</label>
                <select id="deleteUsername" name="deleteUsername" required>
                    <option value="">Select a user</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo htmlspecialchars($user['UserID']); ?>">
                            <?php echo htmlspecialchars($user['Username']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="delete-button">Delete User</button>
            </div>
        </form>
    </div>

    <!-- Notification Popup -->
    <div id="notificationPopup" class="popup-box" style="display: none;">
      <div class="popup-content">
        <p id="notificationMessage"></p>
      </div>
    </div>
</body>
</html>
