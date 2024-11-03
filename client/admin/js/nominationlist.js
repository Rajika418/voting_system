// Get election ID from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const electionId = urlParams.get("election_id");

let nominations = [];
let displayedNominations = [];
let currentPage = 1;
const itemsPerPage = 10;

// Debug log for election ID
console.log("Election ID:", electionId);

// Verify DOM elements are loaded
document.addEventListener("DOMContentLoaded", function () {
  console.log("DOM Loaded");
  fetch(
    `http://localhost/voting_system/server/controller/election/nomination/nomination_get.php?election_id=${electionId}`
  )
    .then((response) => {
      console.log("API Response status:", response.status);
      return response.json();
    })
    .then((data) => {
      console.log("API Response data:", data);

      if (data.status === "success" && data.data.length > 0) {
        nominations = data.data;
        displayedNominations = [...nominations];

        const electionInfo = nominations[0];

        console.log("Election Info:", electionInfo);
        document.getElementById("electionName").textContent =
          electionInfo.election_name;
        document.getElementById(
          "electionYear"
        ).textContent = `Election Year: ${electionInfo.year}`;
        document.title = `${electionInfo.election_name} - Nomination List`;

        displayNominations();
        updatePageInfo();
        updatePaginationButtons();
      } else {
        console.log("No nominations found or empty data");
        document.getElementById("electionName").textContent =
          "No nominations found";
        document.getElementById("electionYear").textContent = "";
      }
    })
    .catch((error) => {
      console.error("Error fetching nominations:", error);
      document.getElementById("electionName").textContent =
        "Error loading election details";
      document.getElementById("electionYear").textContent = "";
    });
});

// Toast message function
function showToast(message, type = "success") {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("show");
  }, 10);

  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function displayNominations() {
  const tableBody = document.getElementById("nominationBody");
  tableBody.innerHTML = "";

  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const pageNominations = displayedNominations.slice(start, end);

  pageNominations.forEach((nomination, index) => {
    const row = document.createElement("tr");
    const rowNumber = start + index + 1;
    row.innerHTML = `
            <td>${rowNumber}</td>
            <td>${nomination.student_name}</td>
            <td>${nomination.grade_name}</td>
            <td>${nomination.why}</td>
            <td>${nomination.motive}</td>
            <td>${nomination.what}</td>
            <td>
                <button onclick="selectNomination(${nomination.id})">Select</button>
                <button onclick="rejectNomination(${nomination.id})">Reject</button>
            </td>
        `;
    tableBody.appendChild(row);
  });
}

// Pagination controls
function prevPage() {
  if (currentPage > 1) {
    currentPage--;
    displayNominations();
    updatePageInfo();
    updatePaginationButtons();
  }
}

function nextPage() {
  if (currentPage * itemsPerPage < displayedNominations.length) {
    currentPage++;
    displayNominations();
    updatePageInfo();
    updatePaginationButtons();
  }
}

function updatePageInfo() {
  const totalPages = Math.ceil(displayedNominations.length / itemsPerPage);
  document.getElementById(
    "pageInfo"
  ).textContent = `Page ${currentPage} of ${totalPages}`;
}

function updatePaginationButtons() {
  document.querySelector(".pagination button:first-child").disabled =
    currentPage === 1;
  document.querySelector(".pagination button:last-child").disabled =
    currentPage * itemsPerPage >= displayedNominations.length;
}

// Filter and sort functions
function filterTable() {
  const query = document.getElementById("search").value.toLowerCase();
  displayedNominations = nominations.filter(
    (nomination) =>
      nomination.student_name.toLowerCase().includes(query) ||
      nomination.grade_name.toLowerCase().includes(query)
  );
  currentPage = 1;
  displayNominations();
  updatePageInfo();
  updatePaginationButtons();
}

function sortTable() {
  const sortBy = document.getElementById("sort").value;
  displayedNominations.sort((a, b) => {
    if (sortBy === "name") return a.student_name.localeCompare(b.student_name);
    if (sortBy === "year") return a.grade_name.localeCompare(b.grade_name);
  });
  displayNominations();
}

// Select Nomination
function selectNomination(nominationId) {
  console.log("Nomination ID to select:", nominationId); // Debug log
  fetch(
    "http://localhost/voting_system/server/controller/election/candidate/candidate_post.php",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ nomination_id: nominationId }),
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Response data:", data); // Debug log
      if (data.status === "success") {
        showToast("Nomination selected as candidate!", "success");
        nominations = nominations.filter((n) => n.id !== nominationId);
        displayedNominations = [...nominations];
        displayNominations();
        updatePageInfo();
        updatePaginationButtons();
      } else {
        showToast("Error: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error selecting nomination:", error);
      showToast("An error occurred while selecting.", "error");
    });
}

// Reject Nomination
function rejectNomination(nominationId) {
  fetch(
    `http://localhost/voting_system/server/controller/election/nomination/nomination_delete.php?id=${nominationId}`,
    {
      method: "DELETE",
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showToast("Nomination rejected successfully.", "success");
        nominations = nominations.filter((n) => n.id !== nominationId);
        displayedNominations = [...nominations];
        displayNominations();
        updatePageInfo();
        updatePaginationButtons();
      } else {
        showToast("Error: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error rejecting nomination:", error);
      showToast("An error occurred while rejecting.", "error");
    });
}
