<?php
// Start the session
session_start();

// Include the database configuration
include '../config.php'; // Update the path if needed

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // Redirect to the login page if the user is not an admin or not logged in
    header("Location: ../auth/login.php");
    exit;
}

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Sanitize the ID to prevent SQL injection
    $blog_id = intval($_GET['id']);

    // Prepare the delete statement
    $stmt = $conn->prepare("DELETE FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $blog_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the dashboard or a success page
        header("Location: dashboard.php?message=Post deleted successfully");
    } else {
        // If there is an error, display it
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    // If no valid ID is provided, redirect back to the dashboard
    header("Location: dashboard.php?message=Invalid post ID");
}

// Close the database connection
$conn->close();
?>
