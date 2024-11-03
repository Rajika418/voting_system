let currentYear = "";
let currentExam = "o/l";
let debugMode = false;
let currentPage = 1;
let currentSearch = "";
let searchTimeout = null;

// Function to handle search
function handleSearch() {
  const searchInput = document.getElementById("studentSearch");
  const searchValue = searchInput.value.trim();

  // Update current search value
  currentSearch = searchValue;

  // Reset to first page when searching
  currentPage = 1;

  // Refresh results with search
  showResults(currentExam);
}

// Setup search listener with debouncing
function setupSearchListener() {
  const searchInput = document.getElementById("studentSearch");

  searchInput.addEventListener("input", () => {
    // Clear existing timeout
    if (searchTimeout) {
      clearTimeout(searchTimeout);
    }

    // Set new timeout
    searchTimeout = setTimeout(() => {
      handleSearch();
    }, 300); // Reduced to 300ms for faster response
  });
}

function debugLog(message, data) {
  if (debugMode) {
    console.log(message, data);
    const debugDiv = document.getElementById("debug");
    if (debugDiv) {
      debugDiv.innerHTML += `<p>${message}: ${JSON.stringify(data)}</p>`;
    }
  }
}

async function fetchYears() {
  try {
    debugLog("Fetching years", "Started");
    const response = await fetch(
      "http://localhost/voting_system/server/controller/results/get_examyear.php"
    );
    const data = await response.json();
    debugLog("Years data received", data);

    if (data.status === "success") {
      const yearsContainer = document.getElementById("yearsContainer");
      if (!yearsContainer) return;

      yearsContainer.innerHTML = "";

      const sortedYears = [...data.years].sort((a, b) => b.year - a.year);
      const latestYear = sortedYears[0].year;

      data.years.forEach((yearObj) => {
        const btn = document.createElement("button");
        btn.className = "year-btn";
        btn.textContent = yearObj.year;
        btn.onclick = () => selectYear(yearObj.year);
        yearsContainer.appendChild(btn);
      });

      selectYear(latestYear);
    }
  } catch (error) {
    debugLog("Error fetching years", error.message);
    console.error("Error fetching years:", error);
  }
}

function selectYear(year) {
  debugLog("Year selected", year);
  currentYear = year;
  currentPage = 1;

  const resultsContainer = document.getElementById("resultsContainer");
  if (resultsContainer) {
    resultsContainer.classList.add("active");
  }

  document.querySelectorAll(".year-btn").forEach((btn) => {
    btn.style.backgroundColor =
      btn.textContent === year.toString() ? "#0056b3" : "#007bff";
  });

  showResults(currentExam);
}

async function showResults(examType) {
  if (currentYear === "") return;
  debugLog("Showing results for", {
    examType,
    currentYear,
    search: currentSearch,
  });
  currentExam = examType;

  document.querySelectorAll(".tab-btn").forEach((btn) => {
    btn.classList.toggle(
      "active",
      btn.textContent.includes(examType.toUpperCase())
    );
  });

  try {
    // Update URL to include search parameter
    const url = `http://localhost/voting_system/server/controller/results/ol_result_get.php?year=${examType}&page=${currentPage}&search=${encodeURIComponent(
      currentSearch
    )}`;
    debugLog("Fetching results from URL", url);

    const response = await fetch(url);
    const data = await response.json();
    debugLog("Results data received", data);

    const filteredResults = data.data.filter(
      (result) => result.year === currentYear
    );
    const resultsTable = document.getElementById("resultsTable");
    if (!resultsTable) return;

    // Show loading state
    resultsTable.innerHTML =
      filteredResults.length === 0
        ? `<div class="no-results">No results found${
            currentSearch ? ` for "${currentSearch}"` : ""
          }</div>`
        : generateTableHTML(filteredResults);

    // Update pagination if available
    if (data.pagination && data.pagination.total_pages) {
      createPagination(data.pagination.total_pages);
    }
  } catch (error) {
    debugLog("Error fetching results", error.message);
    console.error("Error fetching results:", error);
    const resultsTable = document.getElementById("resultsTable");
    if (resultsTable) {
      resultsTable.innerHTML = `
              <div style="color: red; padding: 20px;">
                  Error loading results. Please try again later.
              </div>
          `;
    }
  }
}

// Helper function to generate table HTML
function generateTableHTML(results) {
  return `
      <table class="results-table">
          <thead>
              <tr>
                  <th>No</th>
                  <th>Index No</th>
                  <th>Student Name</th>
                  <th>NIC</th>
                  <th>Download Results</th>
              </tr>
          </thead>
          <tbody>
              ${results
                .map(
                  (result, index) => `
                  <tr>
                      <td>${(currentPage - 1) * 10 + index + 1}</td>
                      <td>${result.index_no}</td>
                      <td>${result.student_name}</td>
                      <td>${result.nic}</td>
                      <td>
                          <a href="http://localhost/voting_system/server/controller/results/generate_pdf.php?student_id=${
                            result.student_id
                          }"
                              target="_blank">
                              ${result.student_name}_${result.year}.pdf
                          </a>
                      </td>
                  </tr>
              `
                )
                .join("")}
          </tbody>
      </table>
  `;
}

function createPagination(totalPages) {
  const paginationContainer = document.querySelector(".pagination");
  if (!paginationContainer) return;

  let paginationHTML = `<div class="pagination-wrapper">`;

  // Previous button
  paginationHTML += `
        <button 
            class="pagination-btn ${currentPage === 1 ? "disabled" : ""}"
            onclick="changePage(${currentPage - 1})" 
            ${currentPage === 1 ? "disabled" : ""}>
            Previous
        </button>
    `;

  // Page numbers
  for (let i = 1; i <= totalPages; i++) {
    paginationHTML += `
            <button 
                class="pagination-btn ${currentPage === i ? "active" : ""}"
                onclick="changePage(${i})">
                ${i}
            </button>
        `;
  }

  // Next button
  paginationHTML += `
        <button 
            class="pagination-btn ${
              currentPage === totalPages ? "disabled" : ""
            }"
            onclick="changePage(${currentPage + 1})" 
            ${currentPage === totalPages ? "disabled" : ""}>
            Next
        </button>
    </div>`;

  paginationContainer.innerHTML = paginationHTML;
}

function changePage(newPage) {
  currentPage = newPage;
  showResults(currentExam);
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  fetchYears();
  setupSearchListener();
});
