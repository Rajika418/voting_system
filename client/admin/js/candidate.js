// JavaScript
let electionId = null;
let currentPage = 1;
let searchTerm = "";
let totalPages = 1;

function extractElectionId() {
  const url = new URL(window.location.href);
  const pageParam = url.searchParams.get("page");
  const match = pageParam ? pageParam.match(/candidates\/(\d+)/) : null;

  if (match && match[1]) {
    electionId = parseInt(match[1]);
    console.log("Extracted election ID:", electionId);
    return electionId;
  } else {
    console.error("Failed to extract election ID from URL");
    return null;
  }
}

async function fetchCandidateData() {
  if (!electionId) {
    console.error("Election ID is not set");
    return;
  }

  try {
    const response = await fetch(
      `http://localhost/voting_system/server/controller/election/candidate/candidate_get.php?id=${electionId}&page=${currentPage}&search=${encodeURIComponent(
        searchTerm
      )}&limit=10`
    );
    const data = await response.json();

    if (data.status === "success") {
      document.getElementById("electionName").textContent =
        data.data[0]?.election_name || "Election";
      document.getElementById("electionYear").textContent = `Year: ${
        data.data[0]?.year || ""
      }`;
      renderCandidateData(data);
      updatePagination(data);
    } else {
      console.error("Failed to fetch candidate data:", data.message);
    }
  } catch (error) {
    console.error("Error fetching candidate data:", error);
  }
}

function renderCandidateData(data) {
  const candidateInfo = document.getElementById("candidateInfo");
  if (!candidateInfo) {
    console.error("Candidate info element not found");
    return;
  }

  // Clear previous candidate information
  candidateInfo.innerHTML = "";

  console.log(data.data);

  if (data.data.length === 0) {
    candidateInfo.innerHTML = `
          <div class="no-results">
              <p>No candidates found matching your search criteria.</p>
          </div>
      `;
    return;
  }

  // Loop through the array of candidates and create HTML for each
  data.data.forEach((candidate, index) => {
    const rowNum = (currentPage - 1) * 10 + index + 1;
    candidateInfo.innerHTML += `
          <tr class="candidate">
              <td>${rowNum}</td>
              <td> ${candidate.student_name}</td>
              <td> ${candidate.total_votes || "0"}</td>
          </tr>
      `;
  });
}

function updatePagination(data) {
  totalPages = data.totalPages;
  const pageInfo = document.getElementById("pageInfo");
  const prevBtn = document.getElementById("prevPage");
  const nextBtn = document.getElementById("nextPage");

  pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
  prevBtn.disabled = currentPage === 1;
  nextBtn.disabled = currentPage === totalPages;
}

function changePage(delta) {
  const newPage = currentPage + delta;
  if (newPage >= 1 && newPage <= totalPages) {
    currentPage = newPage;
    fetchCandidateData();
  }
}

function initializeSearch() {
  const searchInput = document.getElementById("search");
  let debounceTimeout;

  searchInput.addEventListener("input", (e) => {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => {
      searchTerm = e.target.value;
      currentPage = 1; // Reset to first page when searching
      fetchCandidateData();
    }, 300);
  });
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  extractElectionId();
  initializeSearch();
  fetchCandidateData();
});
