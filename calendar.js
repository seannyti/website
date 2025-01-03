// calendar.js

// Initialize Pikaday
document.addEventListener('DOMContentLoaded', function() {
    var picker = new Pikaday({
        field: document.getElementById('mini-calendar'),
        bound: false, // Don't bind to an input field
        showWeekNumber: true, // Show week numbers
        firstDay: 1, // Start the week on Monday
        numberOfMonths: 1, // Show only one month
        theme: 'light-theme', // Add your own theme if needed
    });
});
