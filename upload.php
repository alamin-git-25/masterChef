<?php
include './admin/admin_header.php'
?>
<?php
// Database connection details
$host = 'localhost';
$dbname = 'blogs';
$username = 'root'; // Replace with your database username
$password = '7175@vivo'; // Replace with your database password

try {
    // Create a PDO instance for database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogTitle = $_POST['blog_title'] ?? '';
    $blogDescription = $_POST['blog_description'] ?? '';
    $blogContent = $_POST['blog_content'] ?? '';
    $uploadDir = 'uploads/';
    $imagePath = null;

    // Ensure the uploads directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Handle the file upload
    if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['blog_image']['tmp_name'];
        $fileName = time() . '_' . basename($_FILES['blog_image']['name']); // Unique filename
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFilePath)) {
            $imagePath = $targetFilePath;
        }
    }

    // Insert the data into the database
    $sql = "INSERT INTO blogs (image, title, description, blog) VALUES (:image, :title, :description, :blog)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':image' => $imagePath,
        ':title' => $blogTitle,
        ':description' => $blogDescription,
        ':blog' => $blogContent,
    ]);

    echo "<p>Blog has been uploaded successfully!</p>";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Upload Blog</title>
    <style>
        .drag-area {
            transition: background-color 0.3s ease;
        }
        .drag-area.dragover {
            background-color: #f0f0f0;
        }
        #preview {
            max-width: 100%;
            max-height: 200px;
            margin-top: 1rem;
            border: 1px solid #ccc;
            padding: 5px;
        }
    </style>
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
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Blog Image Upload -->
            <div class="relative my-6">
                <label>Blog Image</label>
                <input
                    id="blog_image"
                    name="blog_image"
                    type="file"
                    class="peer hidden"
                    accept=".gif,.jpg,.png,.jpeg"
                />
                <label
                    id="drag-area"
                    for="blog_image"
                    class="flex cursor-pointer flex-col items-center gap-6 rounded border border-dashed border-black px-6 py-20 text-center drag-area"
                >
                    <span class="inline-flex h-12 items-center justify-center self-center rounded bg-slate-100/70 px-3 text-slate-400">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            aria-label="File input icon"
                            role="graphics-symbol"
                            fill="none"
                            viewBox="0 0 24 24"
                            strokeWidth="1.5"
                            stroke="currentColor"
                            class="h-6 w-6"
                        >
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                            />
                        </svg>
                    </span>
                    <p class="flex flex-col items-center justify-center gap-1 text-sm">
                        <span class="text-emerald-500 hover:text-emerald-500">
                            Upload media <span class="text-slate-500"> 2048 pixels in width and 1701 pixels in height. </span>
                        </span>
                    </p>
                </label>
            </div>

            <!-- Preview Image -->
            <div>
                <img id="preview" src="#" alt="Image Preview" style="display: none;">
            </div>

            <!-- Blog Title -->
            <div>
                <label for="blog_title" class="block font-medium text-gray-700">Blog Title</label>
                <input
                    type="text"
                    id="blog_title"
                    name="blog_title"
                    placeholder="Enter blog title"
                    class="mt-1 w-full h-12 px-3 rounded-md border border-black shadow-sm sm:text-sm"
                />
            </div>

            <!-- Blog Description -->
            <div class="my-4">
                <label for="blog_description">Blog Description</label>
                <textarea
                    id="blog_description"
                    name="blog_description"
                    class="w-full p-3 resize-none rounded-lg border border-black sm:text-sm"
                    rows="4"
                    placeholder="Enter a brief blog description"
                ></textarea>
            </div>

            <!-- Blog Content -->
            <div class="my-4">
                <label for="blog_content">Blog</label>
                <textarea
                    id="blog_content"
                    name="blog_content"
                    class="w-full p-3 resize-none rounded-lg border border-black sm:text-sm"
                    rows="16"
                    placeholder="Enter the blog content"
                ></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end items-center gap-4">
                <button type="submit" class="inline-block rounded bg-indigo-600 px-8 py-3 text-sm font-medium text-white transition hover:scale-110 hover:shadow-xl focus:outline-none focus:ring active:bg-indigo-500">
                    Upload
                </button>
                <button type="reset" class="inline-block rounded bg-red-600 px-8 py-3 text-sm font-medium text-white transition hover:scale-110 hover:shadow-xl focus:outline-none focus:ring active:bg-red-500">
                    Cancel
                </button>
            </div>
        </form>
    </section>

    <script>
        const dragArea = document.getElementById('drag-area');
        const blogImageInput = document.getElementById('blog_image');
        const preview = document.getElementById('preview');

        // Handle drag and drop events
        dragArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dragArea.classList.add('dragover');
        });

        dragArea.addEventListener('dragleave', () => {
            dragArea.classList.remove('dragover');
        });

        dragArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dragArea.classList.remove('dragover');

            const file = event.dataTransfer.files[0];
            if (file) {
                showPreview(file);
                blogImageInput.files = event.dataTransfer.files; // Update file input for form submission
            }
        });

        // Handle file selection through the file input
        blogImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                showPreview(file);
            }
        });

        // Display image preview
        function showPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    </script>
    
</body>
</html>
<?php
include './admin/admin_footer.php'
?>
