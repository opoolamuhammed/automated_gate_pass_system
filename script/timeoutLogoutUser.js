// Function to logout user
function logoutUser() {
    window.location.href = 'logout.php'; // Redirect to logout page
}

// Set timeout for logout after 30 minutes (1800000 milliseconds)
var logoutTimeout = setTimeout(logoutUser, 1800000);

// Function to reset the logout timeout
function resetLogoutTimeout() {
    clearTimeout(logoutTimeout); // Clear previous timeout
    logoutTimeout = setTimeout(logoutUser, 1800000); // Set new timeout
}

// Add event listeners to monitor user activity
document.addEventListener('mousemove', resetLogoutTimeout);
document.addEventListener('keypress', resetLogoutTimeout);
document.addEventListener('scroll', resetLogoutTimeout);