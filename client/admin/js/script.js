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
