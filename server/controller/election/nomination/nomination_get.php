<?php
// Include the database configuration file
include '../../../db_config.php';

// API to get nomination data by election ID, with search and pagination
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the election ID from the request
    $electionId = isset($_GET['election_id']) ? $_GET['election_id'] : null;
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Number of results per page (default is 10)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page (default is 1)
    $offset = ($page - 1) * $limit; // Offset calculation for pagination

    if ($electionId) {
        // Prepare the SQL query with election_id, search, and pagination
        $sql = "
            SELECT 
                n.id, 
                n.election_id,
                n.student_id, 
                s.student_name, 
                s.grade_id, 
                g.grade_name, 
                u.user_id, 
                u.image,
                n.why,
                n.motive,
                n.what
            FROM 
                nomination n
            JOIN 
                student s ON n.student_id = s.student_id
            JOIN 
                users u ON s.user_id = u.user_id
            JOIN 
                grade g ON s.grade_id = g.grade_id
            JOIN
                elections e ON n.election_id = e.id 
            WHERE 
                n.election_id = :election_id 
                AND s.student_name LIKE :search
            LIMIT :limit OFFSET :offset
        ";

        try {
            // Prepare the statement
            $stmt = $conn->prepare($sql);
            // Bind parameters
            $stmt->bindParam(':election_id', $electionId, PDO::PARAM_INT);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT); // Use bindValue instead of bindParam for limit
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Use bindValue instead of bindParam for offset

            // Execute the query
            $stmt->execute();

            // Fetch all results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get the total count of records for pagination
            $countSql = "
                SELECT COUNT(*) as total
                FROM 
                    nomination n
                JOIN 
                    student s ON n.student_id = s.student_id
                WHERE 
                    n.election_id = :election_id 
                    AND s.student_name LIKE :search
            ";
            $countStmt = $conn->prepare($countSql);
            $countStmt->bindParam(':election_id', $electionId, PDO::PARAM_INT);
            $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->execute();
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Check if results were found
            if ($results) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $results,
                    'total' => $totalCount,
                    'page' => $page,
                    'limit' => $limit
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No nominations found.'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error fetching nominations: ' . $e->getMessage()
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Election ID is required.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}
?>
