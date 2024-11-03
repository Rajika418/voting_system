// State management
const state = {
  currentPage: 1,
  totalPages: 1,
  sortOrder: "DESC",
  yearFilter: null,
};

let electionBody,
  sortButton,
  searchInput,
  prevPageButton,
  nextPageButton,
  pageInfo,
  sortArrow;

function initializeElements() {
  electionBody = document.getElementById("electionBody");
  sortButton = document.getElementById("sortButton");
  searchInput = document.getElementById("searchInput");
  prevPageButton = document.getElementById("prevPage");
  nextPageButton = document.getElementById("nextPage");
  pageInfo = document.getElementById("pageInfo");
  sortArrow = document.getElementById("sortArrow");

  if (
    !electionBody ||
    !sortButton ||
    !searchInput ||
    !prevPageButton ||
    !nextPageButton ||
    !pageInfo ||
    !sortArrow
  ) {
    console.error("One or more required elements not found");
    return false;
  }
  return true;
}

async function fetchElections() {
  const url = new URL(
    "http://localhost/voting_system/server/controller/election/election_get.php"
  );
  url.searchParams.append("page", state.currentPage);
  url.searchParams.append("limit", 10);
  url.searchParams.append("sortOrder", state.sortOrder);
  if (state.yearFilter) {
    url.searchParams.append("year", state.yearFilter);
  }

  try {
    const response = await fetch(url);
    const data = await response.json();

    if (data.status === "success") {
      renderElections(data.data);
      state.totalPages = data.totalPages;
      updatePagination();
    } else {
      console.error("Failed to fetch elections:", data.message);
    }
  } catch (error) {
    console.error("Error fetching elections:", error);
  }
}

function renderElections(elections) {
  electionBody.innerHTML = "";
  elections.forEach((election, index) => {
    const row = document.createElement("tr");
    row.id = `election-${election.id}`;
    row.dataset.name = election.election_name;
    row.dataset.year = election.year;
    row.dataset.nomStart = election.nom_start_date;
    row.dataset.nomEnd = election.nom_end_date;
    row.dataset.eleStart = election.ele_start_date;
    row.dataset.eleEnd = election.ele_end_date;

    row.innerHTML = `
            <td>${(state.currentPage - 1) * 10 + index + 1}</td>
            <td>${election.election_name}</td>
            <td>${election.year}</td>
            <td>${new Date(election.nom_start_date).toLocaleDateString()}</td>
            <td>${new Date(election.nom_end_date).toLocaleDateString()}</td>
            <td>${new Date(election.ele_start_date).toLocaleDateString()}</td>
            <td>${new Date(election.ele_end_date).toLocaleDateString()}</td>
            <td>
                <a href="#/elections/nominations/${
                  election.id
                }">Nominations</a> |
                <a href="#/elections/candidates/${election.id}">Candidates</a>
            </td>
            <td>
                <button class="editbtn" data-id="${election.id}">Edit</button>
                <button class="deletebtn" data-id="${
                  election.id
                }">Delete</button>
            </td>
        `;
    electionBody.appendChild(row);
  });
}

function updatePagination() {
  pageInfo.innerText = `Page ${state.currentPage} of ${state.totalPages}`;
  prevPageButton.disabled = state.currentPage === 1;
  nextPageButton.disabled = state.currentPage === state.totalPages;
}

function updateSortArrow() {
  sortArrow.innerText = state.sortOrder === "ASC" ? "⬆️" : "⬇️";
}

function setupEventListeners() {
  sortButton.addEventListener("click", () => {
    state.sortOrder = state.sortOrder === "DESC" ? "ASC" : "DESC";
    updateSortArrow();
    fetchElections();
  });

  searchInput.addEventListener("input", () => {
    state.yearFilter = searchInput.value ? parseInt(searchInput.value) : null;
    state.currentPage = 1;
    fetchElections();
  });

  prevPageButton.addEventListener("click", () => {
    if (state.currentPage > 1) {
      state.currentPage--;
      fetchElections();
    }
  });

  nextPageButton.addEventListener("click", () => {
    if (state.currentPage < state.totalPages) {
      state.currentPage++;
      fetchElections();
    }
  });

  electionBody.addEventListener("click", function (event) {
    const target = event.target;
    if (target.classList.contains("editbtn")) {
      const electionId = target.getAttribute("data-id");
      editElection(electionId);
    }
    if (target.classList.contains("deletebtn")) {
      const electionId = target.getAttribute("data-id");
      deleteElection(electionId);
    }
  });

  document.getElementById("addElectionButton").addEventListener("click", () => {
    document.getElementById("addModal").style.display = "block";
  });

  document
    .getElementById("addElectionForm")
    .addEventListener("submit", addElection);
}

function editElection(id) {
  const electionRow = document.getElementById(`election-${id}`);
  if (electionRow) {
    document.getElementById("updateElectionName").value =
      electionRow.dataset.name;
    document.getElementById("updateYear").value = electionRow.dataset.year;
    document.getElementById("updateNomStart").value =
      electionRow.dataset.nomStart.split(" ")[0];
    document.getElementById("updateNomEnd").value =
      electionRow.dataset.nomEnd.split(" ")[0];
    document.getElementById("updateEleStart").value =
      electionRow.dataset.eleStart.split(" ")[0];
    document.getElementById("updateEleEnd").value =
      electionRow.dataset.eleEnd.split(" ")[0];

    document.getElementById("updateModal").style.display = "block";
    document.getElementById("updateForm").onsubmit = (event) =>
      updateElection(event, id);
  } else {
    showToast("Election data not found", "error");
  }
}

function updateElection(event, id) {
  event.preventDefault();
  const formData = new FormData(event.target);
  formData.append("_method", "PUT");

  fetch(
    `http://localhost/voting_system/server/controller/election/election_update.php/${id}`,
    {
      method: "POST",
      body: formData,
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showToast("Election updated successfully", "success");
        closeModal("updateModal");
        fetchElections();
      } else {
        showToast(data.message, "error");
      }
    })
    .catch((error) => {
      showToast("Error updating election", "error");
    });
}

function deleteElection(id) {
  if (confirm("Are you sure you want to delete this election?")) {
    fetch(
      `http://localhost/voting_system/server/controller/election/election_delete.php?id=${id}`,
      {
        method: "DELETE",
      }
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.status === "success") {
          showToast("Election deleted successfully", "success");
          fetchElections();
        } else {
          showToast(data.message, "error");
        }
      })
      .catch((error) => {
        showToast("Error deleting election", "error");
      });
  }
}

function showToast(message, type) {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerText = message;
  document.body.appendChild(toast);

  // Automatically remove the toast after 3 seconds
  setTimeout(() => {
    toast.remove();
  }, 3000);
}

function addElection(event) {
  event.preventDefault();
  const formData = new FormData(event.target);

  const imageInput = document.getElementById("electionImage");
  if (imageInput.files.length > 0) {
    formData.append("image", imageInput.files[0]);
  }

  fetch(
    "http://localhost/voting_system/server/controller/election/election_post.php?action=create",
    {
      method: "POST",
      body: formData,
    }
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success") {
        showToast("Election added successfully!", "success");
        closeModal("addModal");
        fetchElections();
      } else {
        showToast("Failed to add election: " + data.message, "error");
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      showToast("An error occurred while adding the election.", "error");
    });
}

function closeModal(modalId) {
  document.getElementById(modalId).style.display = "none";
}

function setupModalClosing() {
  const closeButtons = document.querySelectorAll(".close");
  closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const modalId = this.closest(".modal").id;
      closeModal(modalId);
    });
  });
}

function init() {
  if (initializeElements()) {
    setupEventListeners();
    setupModalClosing();
    fetchElections();
  }
}

document.addEventListener("DOMContentLoaded", init);
