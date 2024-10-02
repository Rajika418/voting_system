<?php

// Enable CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "../../db_config.php";

// Pagination parameters
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$results_per_page = isset($_GET['results_per_page']) && is_numeric($_GET['results_per_page']) ? intval($_GET['results_per_page']) : 10;
$offset = ($page - 1) * $results_per_page;

// Sorting by teacher name (ASC or DESC)
$sort_order = isset($_GET['sort_order']) && in_array(strtoupper($_GET['sort_order']), ['ASC', 'DESC']) ? strtoupper($_GET['sort_order']) : 'ASC';

// Prepare the SQL query for teachers
$sql = "
    SELECT 
        t.teacher_id, 
        t.teacher_name, 
        t.address, 
        t.contact_number, 
        t.nic, 
        t.join_date, 
        t.leave_date,
        u.user_name, 
        u.email,  
        u.image 
    FROM teacher t
    JOIN users u ON t.user_id = u.user_id 
";

// Check if any filter parameters are provided in the GET request
$where_conditions = [];
$params = [];

// Check for specific filters
if (isset($_GET['teacher_id'])) {
    $where_conditions[] = "t.teacher_id = :teacher_id";
    $params[':teacher_id'] = $_GET['teacher_id'];
}

if (isset($_GET['user_name'])) {
    $where_conditions[] = "u.user_name = :user_name";
    $params[':user_name'] = $_GET['user_name'];
}

// Check for search query
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $where_conditions[] = "t.teacher_name LIKE :search";
    $params[':search'] = '%' . $searchQuery . '%'; // Use wildcard for partial match
}

// Add WHERE clause if any conditions exist
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Add sorting and pagination to the SQL query
$sql .= " ORDER BY t.teacher_name $sort_order LIMIT :offset, :results_per_page";

// Prepare and execute the SQL statement
try {
    $stmt = $conn->prepare($sql);

    // Bind the pagination parameters
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':results_per_page', $results_per_page, PDO::PARAM_INT);

    // Bind other query parameters if present
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();

    // Fetch all teachers
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // If teachers were found, fetch grades
    if ($teachers) {
        // Fetch grades for the teachers directly from the grade table
        $teacher_ids = array_column($teachers, 'teacher_id');
        $teacher_ids_placeholder = implode(',', array_fill(0, count($teacher_ids), '?'));

        $grades_sql = "
            SELECT 
                g.teacher_id,
                g.grade_name 
            FROM grade g 
            WHERE g.teacher_id IN ($teacher_ids_placeholder)
        ";

        $grades_stmt = $conn->prepare($grades_sql);
        $grades_stmt->execute($teacher_ids);
        $grades = $grades_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Group grades by teacher_id
        $grades_by_teacher = [];
        foreach ($grades as $grade) {
            $grades_by_teacher[$grade['teacher_id']][] = $grade['grade_name'];
        }

        // Add grades to each teacher
        foreach ($teachers as &$teacher) {
            $teacher['grades'] = isset($grades_by_teacher[$teacher['teacher_id']]) ? $grades_by_teacher[$teacher['teacher_id']] : [];
        }

        // Get total number of teachers for pagination
        $total_sql = "SELECT COUNT(*) FROM teacher t JOIN users u ON t.user_id = u.user_id";
        if (!empty($where_conditions)) {
            $total_sql .= " WHERE " . implode(" AND ", $where_conditions);
        }
        $total_stmt = $conn->prepare($total_sql);
        foreach ($params as $key => $value) {
            $total_stmt->bindValue($key, $value);
        }
        $total_stmt->execute();
        $total_teachers = $total_stmt->fetchColumn();

        // Calculate total pages
        $total_pages = ceil($total_teachers / $results_per_page);

        // Return the results with pagination info
        echo json_encode([
            'data' => $teachers,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'results_per_page' => $results_per_page,
                'total_results' => $total_teachers
            ]
        ]);
    } else {
        echo json_encode(["message" => "No teachers found"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Query failed: " . $e->getMessage()]);
}
