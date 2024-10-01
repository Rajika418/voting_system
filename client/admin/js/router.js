const routes = {
  "#/": {
    title: "Dashboard",
    js: "dashboard.js",
    css: "dashboard.css",
    template: "dashboard.html",
  },
  "#/teachers": {
    title: "Teacher List",
    js: "teacherList.js",
    css: "teacherList.css",
    template: "teacherList.html",
  },
  "#/students": {
    title: "Student List",
    js: "studentList.js",
    css: "studentList.css",
    template: "studentList.html",
  },
  "#/results": {
    title: "Results",
    js: "results.js",
    css: "results.css",
    template: "results.html",
  },
  "#/elections": {
    title: "Elections",
    js: "elections.js",
    css: "elections.css",
    template: "elections.html",
  },
};

function loadRoute(route) {
  console.log("Loading route:", route); // Debug log
  const app = document.getElementById("app");
  const { title, js, css, template } = routes[route] || routes["#/"];

  // Update document title
  document.title = `${title} - Voting System`;

  // Load the CSS
  const existingCSS = document.querySelector("link[data-dynamic-css]");
  if (existingCSS) {
    existingCSS.remove();
  }

  const linkElement = document.createElement("link");
  linkElement.rel = "stylesheet";
  linkElement.href = `styles/${css}`;
  linkElement.setAttribute("data-dynamic-css", "");
  document.head.appendChild(linkElement);

  // Load the HTML template
  fetch(`../templates/${template}`)
    .then((response) => response.text())
    .then((html) => {
      app.innerHTML = html;

      // Load the associated JS file
      import(`./pages/${js}`)
        .then((module) => {
          if (module.render) {
            module.render();
          }
          if (module.init) {
            module.init();
          }
        })
        .catch((error) => {
          console.error("Error loading module:", error);
          app.innerHTML = "<p>Error loading content</p>";
        });
    })
    .catch((error) => {
      console.error("Error loading template:", error);
      app.innerHTML = "<p>Error loading page template</p>";
    });
}

// Initial load
window.addEventListener("DOMContentLoaded", () => {
  console.log("DOMContentLoaded"); // Debug log
  loadRoute(window.location.hash || "#/");
});

// Handle route changes
window.addEventListener("hashchange", () => {
  console.log("Hash changed:", window.location.hash); // Debug log
  loadRoute(window.location.hash);
});

// Handle sidebar navigation
document.addEventListener("DOMContentLoaded", () => {
  console.log("Setting up sidebar navigation"); // Debug log
  document.querySelector(".sidebar").addEventListener("click", (event) => {
    if (event.target.tagName === "A") {
      event.preventDefault();
      const route = event.target.getAttribute("href");
      console.log("Clicked route:", route); // Debug log
      window.location.hash = route;
    }
  });
});

export { loadRoute };
