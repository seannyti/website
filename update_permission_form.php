<?php
session_start();

// Check if the user is logged in and has permission to update permissions
if (!isset($_SESSION['UserID']) || $_SESSION['PermissionLevelID'] < 4) {
    echo "You do not have permission to perform this action.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Permission</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-content">
        <h1>Update User Permission</h1>
        <form action="update_permission.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <br><br>
            <label for="permissionLevelID">Permission Level:</label>
            <input type="number" id="permissionLevelID" name="permissionLevelID" min="1" max="4" required>
            <br><br>
            <button type="submit">Update Permission</button>
        </form>
    </div>
</body>
</html>

