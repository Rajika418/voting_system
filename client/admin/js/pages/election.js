
    const electionBody = document.getElementById('electionBody');
    const sortButton = document.getElementById('sortButton');
    const searchInput = document.getElementById('searchInput');
    const prevPageButton = document.getElementById('prevPage');
    const nextPageButton = document.getElementById('nextPage');
    const pageInfo = document.getElementById('pageInfo');

    let currentPage = 1;
    let totalPages = 1;
    let sortOrder = 'DESC';
    let yearFilter = null;

    console.log("election");
    
    // Function to fetch elections
    async function fetchElections() {
        const url = new URL('http://localhost/voting_system/server/controller/election/election_get.php');
        url.searchParams.append('page', currentPage);
        url.searchParams.append('limit', 10);
        url.searchParams.append('sortOrder', sortOrder);
        if (yearFilter) {
            url.searchParams.append('year', yearFilter);
        }

        const response = await fetch(url);
        const data = await response.json();

        if (data.status === 'success') {
            renderElections(data.data);
            totalPages = data.totalPages;
            pageInfo.innerText = `Page ${currentPage} of ${totalPages}`;
            prevPageButton.disabled = currentPage === 1;
            nextPageButton.disabled = currentPage === totalPages;
        }
    }

    // Function to render election data in the table
    function renderElections(elections) {
        electionBody.innerHTML = '';
        elections.forEach((election, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${(currentPage - 1) * 10 + index + 1}</td>
                <td>${election.election_name}</td>
                <td>${election.year}</td>
                <td>${new Date(election.ele_start_date).toLocaleDateString()}</td>
                <td>${new Date(election.ele_end_date).toLocaleDateString()}</td>
                <td>
                    <a href="#/elections/nominations/${election.id}">Nominations</a> | 
                    <a href="#/elections/candidates/${election.id}">Candidates</a>
                </td>
                <td>
                    <a href="#" onclick="editElection(${election.id})">Edit</a> | 
                    <a href="#" onclick="deleteElection(${election.id})">Delete</a>
                </td>
            `;
            electionBody.appendChild(row);
        });
    }

    // Function to handle sorting
    sortButton.addEventListener('click', () => {
        // Toggle sort order
        sortOrder = (sortOrder === 'DESC') ? 'ASC' : 'DESC';
        sortArrow.innerText = (sortOrder === 'ASC') ? '⬆️' : '⬇️'; // Update arrow direction
        fetchElections();
    });

    // Function to handle searching
    searchInput.addEventListener('input', () => {
        yearFilter = searchInput.value ? parseInt(searchInput.value) : null;
        fetchElections();
    });
    // Pagination controls
    prevPageButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchElections();
        }
    });

    nextPageButton.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            fetchElections();
        }
    });

    // Initial fetch
    fetchElections();


// Placeholder functions for editing and deleting elections
function editElection(id) {
    alert('Edit election with ID: ' + id);
}

function deleteElection(id) {
    alert('Delete election with ID: ' + id);
}


export function init() {
    fetchElections();
  }