<?php
// profile_right.php

// Include necessary files (if needed)
require_once "Database.php";
require_once "User.php";
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

    <!-- Add more components here (e.g., notifications, stats, etc.) -->
</div>

<!-- Pikaday JS -->
<script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
<script>
    // Initialize Pikaday
    var picker = new Pikaday({
        field: document.getElementById('mini-calendar'),
        bound: false, // Don't bind to an input field
        showWeekNumber: true, // Show week numbers
        firstDay: 1, // Start the week on Monday
        numberOfMonths: 1, // Show only one month
        theme: 'light-theme', // Add your own theme if needed
    });
</script>
