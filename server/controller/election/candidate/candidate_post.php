<?php
// add_candidate.php

require '../../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the JSON input
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Check if nomination_id is in the JSON data
    $nomination_id = isset($inputData['nomination_id']) ? intval($inputData['nomination_id']) : null;

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
        $stmt = $conn->prepare("INSERT INTO candidate (nomination_id) VALUES (:nomination_id)");

        // Bind the nomination_id parameter
        $stmt->bindParam(':nomination_id', $nomination_id, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            // Get the ID of the newly created candidate
            $candidate_id = $conn->lastInsertId();

            // Update the nomination table to set isNominated to true
            $updateStmt = $conn->prepare("UPDATE nomination SET isNominated = true WHERE id = :nomination_id");
            $updateStmt->bindParam(':nomination_id', $nomination_id, PDO::PARAM_INT);
            $updateStmt->execute();

            echo json_encode([
                'status' => 'success',
                'message' => 'Candidate added successfully.',
                'candidate_id' => $candidate_id // Return the ID of the newly created candidate
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
