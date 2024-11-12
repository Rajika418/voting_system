let currentPage = 1;
let totalPages = 1;

async function fetchParents(page) {
    try {
        const response = await fetch(`http://localhost/voting_system/server/controller/student/parents_get.php?action=read&page=${page}`);
        const data = await response.json();
        
        if (data.status === 'success') {
            displayParents(data.data);
            updatePagination(data.pagination);
        } else {
            console.error('Error fetching data:', data);
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function displayParents(parents) {
    const container = document.getElementById('cardsContainer');
    container.innerHTML = '';

    parents.forEach(parent => {
        const card = document.createElement('div');
        card.className = 'card';
        card.innerHTML = `
            <h3>${parent.father_name}'s Family</h3>
            <div class="card-content">
                <div class="card-info">
                    <span class="label">Student Name:</span>
                    <span class="value">${parent.student_name}</span>
                </div>
                <div class="card-info">
                    <span class="label">Address:</span>
                    <span class="value">${parent.address}</span>
                </div>
                <div class="card-info">
                    <span class="label">Contact:</span>
                    <span class="value">${parent.contact_number}</span>
                </div>
                ${parent.parents_image ? `<div class="card-image"><img src="${parent.parents_image}" alt="Parent's Image"></div>` : ''}
            </div>
        `;
        container.appendChild(card);
    });
}

function updatePagination(pagination) {
    const paginationContainer = document.getElementById('pagination');
    totalPages = pagination.total_pages;
    currentPage = pagination.current_page;

    paginationContainer.innerHTML = `
        <button onclick="changePage(${currentPage - 1})" 
                ${currentPage === 1 ? 'disabled' : ''}>
            Previous
        </button>
        <span>Page ${currentPage} of ${totalPages}</span>
        <button onclick="changePage(${currentPage + 1})"
                ${currentPage === totalPages ? 'disabled' : ''}>
            Next
        </button>
    `;
}

function changePage(page) {
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        fetchParents(currentPage);
    }
}

// Initial load
fetchParents(1);
