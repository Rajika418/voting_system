<?php
// Fetch candidates from database (mock data for example)
$candidates = [
    [
        'id' => 1,
        'name' => 'John Smith',
        'position' => 'School President',
        'grade' => 'Grade 12',
        'image' => '../uploads/candidate1.jpg',
        'manifesto' => 'Working towards better educational facilities and student welfare.'
    ],
    [
        'id' => 2,
        'name' => 'Sarah Johnson',
        'position' => 'School President',
        'grade' => 'Grade 11',
        'image' => '../uploads/candidate2.jpg',
        'manifesto' => 'Focusing on student activities and academic excellence.'
    ]
];

// Check if user has already voted
$hasVoted = false; // This should be checked from your database
?>

<div class="election-detail-container">
    <div class="candidates-section">
        <h2>Current Election Candidates</h2>
        <div class="candidates-grid">
            <?php foreach ($candidates as $candidate): ?>
                <div class="candidate-card">
                    <div class="candidate-image">
                        <img src="<?php echo htmlspecialchars($candidate['image']); ?>"
                            alt="<?php echo htmlspecialchars($candidate['name']); ?>"
                            onerror="this.src='../uploads/default-candidate.png';">
                    </div>
                    <div class="candidate-info">
                        <h3><?php echo htmlspecialchars($candidate['name']); ?></h3>
                        <p class="position"><?php echo htmlspecialchars($candidate['position']); ?></p>
                        <p class="grade"><?php echo htmlspecialchars($candidate['grade']); ?></p>
                        <p class="manifesto"><?php echo htmlspecialchars($candidate['manifesto']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="voting-section">
        <div class="voting-box">
            <h2>Cast Your Vote</h2>
            <?php if (!$hasVoted): ?>
                <form id="votingForm" action="process_vote.php" method="POST">
                    <div class="form-group">
                        <label for="candidateSelect">Select Candidate:</label>
                        <select id="candidateSelect" name="candidate_id" required>
                            <option value="">Choose a candidate...</option>
                            <?php foreach ($candidates as $candidate): ?>
                                <option value="<?php echo $candidate['id']; ?>">
                                    <?php echo htmlspecialchars($candidate['name']); ?>
                                </option>
                            <?php endforeach; ?>
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