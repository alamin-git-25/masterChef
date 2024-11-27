
<?php
// Start session only if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protect admin routes
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php"); // Redirect to login page if not logged in
    exit();
}
?>
