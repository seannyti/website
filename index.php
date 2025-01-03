<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css?v=1.0.1">
    <link rel="stylesheet" href="mobile.css?v=1.0.1">
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
