<?php

// get_user_election.php

require '../../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve user_id and election_id from the query parameters
    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
    $election_id = isset($_GET['election_id']) ? intval($_GET['election_id']) : null;

    // Validate the input
    if ($user_id === null || $election_id === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'user_id and election_id are required.'
        ]);
        exit;
    }

    try {
        // Prepare and execute the query to get the user's election data
        $stmt = $conn->prepare("SELECT * FROM user_election WHERE user_id = ? AND election_id = ?");
        $stmt->execute([$user_id, $election_id]);

        $userElectionData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userElectionData) {
            // Return the user election data
            echo json_encode([
                'status' => 'success',
                'data' => $userElectionData
            ]);
        } else {
            // No data found for the given user_id and election_id
            echo json_encode([
                'status' => 'error',
                'message' => 'No election data found for the provided user_id and election_id.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'An unexpected error occurred. Please try again later.',
            'error' => $e->getMessage() // Optional: display error message for debugging
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Please use GET.'
    ]);
}

// Close the database connection
$conn = null;
