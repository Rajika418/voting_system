
<?php


// vote_candidate.php

require '../../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    // Retrieve the candidate ID
    $candidate_id = isset($_POST['candidate_id']) ? intval($_POST['candidate_id']) : null;

  
    
    // Validate the input
    if ($candidate_id === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'candidate_id is required.'
        ]);
        exit;
    }
    
    try {
        // Start a transaction
        $conn->beginTransaction();
        
        // First, check if the candidate exists and get current vote count
        $checkStmt = $conn->prepare("SELECT id, total_votes FROM candidate WHERE id = ? FOR UPDATE");
        if (!$checkStmt->execute([$candidate_id])) {
            throw new PDOException("Failed to execute select statement.");
        }
        $candidate = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$candidate) {
            // Candidate doesn't exist
            $conn->rollBack();
            echo json_encode([
                'status' => 'error',
                'message' => 'Candidate not found.'
            ]);
            exit;
        }
        
        // Update the total_votes for the candidate
        $updateStmt = $conn->prepare("UPDATE candidate SET total_votes = total_votes + 1 WHERE id = ?");
        if (!$updateStmt->execute([$candidate_id])) {
            throw new PDOException("Vote update failed.");
        }
        
        // Commit the transaction
        $conn->commit();
        
        // Retrieve the updated vote count
        $selectStmt = $conn->prepare("SELECT id, nomination_id, total_votes FROM candidate WHERE id = ?");
        if (!$selectStmt->execute([$candidate_id])) {
            throw new PDOException("Failed to retrieve updated vote count.");
        }
        $updatedCandidate = $selectStmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Vote counted successfully.',
            'data' => $updatedCandidate
        ]);
        
    } catch (PDOException $e) {
        // Roll back the transaction if an error occurred
        $conn->rollBack();
      
        echo json_encode([
            'status' => 'error',
            'message' => 'An unexpected error occurred. Please try again later.',
            'error' => $e->getMessage() // Optional: display error message for debugging
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

// Close the database connection
$conn = null;
?>
