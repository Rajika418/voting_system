let currentPage = 1;
let limit = 10;

function fetchCandidates() {
    const searchTerm = document.getElementById('search').value;
    const url = `http://localhost/voting_system/server/controller/election/candidate/candidate_get.php?page=${currentPage}&limit=${limit}&search=${searchTerm}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            displayCandidates(data);
            updatePagination(data);
        })
        .catch(error => console.error('Error fetching candidates:', error));
}

function displayCandidates(data) {
    const candidateBody = document.getElementById('candidateBody');
    candidateBody.innerHTML = ''; // Clear previous data

    if (data.status === 'success') {
        data.data.forEach(candidate => {
            candidateBody.innerHTML += `
                <tr>
                    <td>${candidate.candidate_id}</td>
                    <td>${candidate.student_name}</td>
                    <td>${candidate.motive}</td>
                    <td>${candidate.what}</td>
                    <td><img src="${candidate.image}" alt="Candidate Image" width="50" /></td>
                </tr>
            `;
        });
    } else {
        candidateBody.innerHTML = `<tr><td colspan="5">${data.message}</td></tr>`;
    }
}

function updatePagination(data) {
    const pageInfo = document.getElementById('pageInfo');
    pageInfo.textContent = `Page ${currentPage}`;
    
    document.getElementById('prevPage').disabled = currentPage === 1;
    document.getElementById('nextPage').disabled = data.data.length < limit;
}

function changePage(direction) {
    currentPage += direction;
    fetchCandidates();
}

document.getElementById('search').addEventListener('input', fetchCandidates);

// Initial fetch
fetchCandidates();
