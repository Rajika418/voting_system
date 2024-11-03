<?php
// Include the database configuration file
include '../../../db_config.php';

// API to reject a nomination
if ($_SERVER['REQUEST_METHOD'] === 'PATCH') { // Use PATCH for updates
    // Get the ID from the query string or request body
    parse_str(file_get_contents("php://input"), $_PATCH);
    $id = $_GET['id'] ?? $_PATCH['id'] ?? null;

    // Validate required fields
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required.'
        ]);
        exit();
    }

    // Prepare and execute the update statement
    try {
        $stmt = $conn->prepare("UPDATE nomination SET isRejected = true WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Nomination marked as rejected successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nomination not found or already marked as rejected.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error rejecting nomination: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
