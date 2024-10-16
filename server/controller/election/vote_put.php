<?php
// vote_candidate.php

require '../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

// Check if the request method is PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the raw input from the PUT request
    parse_str(file_get_contents("php://input"), $_PUT);

    // Retrieve the candidate ID from the input
    $candidate_id = isset($_PUT['candidate_id']) ? intval($_PUT['candidate_id']) : null;

    // Validate the input
    if ($candidate_id === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'candidate_id is required.'
        ]);
        exit;
    }

    try {
        // Prepare the SQL statement to update total_votes
        $stmt = $conn->prepare("UPDATE candidate SET total_votes = total_votes + 1 WHERE id = ?");
        
        // Execute the statement with the candidate ID
        $stmt->execute([$candidate_id]);

        // Check if the update was successful
        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Vote counted successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Candidate not found or no votes updated.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to count vote: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

// Close the database connection
$conn = null; // Set connection to null to close
?>
