<?php
session_start();
include '../config.php';

// Ensure user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Verify admin role
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['role'] !== 'admin') {
    echo "Access Denied: You are not authorized.";
    exit();
}

// Handle form submission
if (isset($_POST['promote_user'])) {
    $promote_id = $_POST['user_id'];

    // Update user's role to admin
    $stmt = $conn->prepare("UPDATE users SET role = 'admin' WHERE id = ?");
    $stmt->bind_param("i", $promote_id);

    if ($stmt->execute()) {
        $message = "User ID $promote_id has been promoted to admin.";
    } else {
        $message = "Failed to promote user ID $promote_id.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promote User to Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.0.0/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">Promote User to Admin</h1>

        <?php if (isset($message)): ?>
            <p class="text-center text-green-500 mb-4"><?= $message ?></p>
        <?php endif; ?>

        <form method="POST" action="" class="max-w-lg mx-auto bg-white p-6 shadow-lg rounded-lg">
            <label for="user_id" class="block text-gray-700 font-medium mb-2">Enter User ID to Promote:</label>
            <input type="number" name="user_id" id="user_id" class="w-full border-gray-300 rounded-lg p-3 mb-4" required>

            <button type="submit" name="promote_user" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">
                Promote to Admin
            </button>
        </form>
    </div>
</body>
</html>
