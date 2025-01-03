<?php
// profile_right.php

// Include necessary files
require_once "Database.php";
require_once "User.php";

$database = new Database();
$db = $database->getConnection();

// Fetch all users from the database
$query = "SELECT UserID, Username, ProfilePicture FROM Users";
$stmt = $db->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Pikaday CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">

<!-- Right Section Content -->
<div class="profile-right-section">
    <!-- Mini Calendar Section -->
    <div class="calendar-section">
        <h2>Calendar</h2>
        <div class="mini-calendar">
            <div id="mini-calendar"></div>
        </div>
    </div>

    <!-- Users Section -->
    <div class="users-section">
        <h2 class="users-heading">Users</h2>
        <div class="users-list">
            <?php foreach ($users as $user): ?>
                <!-- Link to User Profile -->
                <a href="profile.php?user_id=<?php echo $user['UserID']; ?>" class="user-card">
                    <div class="user-picture">
                        <img src="uploads/<?php echo !empty($user['ProfilePicture']) ? htmlspecialchars($user['ProfilePicture']) : 'default.png'; ?>" alt="Profile Picture" width="40" height="40">
                    </div>
                    <div class="user-name">
                        <?php echo htmlspecialchars($user['Username']); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Pikaday JS -->
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<!-- Include the external JavaScript file -->
<script src="calendar.js"></script>
