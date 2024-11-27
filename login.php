<?php
session_start();
include 'config.php'; // Include the database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to find the user by email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, check if the password is correct
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, start session and redirect to dashboard
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: dashboard.php'); // Redirect to the dashboard
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with that email address!";
    }
}
?>

<form method="POST" action="">
    <label for="email">Email: </label>
    <input type="email" name="email" required><br>

    <label for="password">Password: </label>
    <input type="password" name="password" required><br>

    <button type="submit">Login</button>
</form>
