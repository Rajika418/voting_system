<?php

// Import the database configuration file
require '../../db_config.php'; 

try {
    // Get the page number for pagination, if not set, default is 1
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 10;  // Number of results per page
    $offset = ($page - 1) * $limit;

    // Set sorting order, default is year descending
    $sort_order = isset($_GET['sort_order']) && in_array($_GET['sort_order'], ['asc', 'desc']) ? $_GET['sort_order'] : 'desc';

    // Get search query for student name or index_no
    $search_query = isset($_GET['search']) ? $_GET['search'] : '';

    // Prepare the SQL query
    $sql = "
        SELECT 
            se.id,
            se.index_no,
            se.student_id,
            se.year,
            se.nic,
            s.student_name,
            r.result,
            r.subject_id,
            r.exam_id,
            sub.subject_name
        FROM 
            student_exam se
        LEFT JOIN 
            student s ON se.student_id = s.student_id
        LEFT JOIN 
            results r ON se.id = r.exam_id
        LEFT JOIN 
            subjects sub ON r.subject_id = sub.subject_id
        WHERE 
            (s.student_name LIKE :search_query OR se.index_no LIKE :search_query) 
       
        ORDER BY 
            se.year $sort_order
        LIMIT :limit OFFSET :offset";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Add wildcard characters for search query
    $search_term = '%' . $search_query . '%';

    // Bind parameters for the search term, limit, and offset
    $stmt->bindParam(':search_query', $search_term, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    // Fetch data and prepare the response
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Group results by index_no, student_id, and other details
        $data[] = [
            'id' => $row['id'],
            'index_no' => $row['index_no'],
            'student_id' => $row['student_id'],
            'student_name' => $row['student_name'],
            'year' => $row['year'],
            'nic' => $row['nic'],
            'results' => [
                [
                    'subject_id' => $row['subject_id'],
                    'subject_name' => $row['subject_name'],
                    'result' => $row['result']
                ]
            ]
        ];
    }

    // Return the data as JSON
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data,
        'page' => $page,
        'limit' => $limit
    ]);

} catch(PDOException $e) {
    // Handle connection error
    echo "Error: " . $e->getMessage();
}

// Close connection (optional in PDO)
$conn = null;

?>
