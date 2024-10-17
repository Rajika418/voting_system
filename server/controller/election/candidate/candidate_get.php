<?php
// get_candidates.php

require '../../db_config.php';  // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

// SQL query to retrieve data from the tables
$sql = "
    SELECT 
        c.id AS candidate_id,
        c.nomination_id,
        n.student_id,
        n.motive,
        n.what,
        s.student_name,
        s.user_id,
        u.image
    FROM candidate c
    JOIN nomination n ON c.nomination_id = n.id
    JOIN student s ON n.student_id = s.student_id 
    JOIN users u ON s.user_id = u.user_id 
";

$result = $conn->query($sql);

$response = [];

if ($result->rowCount() > 0) {
    // Fetch each row and append it to the response array
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $response[] = $row;
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'No candidates found.'
    ];
}

// Output the JSON response
echo json_encode($response);

// No need to close the connection; it closes automatically
?>
