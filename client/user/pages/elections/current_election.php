<div class="election-detail-container">
    <div class="candidates-section">
        <h2>Current Election Candidates</h2>
        <div class="candidates-grid" id="candidatesGrid">
            <!-- Candidates will be loaded here dynamically -->
        </div>
    </div>

    <div class="voting-section">
        <div class="voting-box">
            <h2>Cast Your Vote</h2>
            <?php if (!$hasVoted): ?>
                <form id="votingForm" action="#" method="POST">
                    <div class="form-group">
                        <label for="candidateSelect">Select Candidate:</label>
                        <select id="candidateSelect" name="candidate_id" required>
                            <option value="">Choose a candidate...</option>
                            <!-- Options will be loaded dynamically -->
                        </select>
                    </div>
                    <button type="submit" class="vote-button" id="voteButton">
                        Cast Vote
                    </button>
                </form>
            <?php else: ?>
                <div class="already-voted">
                    <p>You have already cast your vote in this election.</p>
                    <p>Thank you for participating!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
