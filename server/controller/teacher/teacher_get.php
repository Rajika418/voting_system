<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = "localhost";
$db_name = "voting_system";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["message" => "Connection failed: " . $e->getMessage()]);
    exit();
}

// Pagination parameters
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$results_per_page = isset($_GET['results_per_page']) && is_numeric($_GET['results_per_page']) ? intval($_GET['results_per_page']) : 10;
$offset = ($page - 1) * $results_per_page;

// Sorting by teacher name (ASC or DESC)
$sort_order = isset($_GET['sort_order']) && in_array(strtoupper($_GET['sort_order']), ['ASC', 'DESC']) ? strtoupper($_GET['sort_order']) : 'ASC';

// Prepare the SQL query
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
        u.image, 
        g.grade_name 
    FROM teacher t
    JOIN users u ON t.user_id = u.user_id 
    LEFT JOIN grade_teacher gt ON t.teacher_id = gt.teacher_id
    LEFT JOIN grade g ON gt.grade_id = g.grade_id
";

// Check if any filter parameters are provided in the GET request
$where_conditions = [];
$params = [];

if (isset($_GET['teacher_id'])) {
    $where_conditions[] = "t.teacher_id = :teacher_id";
    $params[':teacher_id'] = $_GET['teacher_id'];
}

if (isset($_GET['grade_name'])) {
    $where_conditions[] = "g.grade_name = :grade_name";
    $params[':grade_name'] = $_GET['grade_name'];
}

if (isset($_GET['user_name'])) {
    $where_conditions[] = "u.user_name = :user_name";
    $params[':user_name'] = $_GET['user_name'];
}

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

    // Fetch all results
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get total number of teachers for pagination
    $total_sql = "SELECT COUNT(*) FROM teacher";
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

    if ($teachers) {
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
?>
