<?php
// get_candidates.php
include '../../../db_config.php';

header('Content-Type: application/json');

try {
    // Sanitize and validate input parameters
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
    $electionId = isset($_GET['id']) ? intval($_GET['id']) : 0; // Get election_id from the query parameters
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
    $limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 10;
    $offset = ($page - 1) * $limit;

    // Base query for candidates with necessary joins
    $baseQuery = "
        SELECT 
            c.id AS candidate_id,
            s.student_name,
            c.total_votes
        FROM 
            candidate c
            JOIN nomination n ON c.nomination_id = n.id
            JOIN student s ON n.student_id = s.student_id
        WHERE n.election_id = :electionId
    ";

    // Add search condition if search term is provided
    $params = [':electionId' => $electionId];
    if (!empty($searchTerm)) {
        $baseQuery .= " AND s.student_name LIKE :search";
        $params[':search'] = '%' . $searchTerm . '%';
    }

    // Count total records for pagination
    $countQuery = "
        SELECT COUNT(*) 
        FROM candidate c
        JOIN nomination n ON c.nomination_id = n.id
        JOIN student s ON n.student_id = s.student_id
        WHERE n.election_id = :electionId
    ";

    if (!empty($searchTerm)) {
        $countQuery .= " AND s.student_name LIKE :search";
    }

    // Prepare and execute the count query
    $countStmt = $conn->prepare($countQuery);
    foreach ($params as $key => $value) {
        $countStmt->bindValue($key, $value);
    }
    $countStmt->execute();
    $totalRecords = $countStmt->fetchColumn();

    // Add pagination to the main query
    $baseQuery .= " ORDER BY s.student_name
                    LIMIT :limit OFFSET :offset";

    // Prepare and execute the main query
    $stmt = $conn->prepare($baseQuery);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($candidates) > 0) {
        echo json_encode([
            'status' => 'success',
            'data' => $candidates,
            'total' => $totalRecords,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => ceil($totalRecords / $limit)
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'data' => [],
            'total' => 0,
            'page' => $page,
            'limit' => $limit,
            'totalPages' => 0,
            'message' => 'No candidates found matching the search criteria.'
        ]);
    }
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error occurred: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'An unexpected error occurred: ' . $e->getMessage()
    ]);
}
