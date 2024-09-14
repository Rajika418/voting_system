<?php 

require "../../db_config.php" ;


// Prepare the SQL query
$sql = "
    SELECT 
        student.student_id, 
        student.student_name, 
        student.address, 
        student.contact_number, 
        student.registration_number, 
        student.guardian, 
        student.join_date, 
        student.leave_date, 
        users.user_name, 
        users.email, 
        users.image, 
        grade.grade_name, 
        teacher.teacher_name  -- Fetch teacher_name from the teacher table
    FROM student 
    JOIN users ON student.user_id = users.user_id 
    JOIN grade ON student.grade_id = grade.grade_id
    JOIN teacher ON grade.teacher_id = teacher.teacher_id  -- Join the teacher table using teacher_id
";

// Check if any filter parameters are provided in the GET request
$where_conditions = [];
$params = [];

if (isset($_GET['student_id'])) {
    $where_conditions[] = "student.student_id = :student_id";
    $params[':student_id'] = $_GET['student_id'];
}

if (isset($_GET['grade_name'])) {
    $where_conditions[] = "grade.grade_name = :grade_name";
    $params[':grade_name'] = $_GET['grade_name'];
}

// Add WHERE conditions if any
if (!empty($where_conditions)) {
    $sql .= " WHERE " . implode(" AND ", $where_conditions);
}

// Sort by grade_name and then by registration_number within each grade
$sql .= " ORDER BY grade.grade_name, student.registration_number";

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default page
$offset = ($page - 1) * $limit;

// Add LIMIT and OFFSET clauses
$sql .= " LIMIT :limit OFFSET :offset";

// Prepare and execute the SQL statement
try {
    $stmt = $conn->prepare($sql);
    
    // Bind parameters
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    
    // Bind pagination parameters
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    
    $stmt->execute();

    // Fetch all results
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($students) {
        // Return the results as JSON
        echo json_encode($students);
    } else {
        echo json_encode(["message" => "No students found"]);
    }
} catch (Exception $e) {
    echo json_encode(["message" => "Query failed: " . $e->getMessage()]);
}
?>
