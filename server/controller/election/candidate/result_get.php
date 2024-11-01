<?php
// Include the database configuration file
require '../../../db_config.php';

// Set headers to allow API access
header("Content-Type: application/json; charset=UTF-8");

try {
    // Check the request method
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Prepare an SQL statement to select candidate data
        $sql = "SELECT c.id, n.election_id, e.election_name, year e, c.nomination_id, n.student_id, s.student_name, c.total_votes 
                FROM candidate c 
                JOIN nomination n ON c.nomination_id = n.id
                JOIN elections e ON n.election_id = e.id
                JOIN student s ON n.student_id = s.student_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        // Fetch all rows as an associative array
        $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If no records are found, $candidates will be an empty array
        if ($candidates) {
            // Respond with the data in JSON format
            echo json_encode($candidates);
        } else {
            // If no records are found, respond with an empty array
            echo json_encode(array());
        }
    } else {
        // If the request method is not GET, respond with a 405 Method Not Allowed
        http_response_code(405);
        echo json_encode(array("message" => "Method Not Allowed"));
    }
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500);
    echo json_encode(array("message" => "Database error: " . $e->getMessage()));
}

// No need to close PDO connection, it closes automatically when the script ends
?>
