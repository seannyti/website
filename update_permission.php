<?php
session_start();

// Check if the user is logged in and has permission to update permissions
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 4) {
    echo "You do not have permission to perform this action.";
    exit();
}

require_once "Database.php";

$database = new Database();
$db = $database->getConnection();

// Get the username and new permission level from the form
$username = $_POST['username'];
$permissionLevelID = $_POST['permissionLevelID'];

// Validate inputs
if (empty($username) || !is_numeric($permissionLevelID)) {
    echo "Invalid input.";
    exit();
}

// Update the user's permission level
$query = "UPDATE Users SET PermissionLevelID = :permissionLevelID WHERE Username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(":permissionLevelID", $permissionLevelID);
$stmt->bindParam(":username", $username);

if ($stmt->execute()) {
    echo "Permission level updated successfully.";
} else {
    echo "Failed to update permission level.";
}
?>
