<?php
header("Content-Type: application/json");

// Include database configuration
require '../../db_config.php';

try {
    // Check if the request method is DELETE
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        // Check if the ID is provided in the URL query string
        if (isset($_GET['id'])) {

            // Sanitize the ID
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            // Prepare SQL query to delete the election
            $sql = "DELETE FROM elections WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);

            // Execute the delete query
            if ($stmt->execute()) {
                echo json_encode(["message" => "Election deleted successfully."]);
            } else {
                echo json_encode(["message" => "Failed to delete election."]);
            }
        } else {
            echo json_encode(["message" => "Election ID is required."]);
        }
    } else {
        echo json_encode(["message" => "Invalid request method."]);
    }
} catch (PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
}
