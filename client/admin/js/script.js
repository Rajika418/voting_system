document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(".nav-link");

  // Get the current page from the URL
  const currentPage =
    new URLSearchParams(window.location.search).get("page") || "dashboard";

  // Loop through the navigation links
  navLinks.forEach((link) => {
    // Check if the link's href matches the current page
    if (link.getAttribute("href") === `?page=${currentPage}`) {
      link.classList.add("active"); // Add the active class
    } else {
      link.classList.remove("active"); // Remove the active class
    }
  });
});

// Get the necessary DOM elements
const toggleButton = document.getElementById("toggleSidebar");
const sidebar = document.getElementById("sidebar");
const mainContent = document.getElementById("mainContent");
const footer = document.getElementById("footer");

// Add click event listener to toggle button
toggleButton.addEventListener("click", () => {
  // Toggle the 'collapsed' class on sidebar
  sidebar.classList.toggle("collapsed");

  // Toggle 'expanded' class on main content and footer
  mainContent.classList.toggle("expanded");
  footer.classList.toggle("expanded");

  // Toggle button icon between bars and times
  const icon = toggleButton.querySelector("i");
  if (icon.classList.contains("fa-bars")) {
    icon.classList.remove("fa-bars");
    icon.classList.add("fa-times");
  } else {
    icon.classList.remove("fa-times");
    icon.classList.add("fa-bars");
  }
});

// Add resize event listener to handle responsive behavior
window.addEventListener("resize", () => {
  if (window.innerWidth <= 768) {
    // Mobile breakpoint
    sidebar.classList.add("collapsed");
    mainContent.classList.add("expanded");
    footer.classList.add("expanded");
  } else {
    sidebar.classList.remove("collapsed");
    mainContent.classList.remove("expanded");
    footer.classList.remove("expanded");
  }
});
