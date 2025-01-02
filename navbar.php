<?php
session_start();
$isLoggedIn = isset($_SESSION['UserID']);
$username = $isLoggedIn ? ucfirst($_SESSION['Username']) : '';
$permissionLevelID = $isLoggedIn ? $_SESSION['PermissionLevelID'] : 0;
?>

<!-- Navigation bar -->
<div class="navbar">
    <!-- Left-aligned links -->
    <div class="navbar-left">
        <a href="index.php">Home</a>
        <a href="#about">About</a>
        <a href="#contact">Contact</a>
    </div>

    <!-- Right-aligned account dropdown -->
    <div class="navbar-right">
        <div class="dropdown">
            <?php if ($isLoggedIn): ?>
                <!-- Logged in: Show username and dropdown for Logout and Profile -->
                <a href="#" class="dropbtn"><?php echo htmlspecialchars($username); ?></a>
                <div class="dropdown-content">
                    <a href="profile.php">Profile</a>
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
