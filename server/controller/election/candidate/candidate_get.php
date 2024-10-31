<?php
// get_candidates.php

include '../../../db_config.php'; // Include database connection

header('Content-Type: application/json'); // Set the content type to JSON

// Get search term and pagination parameters from query string
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit

$offset = ($page - 1) * $limit; // Calculate offset for pagination

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
        u.image,
        e.id AS election_id
    FROM candidate c
    JOIN nomination n ON c.nomination_id = n.id
    JOIN student s ON n.student_id = s.student_id 
    JOIN users u ON s.user_id = u.user_id
    JOIN elections e ON n.election_id = e.id  -- Changed to 'elections'
    WHERE s.student_name LIKE :search
    LIMIT :limit OFFSET :offset
";

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
$stmt->bindValue(':search', '%' . $searchTerm . '%', PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$response = [];
$totalCandidates = $conn->query("SELECT COUNT(*) FROM candidate c
    JOIN nomination n ON c.nomination_id = n.id
    JOIN student s ON n.student_id = s.student_id
    WHERE s.student_name LIKE '%" . $searchTerm . "%'")->fetchColumn();

if ($stmt->rowCount() > 0) {
    // Fetch each row and append it to the response array
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $response[] = $row;
    }

    $response = [
        'status' => 'success',
        'data' => $response,
        'total' => $totalCandidates,
        'page' => $page,
        'limit' => $limit
    ];
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
