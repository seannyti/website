<?php
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['UserID']);
$username = $isLoggedIn ? ucfirst($_SESSION['Username']) : '';
$permissionLevelID = $isLoggedIn ? $_SESSION['PermissionLevelID'] : 0;

// Redirect to the dashboard if the user is logged in and has permission level 3 or higher
if ($isLoggedIn && $permissionLevelID >= 3) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation bar -->
    <div class="navbar">
        <!-- Left-aligned links -->
        <div class="navbar-left">
            <a href="#home">Home</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
        </div>

        <!-- Right-aligned account dropdown -->
        <div class="navbar-right">
            <div class="dropdown">
                <?php if ($isLoggedIn): ?>
                    <!-- Logged in: Show username and dropdown for Logout -->
                    <a href="#" class="dropbtn"><?php echo htmlspecialchars($username); ?></a>
                    <div class="dropdown-content">
                        <a href="logout.php">Logout</a>
                    </div>
                <?php else: ?>
                    <!-- Logged out: Show Account and dropdown for Login -->
                    <a href="#" class="dropbtn">Account</a>
                    <div class="dropdown-content">
                        <a href="login.php">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <?php if ($isLoggedIn): ?>
            <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <?php else: ?>
            <h1>Home</h1>
        <?php endif; ?>
        <p>Test Test Test</p>
    </div>
</body>
</html>
