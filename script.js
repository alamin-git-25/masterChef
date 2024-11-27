// JavaScript for toggle functionality
document.addEventListener("DOMContentLoaded", () => {
    const menuButton = document.getElementById("menuButton");
    const closeButton = document.getElementById("closeButton");
    const header = document.getElementById("header");

    menuButton.addEventListener("click", () => {
        header.classList.remove("top-[-100vh]");
        header.classList.add("top-0");
        closeButton.classList.remove("hidden");
    });

    closeButton.addEventListener("click", () => {
        header.classList.remove("top-0");
        header.classList.add("top-[-100vh]");
        closeButton.classList.add("hidden");
    });

    // Close menu when a link is clicked
    document.querySelectorAll("header nav ul li a").forEach((link) => {
        link.addEventListener("click", () => {
            header.classList.remove("top-0");
            header.classList.add("top-[-100vh]");
            closeButton.classList.add("hidden");
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    // Check if the page URL has a query parameter for pagination
    if (window.location.search.includes("page=")) {
        // Scroll to the section containing recipes
        const section = document.getElementById("recipes-section");
        if (section) {
            section.scrollIntoView({ behavior: "smooth" });
        }
    }
});