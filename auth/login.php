<?php
session_start();
include '../config.php'; // Ensure your database connection is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by email
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // Admin or user role
            header("Location: ../../blogs/admin/dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "Invalid email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white">
<section class="bg-white">
  <div class="lg:grid lg:min-h-screen lg:grid-cols-12">
    <section class="relative flex h-32 items-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
      <img
        alt=""
        src="https://images.unsplash.com/photo-1617195737496-bc30194e3a19?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
        class="absolute inset-0 h-full w-full object-cover opacity-80"
      />
      <div class="hidden lg:relative lg:block lg:p-12">
        <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">Welcome Back</h2>
        <p class="mt-4 leading-relaxed text-white/90">Log in to manage your content and stay connected.</p>
      </div>
    </section>
    <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
      <div class="max-w-xl lg:max-w-3xl">
        <form method="POST" action="" class="mt-8 grid grid-cols-6 gap-6">
          <div class="col-span-6">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              type="email"
              id="email"
              name="email"
              class="mt-1 w-full h-10 px-4 border rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm"
              required
            />
          </div>
          <div class="col-span-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
              type="password"
              id="password"
              name="password"
              class="mt-1 w-full h-10 px-4 border rounded-md border-gray-200 bg-white text-sm text-gray-700 shadow-sm"
              required
            />
          </div>
          <?php if (isset($error_message)): ?>
            <div class="col-span-6 text-red-500 text-sm text-center"><?= $error_message ?></div>
          <?php endif; ?>
          <div class="col-span-6 sm:flex sm:items-center sm:gap-4">
            <button
              type="submit"
              class="inline-block shrink-0 rounded-md border border-blue-600 bg-blue-600 px-12 py-3 text-sm font-medium text-white transition hover:bg-transparent hover:text-blue-600 focus:outline-none focus:ring active:text-blue-500"
            >
              Log In
            </button>
            <p class="mt-4 text-sm text-gray-500 sm:mt-0">
              Don't have an account?
              <a href="signup.php" class="text-gray-700 underline">Sign up here</a>.
            </p>
          </div>
        </form>
      </div>
    </main>
  </div>
</section>
</body>
</html>
