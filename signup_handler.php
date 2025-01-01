<?php
session_start();
require_once "Database.php";
require_once "User.php";

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$user->Username = $_POST['username'];
$user->Email = $_POST['email'];
$user->PasswordHash = $_POST['password'];

// Check if the username already exists
$usernameExists = $user->usernameExists();

// Check if the email already exists
$emailExists = $user->emailExists();

if ($usernameExists && $emailExists) {
    // Both username and email already exist
    header("Location: signup.php?error=Both the username and email are already taken.");
    exit();
} elseif ($usernameExists) {
    // Username already exists
    header("Location: signup.php?error=This username is already taken.");
    exit();
} elseif ($emailExists) {
    // Email already exists
    header("Location: signup.php?error=This email is already registered.");
    exit();
} else {
    // Username and email do not exist, proceed with registration
    if ($user->signup()) {
        // Redirect to login page after successful registration
        header("Location: login.php");
        exit();
    } else {
        // Handle registration failure
        header("Location: signup.php?error=Failed to register user.");
        exit();
    }
}
?>
