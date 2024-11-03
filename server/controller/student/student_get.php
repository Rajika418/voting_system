<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Origin: *');

// Include database configuration
require '../../db_config.php';

// Prepare the base SQL query to fetch student details
$sql = "
    SELECT 
        student.student_id, 
        student.student_name, 
        student.address, 
        student.contact_number, 
        student.registration_number, 
        student.guardian, 
        student.father_name, 
        student.join_date, 
        student.leave_date, 
        users.user_name, 
        users.email, 
        users.image, 
        grade.grade_name, 
        teacher.teacher_name  -- Fetch teacher_name directly from the teacher table
    FROM student 
    JOIN users ON student.user_id = users.user_id 
    JOIN grade ON student.grade_id = grade.grade_id
    LEFT JOIN teacher ON grade.teacher_id = teacher.teacher_id  -- Direct join with teacher table using teacher_id from grade
";

// Initialize filter, sorting, and pagination variables
$where_conditions = [];
$params = [];
$grade_name = null;
$order_by = 'student.student_name';  // Default sort column
$order_direction = 'ASC';            // Default sort direction

// Check if grade_name filter is provided
if (isset($_GET['grade_name'])) {
    $where_conditions[] = "grade.grade_name = :grade_name";
    $params[':grade_name'] = $_GET['grade_name'];
    $grade_name = $_GET['grade_name'];
}

// Check if sort order is provided (either by student_name or grade_name)
if (isset($_GET['sort_by'])) {
    if ($_GET['sort_by'] === 'student_name') {
        $order_by = 'student.student_name';
    } elseif ($_GET['sort_by'] === 'grade_name') {
        $order_by = 'grade.grade_name';
    }
}

// Check if sorting direction is provided (ascending or descending)
if (isset($_GET['order']) && ($_GET['order'] === 'asc' || $_GET['order'] === 'desc')) {
    $order_direction = strtoupper($_GET['order']);
}

// Add search functionality
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $where_conditions[] = "(student.student_name LIKE :search OR student.registration_number LIKE :search)";
    $params[':search'] = $search;
}

// Add WHERE conditions if any
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Sorting by student_name or grade_name in ascending or descending order
$sql .= " ORDER BY $order_by $order_direction";

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20; // Default limit per page (20 students per page)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;      // Default page number is 1
$offset = ($page - 1) * $limit;                            // Calculate the offset for pagination

// Add LIMIT and OFFSET clauses for pagination
$sql .= " LIMIT :limit OFFSET :offset";

// Prepare and execute the SQL query
try {
    $stmt = $conn->prepare($sql);

    // Bind filter parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    // Bind pagination parameters
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    // Fetch all the results
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    echo json_encode($students);
} catch (Exception $e) {
    echo json_encode(["message" => "Query failed: " . $e->getMessage()]);
}
