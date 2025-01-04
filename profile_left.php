<?php
// profile_left.php

// Check if the user is logged in (if needed)
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Fetch the current user's profile picture and username
$query = "SELECT ProfilePicture, Username FROM Users WHERE UserID = :userID";
$stmt = $db->prepare($query);
$stmt->bindParam(":userID", $user->UserID);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$profilePicture = $userData['ProfilePicture'] ?? 'default.png'; // Default to PNG
$username = $userData['Username'] ?? 'User';
?>

<!-- Profile Left Section -->
<div class="profile-left-section">
    <div class="user-info">
        <div class="profile-picture" onclick="openUploadBox()">
            <img src="uploads/<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" width="50" height="50">
            <div class="upload-text <?php echo ($profilePicture === 'default.png') ? 'always-visible' : ''; ?>">Upload</div>
        </div>
        <div class="user-name">
            <?php echo htmlspecialchars($username); ?>
        </div>
    </div>

    <!-- Admin Button -->
    <?php if ($_SESSION['PermissionLevelID'] >= 4): ?>
        <button class="admin-button" onclick="openAdminPopup()">User Permissions</button>
    <?php endif; ?>
</div>

<!-- Admin Popup Box -->
<div id="adminPopup" class="popup-box">
    <div class="popup-content">
        <span class="close-button" onclick="closeAdminPopup()">&times;</span>	
        <!-- Include the update permission form -->
        <?php include 'update_permission_form.php'; ?>
    </div>
</div>
