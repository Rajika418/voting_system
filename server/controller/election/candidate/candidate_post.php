<?php
// add_candidate.php

require '../../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the posted data
    // Check if nomination_id is passed in the POST data or URL
    $nomination_id = isset($_POST['nomination_id']) ? intval($_POST['nomination_id']) : 
                     (isset($_GET['nomination_id']) ? intval($_GET['nomination_id']) : null);

    // Validate the input
    if ($nomination_id === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'nomination_id is required.'
        ]);
        exit;
    }

    try {
        // Prepare the SQL statement to insert a new candidate
        $stmt = $conn->prepare("INSERT INTO candidate (nomination_id, total_votes) VALUES (:nomination_id, NULL)");

        // Bind the nomination_id parameter
        $stmt->bindParam(':nomination_id', $nomination_id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Candidate added successfully.',
                'candidate_id' => $conn->lastInsertId() // Return the ID of the newly created candidate
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add candidate.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add candidate: ' . $e->getMessage()
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
