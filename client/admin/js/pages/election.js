let electionBody, sortButton, searchInput, prevPageButton, nextPageButton, pageInfo, sortArrow;
let currentPage = 1;
let totalPages = 1;
let sortOrder = 'DESC';
let yearFilter = null;

// State management
const state = {
    get currentPage() { return currentPage; },
    set currentPage(value) { currentPage = value; },
    get sortOrder() { return sortOrder; },
    set sortOrder(value) { sortOrder = value; },
    get yearFilter() { return yearFilter; },
    set yearFilter(value) { yearFilter = value; }
};

function initializeElements() {
    electionBody = document.getElementById('electionBody');
    sortButton = document.getElementById('sortButton');
    searchInput = document.getElementById('searchInput');
    prevPageButton = document.getElementById('prevPage');
    nextPageButton = document.getElementById('nextPage');
    pageInfo = document.getElementById('pageInfo');
    sortArrow = document.getElementById('sortArrow');

    if (!electionBody || !sortButton || !searchInput || !prevPageButton || !nextPageButton || !pageInfo || !sortArrow) {
        console.error('One or more required elements not found');
        return false;
    }
    return true;
}

async function fetchElections() {
    const url = new URL('http://localhost/voting_system/server/controller/election/election_get.php');
    url.searchParams.append('page', state.currentPage);
    url.searchParams.append('limit', 10);
    url.searchParams.append('sortOrder', state.sortOrder);
    if (state.yearFilter) {
        url.searchParams.append('year', state.yearFilter);
    }

    try {
        const response = await fetch(url);
        const data = await response.json();

        if (data.status === 'success') {
            renderElections(data.data);
            totalPages = data.totalPages;
            updatePagination();
        } else {
            console.error('Failed to fetch elections:', data.message);
        }
    } catch (error) {
        console.error('Error fetching elections:', error);
    }
}

function renderElections(elections) {
    electionBody.innerHTML = '';
    elections.forEach((election, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${(state.currentPage - 1) * 10 + index + 1}</td>
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

function updatePagination() {
    pageInfo.innerText = `Page ${state.currentPage} of ${totalPages}`;
    prevPageButton.disabled = state.currentPage === 1;
    nextPageButton.disabled = state.currentPage === totalPages;
}

function updateSortArrow() {
    sortArrow.innerText = (state.sortOrder === 'ASC') ? '⬆️' : '⬇️';
}

function setupEventListeners() {
    sortButton.addEventListener('click', () => {
        state.sortOrder = (state.sortOrder === 'DESC') ? 'ASC' : 'DESC';
        updateSortArrow();
        fetchElections();
    });

    searchInput.addEventListener('input', () => {
        state.yearFilter = searchInput.value ? parseInt(searchInput.value) : null;
        state.currentPage = 1; // Reset to first page when searching
        fetchElections();
    });

    prevPageButton.addEventListener('click', () => {
        if (state.currentPage > 1) {
            state.currentPage--;
            fetchElections();
        }
    });

    nextPageButton.addEventListener('click', () => {
        if (state.currentPage < totalPages) {
            state.currentPage++;
            fetchElections();
        }
    });
}

function restoreState() {
    searchInput.value = state.yearFilter || '';
    updateSortArrow();
}

// Placeholder functions for editing and deleting elections
function editElection(id) {
    alert('Edit election with ID: ' + id);
}

function deleteElection(id) {
    alert('Delete election with ID: ' + id);
}

export function render() {
    console.log("Rendering election page");
    if (initializeElements()) {
        restoreState();
        setupEventListeners();
        fetchElections();
    }
}

export function init() {
    console.log("Initializing election page");
    // Reset state to default values only on first load
    if (!window.electionPageInitialized) {
        state.currentPage = 1;
        state.sortOrder = 'DESC';
        state.yearFilter = null;
        window.electionPageInitialized = true;
    }
    render();
}