<?php
// Include the admin header
include 'admin_header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Blog Post</title>
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
    <!-- Responsive Menu -->
    <section class="relative">
        <button id="menuButton" class="p-2 bg-black rounded-full fixed top-5 right-10 z-30">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="white" class="sm:w-8 sm:h-8 w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <header id="header" class="w-full h-[50vh] bg-black fixed top-[-100vh] left-0 duration-500 ease-linear z-50">
            <button id="closeButton" class="absolute bg-white p-2 rounded-full top-5 right-10 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="sm:w-8 sm:h-8 w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <nav class="flex justify-center items-center h-full">
                <ul class="text-white text-center space-y-4">
                    <li><a href="index.php" class="text-3xl sm:text-7xl font-thin">Home</a></li>
                    <li><a href="#" class="text-3xl sm:text-7xl font-thin">About</a></li>
                    <li><a href="add_post.php" class="text-3xl sm:text-7xl font-thin">Upload</a></li>
                    <li><a href="#" class="text-3xl sm:text-7xl font-thin">Contact</a></li>
                </ul>
            </nav>
        </header>
    </section>

    <!-- Blog Upload Form -->
    <section class="px-40 m-10">
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Blog Image Upload -->
            <div class="relative my-6">
                <label>Blog Image</label>
                <input id="blog_image" name="image" type="file" class="peer hidden" accept=".gif,.jpg,.png,.jpeg" required />
                <label id="drag-area" for="blog_image" class="flex cursor-pointer flex-col items-center gap-6 rounded border border-dashed border-black px-6 py-20 text-center drag-area">
                    <span class="inline-flex h-12 items-center justify-center self-center rounded bg-slate-100/70 px-3 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" aria-label="File input icon" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </span>
                    <p class="text-sm">
                        <span class="text-emerald-500">Upload media</span>
                        <span class="text-slate-500">2048 x 1701 pixels preferred.</span>
                    </p>
                </label>
            </div>
            <div><img id="preview" src="#" alt="Image Preview" style="display: none;"></div>

            <!-- Blog Title -->
            <div>
                <label for="title" class="block font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 h-10 border w-full rounded-md border shadow-sm sm:text-sm" placeholder="Enter blog title" required>
            </div>

            <!-- Blog Description -->
            <div class="my-4">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="w-full p-3 rounded-lg border sm:text-sm" rows="4" placeholder="Enter blog description" required></textarea>
            </div>

            <!-- Blog Content -->
            <div class="my-4">
                <label for="blog">Content</label>
                <textarea id="blog" name="blog" class="w-full p-3 rounded-lg border sm:text-sm" rows="16" placeholder="Enter blog content" required></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end gap-4">
                <button type="submit" name="add_post" class="rounded bg-indigo-600 px-8 py-3 text-white hover:scale-110">Upload</button>
                <button type="reset" class="rounded bg-red-600 px-8 py-3 text-white hover:scale-110">Cancel</button>
            </div>
        </form>
    </section>

    <script>
        const dragArea = document.getElementById('drag-area');
        const blogImageInput = document.getElementById('blog_image');
        const preview = document.getElementById('preview');

        dragArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dragArea.classList.add('dragover');
        });

        dragArea.addEventListener('dragleave', () => dragArea.classList.remove('dragover'));
        dragArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dragArea.classList.remove('dragover');
            const file = event.dataTransfer.files[0];
            if (file) showPreview(file);
        });

        blogImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) showPreview(file);
        });

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
if (isset($_POST['add_post'])) {
    include '../config.php';

    $title = $_POST['title'];
    $description = $_POST['description'];
    $blog = $_POST['blog'];
    $image = $_FILES['image'];

    $image_name = $image['name'];
    $image_tmp_name = $image['tmp_name'];
    $image_folder = "upload/" . $image_name;

    if (move_uploaded_file($image_tmp_name, $image_folder)) {
        $stmt = $conn->prepare("INSERT INTO blogs (image, title, description, blog) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $image_folder, $title, $description, $blog);
        if ($stmt->execute()) {
            echo "<p class='text-green-600'>Post added successfully!</p>";
        } else {
            echo "<p class='text-red-600'>Failed to add post.</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='text-red-600'>Failed to upload image.</p>";
    }
}
?>
