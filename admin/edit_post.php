<?php
include '../config.php'; // Include database connection
include 'admin_header.php'; // Include admin header

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Securely fetch the blog post details
    $stmt = $conn->prepare("SELECT * FROM blogs WHERE blog_id = ?");
    $stmt->bind_param("i", $id); // Binding the ID parameter to avoid SQL injection
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
}

// Handle form submission and update logic
if (isset($_POST['update_post'])) {
    $title = $_POST['blog_title'];
    $description = $_POST['blog_description'];
    $blog_content = $_POST['blog_content'];
    $image = $post['image']; // Use the existing image initially

    // Handle image upload if a new file is selected
    if (!empty($_FILES['blog_image']['name'])) {
        $image_name = $_FILES['blog_image']['name'];
        $image_tmp_name = $_FILES['blog_image']['tmp_name'];
        $image_folder = "uploads/" . $image_name;

        if (move_uploaded_file($image_tmp_name, $image_folder)) {
            $image = $image_folder; // Update the image path
        } else {
            echo "<p class='text-red-600'>Failed to upload image.</p>";
        }
    }

    // Update the post in the database
    $update_stmt = $conn->prepare("UPDATE blogs SET title = ?, description = ?, blog = ?, image = ? WHERE blog_id = ?");
    $update_stmt->bind_param("ssssi", $title, $description, $blog_content, $image, $id);
    if ($update_stmt->execute()) {
        echo "<p class='text-green-600'>Post updated successfully!</p>";
    } else {
        echo "<p class='text-red-600'>Error updating post.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Edit Blog Post</title>
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
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-6 w-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"
                            />
                        </svg>
                    </span>
                    <p class="text-sm">Drag & drop an image or click to select</p>
                </label>
            </div>

            <!-- Preview Image -->
            <div>
                <img id="preview" src="<?= $post['image'] ?>" alt="Image Preview" style="<?= $post['image'] ? '' : 'display: none;' ?>">
            </div>

            <!-- Blog Title -->
            <div>
                <label for="blog_title" class="block font-medium text-gray-700">Blog Title</label>
                <input
                    type="text"
                    id="blog_title"
                    name="blog_title"
                    value="<?= $post['title'] ?>"
                    class="mt-1 w-full h-12 px-3 rounded-md border border-black shadow-sm sm:text-sm"
                    required
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
                    required
                ><?= $post['description'] ?></textarea>
            </div>

            <!-- Blog Content -->
            <div class="my-4">
                <label for="blog_content">Blog Content</label>
                <textarea
                    id="blog_content"
                    name="blog_content"
                    class="w-full p-3 resize-none rounded-lg border border-black sm:text-sm"
                    rows="16"
                    required
                ><?= $post['blog'] ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end items-center gap-4">
                <button type="submit" name="update_post" class="rounded bg-indigo-600 px-8 py-3 text-white hover:scale-110">
                    Update
                </button>
                <button type="reset" class="rounded bg-red-600 px-8 py-3 text-white hover:scale-110">
                    Cancel
                </button>
            </div>
        </form>
    </section>

    <script>
        const dragArea = document.getElementById('drag-area');
        const blogImageInput = document.getElementById('blog_image');
        const preview = document.getElementById('preview');

        // Drag & Drop functionality
        dragArea.addEventListener('dragover', (event) => {
            event.preventDefault();
            dragArea.classList.add('dragover');
        });

        dragArea.addEventListener('dragleave', () => dragArea.classList.remove('dragover'));

        dragArea.addEventListener('drop', (event) => {
            event.preventDefault();
            dragArea.classList.remove('dragover');

            const file = event.dataTransfer.files[0];
            if (file) {
                showPreview(file);
                blogImageInput.files = event.dataTransfer.files; // Update the file input for form submission
            }
        });

        // Show image preview
        blogImageInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                showPreview(file);
            }
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
