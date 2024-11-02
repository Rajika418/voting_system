
// URLs to fetch election data and candidate results
const electionDateURL = "http://localhost/voting_system/server/controller/election/election_getyear.php?action=read";
const candidateResultsURL = "http://localhost/voting_system/server/controller/election/candidate/result_get.php?action=read";

// Function to fetch and display election date
async function loadElectionDate() {
    try {
        const response = await fetch(electionDateURL);
        const electionData = await response.json();

        // Assuming the latest election is the first item in the response
        if (electionData.length > 0) {
            const latestElection = electionData[0];
            document.getElementById('election-date').innerText = `Election Date: ${new Date(latestElection.ele_end_date).toLocaleDateString()}`;
        }
    } catch (error) {
        console.error("Error fetching election date:", error);
    }
}

// Function to fetch and display candidate results
async function loadCandidateResults() {
    try {
        const response = await fetch(candidateResultsURL);
        const candidateData = await response.json();

        const resultsContainer = document.getElementById('candidates-results');
        resultsContainer.innerHTML = ""; // Clear any previous content

        candidateData.forEach(candidate => {
            const candidateResult = document.createElement('div');
            candidateResult.classList.add('candidate-card'); // Added new card class
            candidateResult.innerHTML = `
                <h4>Candidate: ${candidate.student_name}</h4>
                <p>Total Votes: ${candidate.total_votes}</p>
            `;
            resultsContainer.appendChild(candidateResult);
        });
    } catch (error) {
        console.error("Error fetching candidate results:", error);
    }
}


// Load data when the page loads
window.onload = () => {
    loadElectionDate();
    loadCandidateResults();
};

