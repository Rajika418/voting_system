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

    // Get year parameter (e.g., "A/L" or "O/L") from query string
    $year_param = isset($_GET['year']) ? $_GET['year'] : null;

    // Prepare the SQL query to get student results
    $sql = "
        SELECT 
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
        WHERE 
            (s.student_name LIKE :search_query OR se.index_no LIKE :search_query)";

    // Add condition to filter by `sub.year` (exam type) if provided
    if (!is_null($year_param)) {
        $sql .= " AND sub.year = :year_param";  // Filter by exam type when provided
    }

    $sql .= " ORDER BY se.year $sort_order
              LIMIT :limit OFFSET :offset";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Add wildcard characters for search query
    $search_term = '%' . $search_query . '%';

    // Bind parameters for the search term, limit, and offset
    $stmt->bindParam(':search_query', $search_term, PDO::PARAM_STR);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Bind the year parameter (exam type) only if it's provided
    if (!is_null($year_param)) {
        $stmt->bindParam(':year_param', $year_param, PDO::PARAM_STR);
    }

    // Execute the statement
    $stmt->execute();

    // Fetch data and group by student
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Check if the student already exists in the data array
        if (!isset($data[$row['index_no']])) {
            // Add the student details for the first time
            $data[$row['index_no']] = [
                'id' => $row['id'],
                'index_no' => $row['index_no'],
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'year' => $row['exam_year'],  // Use the exam year from student_exam
                'nic' => $row['nic'],
                'results' => []
            ];
        }

        // Append the result to the existing student entry
        $data[$row['index_no']]['results'][] = [
            'subject_id' => $row['subject_id'],
            'subject_name' => $row['subject_name'],
            'result' => $row['result']
        ];
    }

    // Prepare SQL query to get all subjects for the given year (A/L or O/L)
    $subject_sql = "SELECT subject_id, subject_name FROM subjects";
    if (!is_null($year_param)) {
        $subject_sql .= " WHERE year = :year_param";
    }

    // Prepare and execute the subject query
    $subject_stmt = $conn->prepare($subject_sql);

    if (!is_null($year_param)) {
        $subject_stmt->bindParam(':year_param', $year_param, PDO::PARAM_STR);
    }

    $subject_stmt->execute();
    $subjects = $subject_stmt->fetchAll(PDO::FETCH_ASSOC);

    // Re-index the array to return as a list
    $data = array_values($data);

    // Return the data as JSON with the subjects list
    header('Content-Type: application/json');
    echo json_encode([
        'data' => $data,
        'subjects' => $subjects,  // Include the list of subjects for the given year
        'page' => $page,
        'limit' => $limit
    ]);
} catch (PDOException $e) {
    // Handle connection error
    echo "Error: " . $e->getMessage();
}

// Close connection (optional in PDO)
$conn = null;
