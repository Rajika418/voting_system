// Global variables for pagination
let currentPage = 1;
let limit = 10;

// Function to safely parse JSON response
async function parseJSON(response) {
    try {
        const data = await response.json();
        return { success: true, data };
    } catch (error) {
        console.error('JSON parsing error:', error);
        return { success: false, error };
    }
}

// Main fetch function with proper error handling
async function fetchCandidates() {
    const searchTerm = document.getElementById('search').value;
    const url = `http://localhost/voting_system/server/controller/election/candidate/result_get.php?action=read&page=${currentPage}&limit=${limit}&search=${searchTerm}`;

    try {
        // Show loading state
        showLoading();

        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const { success, data, error } = await parseJSON(response);
        if (!success) {
            throw new Error('Failed to parse response data');
        }

        console.log('Received data:', data);
        displayCandidates(data);
    } catch (error) {
        console.error('Fetch error:', error);
        displayError('Failed to load candidates. Please try again later.');
    }
}

// Function to show loading state
function showLoading() {
    const candidateBody = document.getElementById('candidateBody');
    if (candidateBody) {
        candidateBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">
                    Loading candidates...
                </td>
            </tr>
        `;
    }
}

// Function to display candidates
function displayCandidates(data) {
    const candidateBody = document.getElementById('candidateBody');
    if (!candidateBody) return;

    candidateBody.innerHTML = '';

    // Handle different data structures
    let candidates = [];
    if (Array.isArray(data)) {
        candidates = data;
    } else if (data && Array.isArray(data.data)) {
        candidates = data.data;
    }

    if (candidates.length === 0) {
        displayNoResults();
        return;
    }

    candidates.forEach((candidate, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${(currentPage - 1) * limit + index + 1}</td>
            <td>${sanitizeHTML(candidate.election_name || '')}</td>
            <td>${sanitizeHTML(candidate.e || '')}</td>
            <td>${sanitizeHTML(candidate.student_name || '')}</td>
            <td>${candidate.total_votes || 0}</td>
        `;
        candidateBody.appendChild(row);
    });

    updatePagination(candidates.length);
}

// Function to sanitize HTML and prevent XSS
function sanitizeHTML(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

// Function to display no results message
function displayNoResults() {
    const candidateBody = document.getElementById('candidateBody');
    if (candidateBody) {
        candidateBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">
                    No candidates found for this search.
                </td>
            </tr>
        `;
    }
    updatePagination(0);
}

// Function to display error message
function displayError(message) {
    const candidateBody = document.getElementById('candidateBody');
    if (candidateBody) {
        candidateBody.innerHTML = `
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px; color: red;">
                    ${sanitizeHTML(message)}
                </td>
            </tr>
        `;
    }
    updatePagination(0);
}

// Function to update pagination
function updatePagination(dataLength) {
    const pageInfo = document.getElementById('pageInfo');
    const prevButton = document.getElementById('prevPage');
    const nextButton = document.getElementById('nextPage');

    if (pageInfo) pageInfo.textContent = `Page ${currentPage}`;
    if (prevButton) prevButton.disabled = currentPage === 1;
    if (nextButton) nextButton.disabled = dataLength < limit;
}

// Function to change page
function changePage(direction) {
    const newPage = currentPage + direction;
    if (newPage < 1) return;
    currentPage = newPage;
    fetchCandidates();
}

// Search handler with debouncing
let searchTimeout;
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        currentPage = 1; // Reset to first page on new search
        fetchCandidates();
    }, 300);
}

// Initialize page
function initializePage() {
    // Add event listener for search
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('input', handleSearch);
    }

    // Initial fetch
    fetchCandidates();
}

// Wait for DOM to be ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializePage);
} else {
    initializePage();
}