<?php
// Include the database configuration file
include '../../db_config.php';

// API to delete a nomination
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get the ID from the query string or request body
    parse_str(file_get_contents("php://input"), $_DELETE);
    $id = $_GET['id'] ?? $_DELETE['id'] ?? null;

    // Validate required fields
    if (empty($id)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'ID is required.'
        ]);
        exit();
    }

    // Prepare and execute the delete statement
    try {
        $stmt = $conn->prepare("DELETE FROM nomination WHERE id = ?");
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Nomination deleted successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nomination not found or already deleted.'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error deleting nomination: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
