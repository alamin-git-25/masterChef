<?php 
include '../config.php'; // Ensure correct path to config.php
include 'admin_header.php'; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html class="">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .img{
            width: 300px;
            aspect-ratio:16/9;
        
        }
        html{
            scrollbar-width: none;
        }
    </style>
</head>
<body>
<h1 class="text-5xl my-5 ml-4">Manage Posts</h1>
<a href="../upload.php" class="inline-block ml-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add New Post</a>

<table class="w-full border-collapse border border-gray-300 mt-4 text-left">
    <thead>
        <tr class="bg-gray-100">
            <th class="border border-gray-300 px-4 py-2">Image</th>
            <th class="border border-gray-300 px-4 py-2">Title</th>
            <th class="border border-gray-300 px-4 py-2">Description</th>
            <th class="border border-gray-300 px-4 py-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM blogs");
        
        if ($result && $result->num_rows > 0) {
            while ($post = $result->fetch_assoc()) {
                $imagePath = $post['image'];
                $imageUrl = "http://localhost/blogs/" . $imagePath;
                echo "
                <tr>
                    <td class='border border-gray-300 px-4 py-2'>
                       <img src='{$imageUrl}' alt='Post Image' class='img rounded'>
                    </td>
                    <td class='border border-gray-300 px-4 py-2'>{$post['title']}</td>
                    <td class='border border-gray-300 px-4 py-2'>" . substr($post['description'], 0, 50) . "...</td>
                    <td class='border border-gray-300 px-4 py-2'>
                        <a href='edit_post.php?id={$post['blog_id']}' class='text-blue-600 hover:underline'>Edit</a> |
                        <a href='delete_post.php?id={$post['blog_id']}' class='text-red-600 hover:underline' onclick='return confirm(\"Are you sure you want to delete this post?\")'>Delete</a>
                    </td>
                </tr>";
                
            }
        } else {
            echo "
            <tr>
                <td colspan='4' class='text-center border border-gray-300 px-4 py-2'>No posts available</td>
            </tr>";
        }
        ?>
    </tbody>
</table>
</body>
</html>


<?php include 'admin_footer.php'; ?>
