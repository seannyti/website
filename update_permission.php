<?php
session_start();

// Check if the user is logged in and has permission to update permissions
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 4) {
    echo json_encode(["success" => false, "message" => "You do not have permission to perform this action."]);
    exit();
}

require_once "Database.php";

$database = new Database();
$db = $database->getConnection();

// Get the username and new permission level from the form
$username = $_POST['username'] ?? '';
$permissionLevelID = $_POST['permissionLevelID'] ?? 0;

// Validate inputs
if (empty($username) || !is_numeric($permissionLevelID) || $permissionLevelID < 1 || $permissionLevelID > 4) {
    echo json_encode(["success" => false, "message" => "Invalid input."]);
    exit();
}

// Update the user's permission level
$query = "UPDATE Users SET PermissionLevelID = :permissionLevelID WHERE Username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(":permissionLevelID", $permissionLevelID, PDO::PARAM_INT);
$stmt->bindParam(":username", $username, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Permission level updated successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update permission level."]);
}
?>
