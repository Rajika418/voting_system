let electionId = null;

function extractelectionId() {
    const hash = window.location.hash;
    const match = hash.match(/#\/elections\/candidates\/(\d+)/);
    if (match && match[1]) {
        electionId = parseInt(match[1]);
        console.log("Extracted election ID:", electionId);
    } else {
        console.error("Failed to extract election ID from URL");
    }
}



async function fetchCandidateData() {
    if (!electionId) {
        console.error("Election ID is not set");
        return;
    }

    try {
        const response = await fetch(`http://localhost/voting_system/server/controller/election/candidate/candidate_get.php?id=${electionId}`);
        const data = await response.json();

        if (data.status === 'success') {
            renderCandidateData(data.data);
        } else {
            console.error('Failed to fetch candidate data:', data.message);
        }
    } catch (error) {
        console.error('Error fetching candidate data:', error);
    }
}

function renderCandidateData(candidate) {
    const candidateInfo = document.getElementById('candidateInfo');
    if (!candidateInfo) {
        console.error('Candidate info element not found');
        return;
    }

    candidateInfo.innerHTML = `
        <h2>Candidate Information</h2>
        <p><strong>Name:</strong> ${candidate.name}</p>
        <p><strong>Election:</strong> ${candidate.election_name}</p>
        <p><strong>Position:</strong> ${candidate.position}</p>
        <!-- Add more candidate details as needed -->
    `;
}

export function init() {
    console.log("Initializing candidate page");
    extractelectionId();
}

export function render() {
    console.log("Rendering candidate page");
    fetchCandidateData();
}