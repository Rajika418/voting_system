import "https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js";

const routes = {
  "#/": {
    title: "Dashboard",
    js: ["dashboard.js"],
    css: "dashboard.css",
    template: "dashboard.html",
  },
  "#/teachers": {
    title: "Teacher List",
    js: ["teacherlist.js", "classAssignPopup.js"],
    css: "teacherlist.css",
    template: "teacherlist.html",
  },
  "#/students": {
    title: "Student List",
    js: "studentlist.js",
    css: "studentlist.css",
    template: "studentlist.html",
  },
  "#/results": {
    title: "Results",
    js: [
      // "results.js",
      "tab.js",
      "popup.js",
      "admission_popup.js",
      "add_result.js",
    ],
    css: "result.css",
    template: "result.html",
  },
  "#/elections": {
    title: "Elections",
    js: ["election.js"],
    css: "election.css",
    template: "election.html",
  },
};

function loadRoute(route) {
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
  fetch(`templates/${template}`)
    .then((response) => response.text())
    .then((html) => {
      app.innerHTML = html;

      // Check if js is an array and load associated JS files
      const jsFiles = Array.isArray(js) ? js : [js]; // Ensure js is always an array

      const jsPromises = jsFiles.map((jsFile) => import(`./pages/${jsFile}`));

      Promise.all(jsPromises)
        .then((modules) => {
          modules.forEach((module) => {
            if (module.render) {
              module.render();
            }
            if (module.init) {
              module.init();
            }
          });
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
  loadRoute(window.location.hash || "#/");
});

// Handle route changes
window.addEventListener("hashchange", () => {
  loadRoute(window.location.hash);
});

// Handle sidebar navigation
document.addEventListener("DOMContentLoaded", () => {
  document.querySelector(".sidebar").addEventListener("click", (event) => {
    if (event.target.tagName === "A") {
      event.preventDefault();
      const route = event.target.getAttribute("href");

      window.location.hash = route;
    }
  });
});

export { loadRoute };
