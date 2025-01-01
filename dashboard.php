<?php
session_start();

// Check if the user is logged in and has permission level 3 or higher
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 3) {
    echo "You do not have permission to access this page.";
    exit();
}

require_once "Database.php";

$database = new Database();
$db = $database->getConnection();

// Fetch all users for the dropdown menu
$query = "SELECT UserID, Username FROM Users";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    <!-- Dashboard Page -->
    <div class="dashboard-container">
        <h1>Dashboard</h1>

        <!-- Update Permission Form -->
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
</body>
</html>
