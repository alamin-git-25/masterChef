<?php
// Database connection
$host = 'localhost';
$dbname = 'blogs';
$username = 'root';
$password = '7175@vivo';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Pagination setup
$limit = 9;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get the search term from the request (if any)
$searchTerm = isset($_GET['search']) ? "%" . $_GET['search'] . "%" : "%";

// Fetch recipes for the current page with the search term
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE title LIKE :searchTerm OR description LIKE :searchTerm LIMIT :limit OFFSET :offset");
$stmt->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch total number of records for the search term
$totalQuery = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE title LIKE :searchTerm OR description LIKE :searchTerm");
$totalQuery->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
$totalQuery->execute();
$totalRecords = $totalQuery->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// If no recipes are found, return a "Data Not Found" message
if (empty($recipes)) {
    echo json_encode(['message' => 'Data Not Found']);
} else {
    echo json_encode(['recipes' => $recipes, 'totalPages' => $totalPages]);
}
?>
