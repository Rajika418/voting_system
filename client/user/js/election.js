document.addEventListener('DOMContentLoaded', function() {
    const votingForm = document.getElementById('votingForm');
    const candidateSelect = document.getElementById('candidateSelect');
    const voteButton = document.getElementById('voteButton');
    const candidatesGrid = document.getElementById('candidatesGrid');

    if (!candidatesGrid) {
        console.error('Candidates grid element is not found.');
        return;
    }

    // Fetch candidates from the server
    fetch('http://localhost/voting_system/server/controller/election/candidate/candidate_get.php?election_id=4')
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                data.data.forEach(candidate => {
                    // Populate candidate cards
                    const candidateCard = document.createElement('div');
                    candidateCard.classList.add('candidate-card');
                    candidateCard.innerHTML = `
                        <div class="candidate-image">
                            <img src="${candidate.image || '../uploads/default-candidate.png'}" 
                                 alt="${candidate.student_name}"
                                 onerror="this.src='../uploads/default-candidate.png';">
                        </div>
                        <div class="candidate-info">
                            <h3>${candidate.student_name}</h3>
                            <p class="position">School President</p>
                            <p class="grade">Grade ${candidate.grade || 'Unknown'}</p>
                            <p class="manifesto">${candidate.motive}</p>
                        </div>
                    `;
                    candidatesGrid.appendChild(candidateCard);

                    // Populate select options
                    const option = document.createElement('option');
                    option.value = candidate.candidate_id;
                    option.textContent = candidate.student_name;
                    candidateSelect.appendChild(option);
                });
            } else {
                console.error('Failed to fetch candidates:', data.message);
            }
        })
        .catch(error => console.error('Error fetching candidates:', error));

    // Handle vote submission
    votingForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!candidateSelect.value) {
            alert('Please select a candidate before voting.');
            return;
        }

        const confirmed = confirm('Are you sure you want to vote for this candidate? This action cannot be undone.');
        if (confirmed) {
            voteButton.disabled = true;
            voteButton.textContent = 'Submitting Vote...';

            fetch('http://localhost/voting_system/server/controller/election/candidate/vote_put.php', {
                method: 'POST', // Keeping as POST
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded' // Change header to URL-encoded
                },
                body: new URLSearchParams({
                    candidate_id: candidateSelect.value,
                    _method: 'PUT' // Set _method to PUT as required by your API
                }).toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Thank you! Your vote has been recorded successfully.');
                    window.location.reload();
                } else {
                    alert(data.message || 'There was an error recording your vote. Please try again.');
                    voteButton.disabled = false;
                    voteButton.textContent = 'Cast Vote';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error submitting your vote. Please try again.');
                voteButton.disabled = false;
                voteButton.textContent = 'Cast Vote';
            });
        }
    });

    // Add hover effect to candidate cards
    document.addEventListener('mouseover', function(event) {
        if (event.target && event.target.classList && event.target.classList.contains('candidate-card')) {
            event.target.style.transform = 'translateY(-5px)';
        }
    }, true);

    document.addEventListener('mouseout', function(event) {
        if (event.target && event.target.classList && event.target.classList.contains('candidate-card')) {
            event.target.style.transform = 'translateY(0)';
        }
    }, true);
});
