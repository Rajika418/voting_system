// Get modal elements
const addElectionModal = document.getElementById('addElectionModal');
const addElectionBtn = document.getElementById('addElectionBtn');
const closeModal = document.getElementsByClassName('close')[0];
const form = document.getElementById('electionForm');
const electionTableBody = document.getElementById('electionTableBody');

// Show the Add Election Modal
addElectionBtn.onclick = function() {
    addElectionModal.style.display = 'flex';
}

// Close the Modal when 'X' is clicked
closeModal.onclick = function() {
    addElectionModal.style.display = 'none';
}

// Close Modal when clicked outside of the modal content
window.onclick = function(event) {
    if (event.target == addElectionModal) {
        addElectionModal.style.display = 'none';
    }
}

// Fetch election details and populate the table
document.addEventListener('DOMContentLoaded', function () {
    // Fetch election data when the page loads
    fetchElections();
});

function fetchElections() {
    axios.get('http://localhost/voting_system/server/controller/election/election_get.php?action=read')
        .then(function (response) {
            const elections = response.data;
            const electionTableBody = document.getElementById('electionTableBody');
            electionTableBody.innerHTML = '';  // Clear previous content

            elections.forEach((election, index) => {
                // Create a new row
                const row = document.createElement('tr');

                // Column 1: No (Order number)
                const noCell = document.createElement('td');
                noCell.textContent = index + 1;  // Add row number
                row.appendChild(noCell);

                // Column 2: Image
                const imageCell = document.createElement('td');
                const imgElement = document.createElement('img');
                if (election.image) {
                    imgElement.src = `/uploads/${election.image}`;  // Path to image
                    imgElement.alt = 'Election Logo';
                    imgElement.style.width = '50px';  // Set a width for the image
                    imgElement.style.height = '50px';  // Set a height for the image
                } else {
                    imgElement.src = 'default_image.png';  // Default image if none is uploaded
                    imgElement.alt = 'No Image';
                }
                imageCell.appendChild(imgElement);
                row.appendChild(imageCell);

                // Column 3: Year
                const yearCell = document.createElement('td');
                yearCell.textContent = election.year;
                row.appendChild(yearCell);

                // Column 4: Election Name
                const electionNameCell = document.createElement('td');
                electionNameCell.textContent = election.election_name;
                row.appendChild(electionNameCell);

                // Column 5: Nomination Start Date
                const nominationStartCell = document.createElement('td');
                nominationStartCell.textContent = election.nom_start_date;
                row.appendChild(nominationStartCell);

                // Column 6: Nomination End Date
                const nominationEndCell = document.createElement('td');
                nominationEndCell.textContent = election.nom_end_date;
                row.appendChild(nominationEndCell);

                // Column 7: Election Start Date
                const electionStartCell = document.createElement('td');
                electionStartCell.textContent = election.ele_start_date;
                row.appendChild(electionStartCell);

                // Column 8: Election End Date
                const electionEndCell = document.createElement('td');
                electionEndCell.textContent = election.ele_end_date;
                row.appendChild(electionEndCell);

                // Column 9: Actions (For future edit/delete functionality)
                const actionsCell = document.createElement('td');
                actionsCell.innerHTML = '<button>Edit</button> <button>Delete</button>';
                row.appendChild(actionsCell);

                // Append the row to the table body
                electionTableBody.appendChild(row);
            });
        })
        .catch(function (error) {
            console.error('Error fetching elections:', error);
        });
}

// Handle form submission
form.addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent the default form submission

    const electionData = {
        year: document.getElementById("year").value,
        electionName: document.getElementById("electionName").value,
        nominationStart: document.getElementById("nominationStart").value,
        nominationEnd: document.getElementById("nominationEnd").value,
        electionStart: document.getElementById("electionStart").value,
        electionEnd: document.getElementById("electionEnd").value
    };

    // Make the API request
    fetch("http://localhost/voting_system/server/controller/election/election_post.php?action=create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(electionData),
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        addElectionModal.style.display = "none";  // Close the modal
        form.reset();  // Clear the form
        fetchElections();  // Reload the elections table (use fetchElections instead of loadElections)
    })
    .catch(error => {
        console.error("Error:", error);
    });
});

// Initial load
fetchElections();  // Use fetchElections instead of loadElections


