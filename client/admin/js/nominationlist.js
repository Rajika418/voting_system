
// Get election ID from URL parameters
const urlParams = new URLSearchParams(window.location.search);
const electionId = urlParams.get('election_id');

let nominations = [];
let currentPage = 1;
const itemsPerPage = 10;

// Debug log for election ID
console.log('Election ID:', electionId);

// Verify DOM elements are loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Loaded');
    fetch(`http://localhost/voting_system/server/controller/election/nomination/nomination_get.php?election_id=${electionId}`)
        .then(response => {
            console.log('API Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API Response data:', data);

            if (data.status === "success" && data.data.length > 0) {
                nominations = data.data;

                // Get election details from the first nomination
                const electionInfo = nominations[0]; // Use the first object in the data array
                
                console.log('Election Info:', electionInfo);
                console.log('Election Name:', electionInfo.election_name);
                console.log('Election Year:', electionInfo.year);

                const electionNameElement = document.getElementById('electionName');
                const electionYearElement = document.getElementById('electionYear');

                if (electionNameElement && electionYearElement) {
                    // Set the text content of the header elements
                    electionNameElement.textContent = electionInfo.election_name;
                    electionYearElement.textContent = `Election Year: ${electionInfo.year}`;
                } else {
                    console.error('Election elements not found in DOM');
                }

                document.title = `${electionInfo.election_name} - Nomination List`;
                
                displayNominations();
                updatePageInfo();
            } else {
                console.log('No nominations found or empty data');
                document.getElementById('electionName').textContent = 'No nominations found';
                document.getElementById('electionYear').textContent = '';
            }
        })
        .catch(error => {
            console.error('Error fetching nominations:', error);
            document.getElementById('electionName').textContent = 'Error loading election details';
            document.getElementById('electionYear').textContent = '';
        });
});

function displayNominations() {
    const tableBody = document.getElementById('nominationBody');
    tableBody.innerHTML = '';

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const pageNominations = nominations.slice(start, end);

    pageNominations.forEach((nomination, index) => {
        const row = document.createElement('tr');
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
    const totalPages = Math.ceil(nominations.length / itemsPerPage);
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
}

function selectNomination(nominationId) {
    fetch('http://localhost/voting_system/server/controller/election/candidate/candidate_post.php?', {
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
            updatePageInfo();
        } else {
            alert("Error: " + data.message);
        }
    });
}

function rejectNomination(nominationId) {
    nominations = nominations.filter(n => n.id !== nominationId);
    displayNominations();
    updatePageInfo();
}

function filterTable() {
    const query = document.getElementById('search').value.toLowerCase();
    const filteredNominations = nominations.filter(nomination =>
        nomination.student_name.toLowerCase().includes(query) ||
        nomination.grade_name.toLowerCase().includes(query)
    );
    currentPage = 1;
    nominations = filteredNominations;
    displayNominations();
    updatePageInfo();
}

function sortTable() {
    const sortBy = document.getElementById('sort').value;
    nominations.sort((a, b) => {
        if (sortBy === 'name') return a.student_name.localeCompare(b.student_name);
        if (sortBy === 'year') return a.grade_name.localeCompare(b.grade_name);
    });
    displayNominations();
}
