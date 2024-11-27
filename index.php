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

// Fetch total number of records
$totalQuery = $pdo->query("SELECT COUNT(*) FROM blogs");
$totalRecords = $totalQuery->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Fetch records for the current page
$stmt = $pdo->prepare("SELECT * FROM blogs LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html class="scroll-thin">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="style.css">
    <title>Be Come A Master Chef</title>
    <style>
        
        
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
            <li><a href="#" class="text-3xl sm:text-7xl font-thin">Home</a></li>
            <li><a href="#s" class="text-3xl sm:text-7xl font-thin">Recipes</a></li>
            <li>
              <a href="#" class="text-3xl sm:text-7xl font-thin">About</a>
            </li>
            <li>
              <a href="./admin/dashboard.php" class="text-3xl sm:text-7xl font-thin">Dashboard</a>
            </li>
            <li>
              <a href="#" class="text-3xl sm:text-7xl font-thin">Contact</a>
            </li>
          </ul>
        </nav>
      </header>
    </section>
    <!-- Header Section -->
    <section class="relative">
      <!-- Background Image -->
      <div class="bg-indigo-400 w-full h-screen fixed z-[-100] top-0 left-0">
        <img
          src="https://images.unsplash.com/photo-1556911220-bff31c812dba?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8a2l0Y2hlbnxlbnwwfHwwfHx8MA%3D%3D"
          alt="Kitchen Background"
          class="w-full h-full object-cover"
        />
      </div>

      <!-- Content -->
      <div
        class="relative mx-auto px-4 py-32 bg-black/45 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8 z-10"
      >
        <div class="w-full lg:ml-52">
          <h1 class="text-3xl font-bold text-white sm:text-9xl">
            become a
            <strong class="block font-bold text-green-300">master chef.</strong>
          </h1>

          <p class="mt-4 max-w-lg text-white sm:text-xl/relaxed">
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Nesciunt
            illo tenetur fuga ducimus numquam ea!
          </p>

          <div class="mt-8 flex flex-wrap gap-4 text-center">
            <!-- Get Started Button -->
            <a
              href="#s"
              class="block w-full rounded bg-rose-600 px-12 py-3 text-sm font-medium text-white shadow hover:bg-rose-700 focus:outline-none focus:ring active:bg-rose-500 sm:w-auto"
            >
              Get Started
            </a>

            <!-- Learn More Button -->
            <a
              href="#"
              class="block w-full rounded bg-white px-12 py-3 text-sm font-medium text-rose-600 shadow hover:text-rose-700 focus:outline-none focus:ring active:text-rose-500 sm:w-auto"
            >
              Learn More
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Recipes Section -->
    <section id="s" class="bg-gray-50 sm:p-10 p-5 min-h-screen overflow-hidden">
    <header class="sm:p-10 p-3 gap-5 flex flex-col justify-center items-center">
            <h3 class="text-center font-bold text-slate-700 text-3xl sm:text-7xl">
                Recipes For You
            </h3>
            <div class="relative sm:w-[50%] w-full">
                <label for="search" class="sr-only">Search</label>
                <input
                    type="text"
                    id="search"
                    placeholder="Search for Recipes"
                    class="w-full border h-10 px-3 rounded-md border-gray-200 py-2.5 pr-10 shadow-sm sm:text-sm"
                />
                <span class="absolute inset-y-0 right-0 grid w-10 place-content-center">
                    <button type="button" class="text-gray-600 hover:text-gray-700">
                        <span class="sr-only">Search</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="h-5 w-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                            />
                        </svg>
                    </button>
                </span>
            </div>
        </header>

        <!-- Recipes Grid -->
        <div id="recipes-section" class="grid sm:grid-cols-3 grid-cols-1 gap-5"></div>

        <!-- Pagination -->
        <div id="pagination" class="flex justify-center mt-6"></div>
    </section>
    

    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const recipesSection = document.getElementById("recipes-section");
    const paginationDiv = document.getElementById("pagination");
    const searchInput = document.getElementById("search");

    let searchTerm = '';

    // Function to fetch recipes from the server
    async function fetchRecipes(page = 1, search = '') {
        try {
            const response = await fetch(`fetch_recipes.php?page=${page}&search=${search}`);
            const data = await response.json();

            // If no data is found, show "Data Not Found" message
            if (data.message === "Data Not Found") {
                recipesSection.innerHTML = '<div class=" w-screen mt-20"><p class="text-center sm:text-5xl text-3xl ">Recepies Not Found</p> </div>';
                paginationDiv.innerHTML = '';  // Clear pagination
                return;
            }

            const { recipes, totalPages } = data;
console.log(data);

            // Clear previous content
            recipesSection.innerHTML = '';

            // Append new recipes to the grid
            recipes.forEach((recipe) => {
              console.log(recipe.image);
              
                const recipeHTML = `
                    <article class="overflow-hidden rounded-lg shadow transition hover:shadow-lg">
                        <img
                            src="${recipe.image}"
                            alt="${recipe.title}"
                            width="626"
                            height="358"
                            class=" w-[626px] h-[358px] object-cover"
                        />
                        <div class="bg-white w-full h-full p-4  sm:p-6">
                            <time datetime="2022-10-10" class="block text-xs text-gray-500">
                                10th Oct 2022
                            </time>
                            <a href="recipe.php?blog_id=${recipe.blog_id}">
                                <h3 class="mt-0.5 text-lg text-gray-900">${recipe.title}</h3>
                            </a>
                            <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-500">
                                ${recipe.description}
                            </p>
                            <a href="recipe.php?blog_id=${recipe.blog_id}" class="group mt-4 inline-flex items-center gap-1 text-sm font-medium text-blue-600">
                                Read more
                                <span aria-hidden="true" class="block transition-all group-hover:ms-0.5 rtl:rotate-180">
                                    &rarr;
                                </span>
                            </a>
                        </div>
                    </article>
                `;
                recipesSection.innerHTML += recipeHTML;
            });

            // Create pagination
            let paginationHTML = "";
            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `
                    <a href="#" data-page="${i}" class="px-4 py-2 text-gray-600 ${
                        i === page ? "bg-blue-500 text-white" : "bg-gray-200"
                    } rounded hover:bg-gray-300">${i}</a>
                `;
            }
            paginationDiv.innerHTML = paginationHTML;

            // Add click event listeners to pagination links
            const links = paginationDiv.querySelectorAll("a");
            links.forEach((link) => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const selectedPage = parseInt(link.getAttribute("data-page"));
                    fetchRecipes(selectedPage, search);
                });
            });
        } catch (error) {
            console.error("Error fetching recipes:", error);
        }
    }

    // Initial fetch for page 1
    fetchRecipes();

    // Event listener for the search input
    searchInput.addEventListener('input', function () {
        searchTerm = searchInput.value.trim();
        fetchRecipes(1, searchTerm);  // Fetch results based on search term
    });
});

    </script>

<script src="script.js" defer></script>
</body>
</html>

