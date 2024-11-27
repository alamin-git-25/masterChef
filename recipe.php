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

// Fetch the blog details
if (isset($_GET['blog_id']) && is_numeric($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];

    $stmt = $pdo->prepare("SELECT * FROM blogs WHERE blog_id = :blog_id");
    $stmt->execute([':blog_id' => $blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$blog) {
        die("Blog not found.");
    }
} else {
    die("Invalid blog ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= htmlspecialchars($blog['title']) ?></title>
</head>
<body class="relative">
<section class="relative">
      <button
        id="menuButton"
        class="p-2 bg-black rounded-full fixed top-5 right-10 z-30"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 24 24"
          stroke-width="1.5"
          stroke="white"
          class="sm:w-8 sm:h-8 w-6 h-6"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
          />
        </svg>
      </button>

      <!-- Header -->
      <header
        id="header"
        class="w-full h-[50vh] bg-black z-50 fixed top-[-100vh] left-0 duration-500 ease-linear"
      >
        <!-- Close Button -->
        <button
          id="closeButton"
          class="absolute bg-white p-2 rounded-full top-5 right-10 hidden"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="sm:w-8 sm:h-8 w-6 h-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6 18L18 6M6 6l12 12"
            />
          </svg>
        </button>

        <!-- Navigation -->
        <nav class="flex justify-center items-center h-full">
          <ul class="text-white text-center space-y-4">
            <li><a href="index.php" class="text-3xl sm:text-7xl font-thin">Home</a></li>
           
            <li>
              <a href="#" class="text-3xl sm:text-7xl font-thin">About</a>
            </li>
            <li>
              <a href="upload.php" class="text-3xl sm:text-7xl font-thin">Upload</a>
            </li>
            <li>
              <a href="#" class="text-3xl sm:text-7xl font-thin">Contact</a>
            </li>
          </ul>
        </nav>
      </header>
    </section>
    <section class="px-40 m-10">
        <article>
            <img src="<?= htmlspecialchars($blog['image']) ?>" alt="<?= htmlspecialchars($blog['title']) ?>"  class=" w-[626px] h-[358px] object-cover">
            <h1 class="text-3xl font-bold"><?= htmlspecialchars($blog['title']) ?></h1>
            <p class="mt-4 text-lg"><?= htmlspecialchars($blog['blog']) ?></p>
        </article>
    </section>
</body>
</html>
