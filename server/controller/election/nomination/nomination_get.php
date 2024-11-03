<?php
// Include the database configuration file
include '../../../db_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get the election ID from the input parameter
    $electionId = isset($_GET['election_id']) ? intval($_GET['election_id']) : null;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';
    $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10; // Default limit is 10
    $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Default page is 1
    $offset = ($page - 1) * $limit;

    if ($electionId) {
        // SQL query with election_id, search, pagination, and isNominated condition
        $sql = "
            SELECT 
                n.id, 
                n.election_id,
                e.election_name,
                e.year,
                n.student_id, 
                s.student_name, 
                s.grade_id, 
                g.grade_name, 
                u.user_id, 
                u.image,
                n.why,
                n.motive,
                n.what,
                n.isNominated,
                n.isRejected
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
            // Prepare and bind parameters
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':election_id', $electionId, PDO::PARAM_INT);
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

            // Execute the query
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Count total records for pagination
            $countSql = "
                SELECT COUNT(*) as total
                FROM 
                    nomination n
                JOIN 
                    student s ON n.student_id = s.student_id
                WHERE 
                    n.election_id = :election_id 
                    AND n.isNominated = false
                    AND s.student_name LIKE :search
            ";
            $countStmt = $conn->prepare($countSql);
            $countStmt->bindParam(':election_id', $electionId, PDO::PARAM_INT);
            $countStmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $countStmt->execute();
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Prepare and send the JSON response
            if ($results) {
                echo json_encode([
                    'status' => 'success',
                    'data' => $results,
                    'total' => $totalCount,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => ceil($totalCount / $limit)
                ]);
            } else {
                echo json_encode([
                    'status' => 'success',
                    'data' => [],
                    'total' => 0,
                    'page' => $page,
                    'limit' => $limit,
                    'totalPages' => 0,
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
