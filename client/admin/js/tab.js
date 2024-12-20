document.addEventListener("DOMContentLoaded", function () {
  const tabButtons = document.querySelectorAll(".tab-button");
  const tabContents = document.querySelectorAll(".tab-content");
  const searchBox = document.getElementById("search-box");
  const yearSortBtn = document.getElementById("sort-year");
  const nameSortBtn = document.getElementById("sort-name");

  let currentPage = 1;
  let currentSortField = "exam_year";
  let currentSortOrder = "desc";
  let searchQuery = "";
  let currentTab = "o/l";

  // Tab switching
  tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
      tabButtons.forEach((btn) => btn.classList.remove("active"));
      tabContents.forEach((content) => content.classList.remove("active"));

      this.classList.add("active");
      const tabId = this.getAttribute("data-tab");
      document.getElementById(tabId).classList.add("active");

      currentTab = tabId === "olResultsTab" ? "o/l" : "a/l";
      currentPage = 1; // Reset to first page when switching tabs
      fetchResults();
    });
  });

  // Search functionality
  let searchTimeout;
  searchBox.addEventListener("input", function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      searchQuery = this.value;
      currentPage = 1; // Reset to first page when searching
      fetchResults();
    }, 500);
  });

  // Sort button click handlers
  yearSortBtn.addEventListener("click", () => handleSortClick("exam_year"));
  nameSortBtn.addEventListener("click", () => handleSortClick("student_name"));

  function handleSortClick(field) {
    if (currentSortField === field) {
      currentSortOrder = currentSortOrder === "asc" ? "desc" : "asc";
    } else {
      currentSortField = field;
      currentSortOrder = "asc"; // Reset to ascending for new sort field
    }
    updateSortButtons();
    fetchResults();
  }

  // Update sort button appearance
  function updateSortButtons() {
    // Reset all buttons
    [yearSortBtn, nameSortBtn].forEach((btn) => {
      btn.querySelector(".up-arrow").style.display = "none";
      btn.querySelector(".down-arrow").style.display = "none";
      btn.classList.remove("active");
    });

    // Update active button
    const activeBtn =
      currentSortField === "exam_year" ? yearSortBtn : nameSortBtn;
    activeBtn.classList.add("active");

    if (currentSortOrder === "asc") {
      activeBtn.querySelector(".up-arrow").style.display = "inline";
      activeBtn.querySelector(".down-arrow").style.display = "none";
    } else {
      activeBtn.querySelector(".up-arrow").style.display = "none";
      activeBtn.querySelector(".down-arrow").style.display = "inline";
    }
  }

  function createPagination(totalPages) {
    const paginationContainer = document.querySelector(".pagination-controls");
    let paginationHTML = "";

    // Previous button
    paginationHTML += ` 
            <button class="pagination-button" ${
              currentPage === 1 ? "disabled" : ""
            } 
                    onclick="changePage(${currentPage - 1})">Previous</button>
        `;

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      paginationHTML += ` 
                <button class="pagination-button ${
                  currentPage === i ? "active" : ""
                }" 
                        onclick="changePage(${i})">${i}</button>
            `;
    }

    // Next button
    paginationHTML += ` 
            <button class="pagination-button" ${
              currentPage === totalPages ? "disabled" : ""
            } 
                    onclick="changePage(${currentPage + 1})">Next</button>
        `;

    // Page info
    paginationHTML += `<span class="pagination-info">Page ${currentPage} of ${totalPages}</span>`;

    paginationContainer.innerHTML = paginationHTML;
  }

  // Expose changePage function to global scope for pagination buttons
  window.changePage = function (page) {
    if (page >= 1) {
      currentPage = page;
      fetchResults();
    }
  };

  function fetchResults() {
    const resultsTable = document.getElementById(
      currentTab === "o/l" ? "olResultsTable" : "alResultsTable"
    );
    resultsTable.innerHTML = "<p>Loading...</p>";

    const url = new URL(
      "http://localhost/voting_system/server/controller/results/ol_result_get.php"
    );
    url.searchParams.append("year", currentTab);
    url.searchParams.append("page", currentPage);
    url.searchParams.append("sort_field", currentSortField);
    url.searchParams.append("sort_order", currentSortOrder);
    if (searchQuery) {
      url.searchParams.append("search", searchQuery);
    }

    fetch(url)
      .then((response) => response.json())
      .then((responseData) => {
        console.log(responseData);
        if (
          responseData &&
          responseData.data &&
          Object.keys(responseData.data).length > 0
        ) {
          let tableHTML = `
                        <table>
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Index No</th>
                                    <th>Student Name</th>
                                    <th>
                                        <span>Year</span>
                                        <span class="sort-icon">
                                            <i class="up-arrow">▲</i>
                                            <i class="down-arrow">▼</i>
                                        </span>
                                    </th>
                                    <th>NIC</th>
                                    <th>Download Results</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;

          responseData.data.forEach((student, index) => {
            const startingNumber = (currentPage - 1) * 10 + index + 1;
            tableHTML += `
                            <tr>
                                <td>${startingNumber}</td>
                                <td>${student.index_no}</td>
                                <td>${student.student_name}</td>
                                <td>${student.year}</td>
                                <td>${student.nic}</td>
                                <td>
                                    <a href="http://localhost/voting_system/server/controller/results/generate_pdf.php?student_id=${student.student_id}" 
                                       target="_blank">
                                        ${student.student_name}_${student.year}.pdf
                                    </a>
                                </td>
                            </tr>
                        `;
          });

          tableHTML += "</tbody></table>";
          resultsTable.innerHTML = tableHTML;

          // Create pagination
          createPagination(responseData.pagination.total_pages);
        } else {
          resultsTable.innerHTML = "<p>No results available.</p>";
        }
      })
      .catch((error) => {
        console.error("Error fetching results:", error);
        resultsTable.innerHTML = "<p>Error fetching results.</p>";
      });
  }

  // Initial load
  document.querySelector(".tab-button").click();
});
