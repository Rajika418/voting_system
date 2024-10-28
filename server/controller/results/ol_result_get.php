<?php
// Import the database configuration file
require '../../db_config.php';

try {
    // Get the page number for pagination, if not set, default is 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;  // Number of results per page
    $offset = ($page - 1) * $limit;

    // Set sorting parameters
    $sort_field = isset($_GET['sort_field']) ? $_GET['sort_field'] : 'exam_year';
    $sort_order = isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['asc', 'desc']) ? $_GET['sort_order'] : 'desc';
    
    // Secondary sort by index_no
    $secondary_sort = "se.index_no ASC";

    // Get search query for student name or index_no
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    // Get year parameter (e.g., "A/L" or "O/L") from query string
    $year_param = isset($_GET['year']) ? $_GET['year'] : null;

    // Base SQL query
    $sql = "
        SELECT DISTINCT
            se.id,
            se.index_no,
            se.student_id,
            se.year AS exam_year,
            se.nic,
            s.student_name,
            r.result,
            r.subject_id,
            r.exam_id,
            sub.subject_name,
            sub.year AS subject_year
        FROM 
            student_exam se
        LEFT JOIN 
            student s ON se.student_id = s.student_id
        LEFT JOIN 
            results r ON se.id = r.exam_id
        LEFT JOIN 
            subjects sub ON r.subject_id = sub.subject_id
        WHERE 1=1";

    // Add search conditions
    if (!empty($search_query)) {
        $sql .= " AND (s.student_name LIKE :search_query OR se.index_no LIKE :search_query)";
    }

    // Add year filter condition
    if (!is_null($year_param)) {
        $sql .= " AND sub.year = :year_param";
    }

    // Count total records for pagination
    $count_sql = preg_replace('/SELECT DISTINCT.*?FROM/', 'SELECT COUNT(DISTINCT se.id) FROM', $sql);
    $count_stmt = $conn->prepare($count_sql);
    
    if (!empty($search_query)) {
        $search_term = '%' . $search_query . '%';
        $count_stmt->bindParam(':search_query', $search_term, PDO::PARAM_STR);
    }
    if (!is_null($year_param)) {
        $count_stmt->bindParam(':year_param', $year_param, PDO::PARAM_STR);
    }
    
    $count_stmt->execute();
    $total_records = $count_stmt->fetchColumn();
    $total_pages = ceil($total_records / $limit);

    // Add sorting and pagination to main query
    $sql .= " ORDER BY " . ($sort_field === 'exam_year' ? "se.year $sort_order" : "se.index_no ASC");
    $sql .= " LIMIT :limit OFFSET :offset";

    // Prepare and execute main query
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    if (!empty($search_query)) {
        $search_term = '%' . $search_query . '%';
        $stmt->bindParam(':search_query', $search_term, PDO::PARAM_STR);
    }
    if (!is_null($year_param)) {
        $stmt->bindParam(':year_param', $year_param, PDO::PARAM_STR);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    // Fetch and group data
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!isset($data[$row['index_no']])) {
            $data[$row['index_no']] = [
                'id' => $row['id'],
                'index_no' => $row['index_no'],
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'year' => $row['exam_year'],
                'nic' => $row['nic'],
                'results' => []
            ];
        }

        if ($row['subject_id']) {
            $data[$row['index_no']]['results'][] = [
                'subject_id' => $row['subject_id'],
                'subject_name' => $row['subject_name'],
                'result' => $row['result']
            ];
        }
    }

    // Return response with pagination metadata
    header('Content-Type: application/json');
    echo json_encode([
        'data' => array_values($data),
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'total_records' => $total_records,
            'limit' => $limit
        ],
        'sorting' => [
            'field' => $sort_field,
            'order' => $sort_order
        ]
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => "Database error: " . $e->getMessage()
    ]);
}

// Close connection
$conn = null;