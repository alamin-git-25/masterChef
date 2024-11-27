<?php
session_start();
include '../config.php'; // Ensure your database connection is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];

    // Basic validation
    if (empty($email) || empty($password) || empty($password_confirmation)) {
        $error_message = "Please fill in all fields.";
    } elseif ($password !== $password_confirmation) {
        $error_message = "Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error_message = "This email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['id'] = $conn->insert_id;
                header("Location: login.php");
                exit();
            } else {
                $error_message = "Error registering the user. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <section class="bg-white">
        <div class="lg:grid lg:min-h-screen lg:grid-cols-12">
            <section class="relative flex h-32 items-end bg-gray-900 lg:col-span-5 lg:h-full xl:col-span-6">
                <img
                    alt="Welcome Image"
                    src="https://images.unsplash.com/photo-1617195737496-bc30194e3a19?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80"
                    class="absolute inset-0 h-full w-full object-cover opacity-80"
                />
                <div class="hidden lg:relative lg:block lg:p-12">
                    <h2 class="mt-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl">
                        Welcome to Squid 🦑
                    </h2>
                    <p class="mt-4 leading-relaxed text-white/90">
                        Join us to stay updated with the latest features and updates.
                    </p>
                </div>
            </section>

            <main class="flex items-center justify-center px-8 py-8 sm:px-12 lg:col-span-7 lg:px-16 lg:py-12 xl:col-span-6">
                <div class="max-w-xl lg:max-w-3xl">
                    <div class="relative -mt-16 block lg:hidden">
                        <h1 class="mt-2 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                            Welcome to Squid 🦑
                        </h1>
                        <p class="mt-4 leading-relaxed text-gray-500">
                            Sign up now to access exclusive content and features.
                        </p>
                    </div>

                    <?php if (isset($error_message)): ?>
                        <p class="text-red-500 mb-4"><?= $error_message ?></p>
                    <?php endif; ?>

                    <form method="POST" action="" class="mt-8 grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="mt-1 h-10 px-4 border w-full rounded-md border-gray-200 bg-white text-sm shadow-sm"
                                required
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="mt-1 h-10 px-4 border w-full rounded-md border-gray-200 bg-white text-sm shadow-sm"
                                required
                            />
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="mt-1 w-full h-10 px-4 border rounded-md border-gray-200 bg-white text-sm shadow-sm"
                                required
                            />
                        </div>

                        <div class="col-span-6">
                            <p class="text-sm text-gray-500">
                                By signing up, you agree to our
                                <a href="#" class="text-gray-700 underline">terms</a> and
                                <a href="#" class="text-gray-700 underline">privacy policy</a>.
                            </p>
                        </div>

                        <div class="col-span-6 sm:flex sm:items-center sm:gap-4">
                            <button
                                type="submit"
                                class="inline-block w-full rounded-md bg-blue-600 px-12 py-3 text-sm font-medium text-white transition hover:bg-blue-700"
                            >
                                Create Account
                            </button>
                        </div>
                    </form>

                    <p class="mt-8 text-sm text-gray-500">
                        Already have an account? 
                        <a href="login.php" class="text-gray-700 underline">Log in</a>.
                    </p>
                </div>
            </main>
        </div>
    </section>
</body>
</html>
