<?php
session_start();


// Check if the user is logged in
$isLoggedIn = isset($_SESSION['UserID']);
$username = $isLoggedIn ? ucfirst($_SESSION['Username']) : '';
$permissionLevelID = $isLoggedIn ? $_SESSION['PermissionLevelID'] : 0;

// Debugging: Output the permission level
// echo "Debug: PermissionLevelID = " . $permissionLevelID;

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
    <!-- Include Navbar -->
    <?php include 'navbar.php'; ?>

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
