<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/bag.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/dashboard.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="wrapper">
        <h2>Welcome to your Dashboard</h2>
        
        <p>Dashboard content goes here.</p>
    </div>

    <script>
        // Check if the 'userid' is present in localStorage
        const userid = localStorage.getItem('userid');
        console.log("userid from localStorage:", userid); // Log for debugging

        // If 'userid' is not found, redirect to the login page
        if (!userid) {
            window.location.href = '/';
        }
        let isTabClosing = false;

// Listen for visibility changes
document.addEventListener('visibilitychange', function () {
    if (document.visibilityState === 'hidden') {
        isTabClosing = true; // Likely the tab is being closed
    }
});

// Handle 'beforeunload' event
window.addEventListener('beforeunload', function () {
    if (isTabClosing) {
        // Remove 'userid' only when the tab is closing, not on reload or navigation
        localStorage.removeItem('userid');
    }
});
    </script>
</body>
</html>
