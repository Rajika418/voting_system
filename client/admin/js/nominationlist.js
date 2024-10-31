let nominations = [];
let currentPage = 1;
const itemsPerPage = 10;

// Fetch data
fetch('http://localhost/voting_system/server/controller/election/nomination/nomination_get.php?election_id=4')
  .then(response => response.json())
  .then(data => {
    if (data.status === "success") {
      nominations = data.data;
      displayNominations();
      updatePageInfo();
    }
  });

// Display nominations
function displayNominations() {
  const tableBody = document.getElementById('nominationBody');
  tableBody.innerHTML = '';

  const start = (currentPage - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  const pageNominations = nominations.slice(start, end);

  pageNominations.forEach(nomination => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${nomination.id}</td>
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
  }
}

function nextPage() {
  if (currentPage * itemsPerPage < nominations.length) {
    currentPage++;
    displayNominations();
    updatePageInfo();
  }
}

function updatePageInfo() {
  document.getElementById('pageInfo').textContent = `Page ${currentPage}`;
}

// Select nomination
function selectNomination(nominationId) {
  fetch('http://localhost/voting_system/server/controller/election/nomination/add_candidate.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ nomination_id: nominationId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status === "success") {
      alert("Nomination selected as candidate!");
      nominations = nominations.filter(n => n.id !== nominationId);
      displayNominations();
    } else {
      alert("Error: " + data.message);
    }
  });
}

// Reject nomination
function rejectNomination(nominationId) {
  nominations = nominations.filter(n => n.id !== nominationId);
  displayNominations();
}

// Search filter
function filterTable() {
  const query = document.getElementById('search').value.toLowerCase();
  nominations = nominations.filter(nomination => 
    nomination.student_name.toLowerCase().includes(query) ||
    nomination.grade_name.toLowerCase().includes(query)
  );
  displayNominations();
}

// Sort table
function sortTable() {
  const sortBy = document.getElementById('sort').value;
  nominations.sort((a, b) => {
    if (sortBy === 'name') return a.student_name.localeCompare(b.student_name);
    if (sortBy === 'year') return a.grade_name.localeCompare(b.grade_name);
  });
  displayNominations();
}
