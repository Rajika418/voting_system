function initRouter() {
  const navLinks = document.querySelectorAll("nav a");

  navLinks.forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const route = e.target.getAttribute("href");
      history.pushState(null, "", route);
      loadRoute(route);
    });
  });

  // Handle back/forward browser buttons
  window.addEventListener("popstate", () => {
    loadRoute(window.location.pathname);
  });

  // Load initial route
  loadRoute(window.location.pathname);
}

document.addEventListener("DOMContentLoaded", initRouter);
