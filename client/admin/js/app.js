import { loadRoute } from "./router.js";

function initRouter() {
  const navLinks = document.querySelectorAll("aside.sidebar a"); // Adjusted selector to match your sidebar

  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault(); // Prevent default anchor behavior
      const route = e.target.getAttribute("href");
      console.log("Navigating to route:", route); // Debug log
      window.location.hash = route; // Use hash to navigate
    });
  });

  // Handle back/forward browser buttons
  window.addEventListener("popstate", () => {
    console.log("Popstate event triggered."); // Debug log
    loadRoute(window.location.hash || "#/"); // Load the current route based on hash
  });

  // Load initial route on page load
  loadRoute(window.location.hash || "#/"); // Ensure it loads the initial route
}

document.addEventListener("DOMContentLoaded", initRouter); // Initialize router once DOM is loaded
