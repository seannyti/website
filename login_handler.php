<?php
session_start();
require_once "Database.php";
require_once "User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->Username = $_POST['username'];
$user->PasswordHash = $_POST['password'];

if ($user->login()) {
    // Store user ID, username, and permission level in the session
    $_SESSION['UserID'] = $user->UserID;
    $_SESSION['Username'] = $user->Username;
    $_SESSION['PermissionLevelID'] = $user->PermissionLevelID; // Ensure this is set

    // Redirect to index.php after successful login
    header("Location: index.php");
    exit();
} else {
    // Handle login failure
    echo "Invalid username or password.";
}
?>
