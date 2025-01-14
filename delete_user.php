<?php
session_start();

// Check if the user is logged in and has Permission Level 3 or higher
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 3) {
    echo "You do not have permission to perform this action.";
    exit();
}

require_once "Database.php";

$database = new Database();
$db = $database->getConnection();

// Get the UserID of the user to delete
$userID = $_POST['deleteUsername'] ?? '';

// Validate input
if (empty($userID)) {
    echo "Invalid input.";
    exit();
}

// Prevent the current user from deleting themselves
if ($userID == $_SESSION['UserID']) {
    echo "You cannot delete yourself.";
    exit();
}

// Delete the user from the database
$query = "DELETE FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $userID, PDO::PARAM_INT);

if ($stmt->execute()) {
    echo "User deleted successfully.";
} else {
    echo "Failed to delete user.";
}
