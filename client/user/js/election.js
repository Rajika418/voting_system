// Election Page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const votingForm = document.getElementById('votingForm');
    const candidateSelect = document.getElementById('candidateSelect');
    const voteButton = document.getElementById('voteButton');

    if (votingForm) {
        votingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!candidateSelect.value) {
                alert('Please select a candidate before voting.');
                return;
            }

            // Show confirmation dialog
            const confirmed = confirm('Are you sure you want to vote for this candidate? This action cannot be undone.');
            
            if (confirmed) {
                // Disable the vote button to prevent double submission
                voteButton.disabled = true;
                voteButton.textContent = 'Submitting Vote...';

                // Submit the form
                fetch('process_vote.php', {
                    method: 'POST',
                    body: new FormData(votingForm)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Thank you! Your vote has been recorded successfully.');
                        // Reload the page to show the "already voted" state
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
    }

    // Add hover effect to candidate cards
    const candidateCards = document.querySelectorAll('.candidate-card');
    candidateCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});