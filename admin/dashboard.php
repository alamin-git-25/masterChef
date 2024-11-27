<?php
// Include the database connection and admin header
include '../config.php'; // Ensure the path to config.php is correct
include 'admin_header.php';
?>
<?php
include 'admin_protect.php'; // Protect the admin route
?>


<section>
<div class="mx-auto max-w-screen-xl px-4 py-8 sm:px-6 sm:py-12 lg:px-8">
  <div class="mx-auto max-w-3xl text-center">
    <h2 class="text-3xl font-bold text-gray-900 sm:text-4xl">Welcome To Admin Dashboard</h2>

    <p class="mt-4 text-gray-500 sm:text-xl">
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Ratione dolores laborum labore
      provident impedit esse recusandae facere libero harum sequi.
    </p>
  </div>

  <dl class="mt-6 grid grid-cols-1 gap-4 sm:mt-8 sm:grid-cols-2 lg:grid-cols-2">
    <div class="flex flex-col rounded-lg bg-blue-50 px-4 py-8 text-center">
      <dt class="order-last text-lg font-medium text-gray-500">Total Blogs</dt>

      <dd class="text-4xl font-extrabold text-blue-600 md:text-5xl"> <?php 
            // Query to count total posts
            $result = $conn->query("SELECT COUNT(*) AS total FROM blogs");
            if ($result) {
                $row = $result->fetch_assoc();
                echo $row['total'];
            } else {
                echo "Error fetching posts: " . $conn->error;
            }
            ?></dd>
    </div>

    

    <div class="flex flex-col rounded-lg bg-blue-50 px-4 py-8 text-center">
      <dt class="order-last text-lg font-medium text-gray-500">Total User</dt>

      <dd class="text-4xl font-extrabold text-blue-600 md:text-5xl"><?php 
            // Query to count total users
            $result = $conn->query("SELECT COUNT(*) AS total FROM users");
            if ($result) {
                $row = $result->fetch_assoc();
                echo $row['total'];
            } else {
                echo "Error fetching users: " . $conn->error;
            }
            ?></dd>
    </div>
  </dl>
</div>
</section>

<?php include 'admin_footer.php'; ?>

