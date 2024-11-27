<?php
$host = "localhost";
$user = "root";
$password = "7175@vivo"; // Default password for XAMPP's MySQL is empty
$database = "blogs";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>


