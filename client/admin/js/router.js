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
    js: "election.js",
    css: "election.css",
    template: "election.html",
  },
  "#/elections/nominations/:id": {
    title: "Elections",
    js: "nomination.js",
    css: "nomination.css",
    template: "nomination.html",
  },
  "#/elections/candidates/:id": {
    title: "Elections",
    js: "candidate.js",
    css: "candidate.css",
    template: "candidate.html",
  },
};

function matchRoute(hash) {
  const routes = Object.keys(window.routes || routes);
  for (const route of routes) {
    const paramNames = [];
    const regexPattern = route.replace(/:([^\s/]+)/g, (match, paramName) => {
      paramNames.push(paramName);
      return "([^\s/]+)";
    });
    const regex = new RegExp(`^${regexPattern}$`);
    const match = hash.match(regex);
    if (match) {
      const params = {};
      paramNames.forEach((name, index) => {
        params[name] = match[index + 1];
      });
      return { route, params };
    }
  }
  console.log("No matching route found");
  return null;
}

function loadRoute(hash) {
  const app = document.getElementById("app");
  if (!app) {
    console.error("App element not found");
    return;
  }

  const matchedRoute = matchRoute(hash);
  
  if (!matchedRoute) {
    console.error("Route not found");
    app.innerHTML = "<p>404 - Page not found</p>";
    return;
  }

  const { route, params } = matchedRoute;
  const routeConfig = window.routes?.[route] || routes[route];

  if (!routeConfig) {
    console.error("Route configuration not found");
    app.innerHTML = "<p>Error: Route configuration not found</p>";
    return;
  }

  const { title, js, css, template } = routeConfig;

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
    .then((response) => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then((html) => {
      app.innerHTML = html;

      // Check if js is an array and load associated JS files
      const jsFiles = Array.isArray(js) ? js : [js]; // Ensure js is always an array

      const jsPromises = jsFiles.map((jsFile) => {
        return import(`./pages/${jsFile}`).catch(e => {
          console.error(`Error loading ${jsFile}:`, e);
          throw e;
        });
      });

      Promise.all(jsPromises)
        .then((modules) => {
          modules.forEach((module) => {
            if (module.render) {
              module.render(params);
            }
            if (module.init) {            
              module.init(params);
            }
          });
        })
        .catch((error) => {
          console.error("Error loading or executing modules:", error);
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
  const sidebar = document.querySelector(".sidebar");
  if (sidebar) {
    sidebar.addEventListener("click", (event) => {
      if (event.target.tagName === "A") {
        event.preventDefault();
        const route = event.target.getAttribute("href");
        console.log("Sidebar navigation:", route);
        window.location.hash = route;
      }
    });
  } else {
    console.error("Sidebar element not found");
  }
});

window.routes = routes;
export { loadRoute };