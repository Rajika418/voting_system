// Fetch election details from API
async function fetchElections() {
    try {
        const response = await fetch('http://localhost/voting_system/server/controller/election/election_get.php');
        const result = await response.json();
        
        if (result.status === "success") {
            displayElections(result.data);
        } else {
            document.getElementById('electionContainer').innerHTML = `<p>No elections available.</p>`;
        }
    } catch (error) {
        console.error('Error fetching elections:', error);
        document.getElementById('electionContainer').innerHTML = `<p>Failed to load elections. Please try again later.</p>`;
    }
}

// Display elections in cards
function displayElections(elections) {
    const container = document.getElementById('electionContainer');
    container.innerHTML = '';  // Clear previous content

    elections.forEach(election => {
        const electionCard = document.createElement('div');
        electionCard.classList.add('election-card');

        electionCard.innerHTML = `
            <img src="${election.image}" alt="${election.election_name} image" class="election-image">
            <h3>${election.election_name}</h3>
            <p><strong>Year:</strong> ${election.year}</p>
            <p><strong>Nomination Period:</strong> ${election.nom_start_date} - ${election.nom_end_date}</p>
            <p><strong>Election Period:</strong> ${election.ele_start_date} - ${election.ele_end_date}</p>
            <button class="nominate-button">Nominate</button>
        `;

        container.appendChild(electionCard);
    });
}

// Load elections when the page loads
document.addEventListener('DOMContentLoaded', fetchElections);
