<?php
// Include database configuration
require '../../db_config.php';

try {
    // Get query parameters for search, sort, and pagination
    $search_student_name = isset($_GET['student_name']) ? $_GET['student_name'] : '';
    $search_father_name = isset($_GET['father_name']) ? $_GET['father_name'] : '';
    $sort_column = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'student_id';
    $sort_order = isset($_GET['sort_order']) && strtolower($_GET['sort_order']) === 'desc' ? 'DESC' : 'ASC';
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12; // Items per page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Page number

    // Calculate the offset for pagination
    $offset = ($page - 1) * $limit;

    // Base SQL query with pagination, sorting, and optional search conditions
    $sql = "SELECT student_id, student_name, father_name, address, contact_number, parents_image 
            FROM student 
            WHERE 1";

    // Add search conditions if provided
    if (!empty($search_student_name)) {
        $sql .= " AND student_name LIKE :student_name";
    }
    if (!empty($search_father_name)) {
        $sql .= " AND father_name LIKE :father_name";
    }

    // Add sorting
    $sql .= " ORDER BY $sort_column $sort_order";

    // Add pagination
    $sql .= " LIMIT :limit OFFSET :offset";

    // Prepare the query
    $stmt = $conn->prepare($sql);

    // Bind search parameters if they are set
    if (!empty($search_student_name)) {
        $stmt->bindValue(':student_name', '%' . $search_student_name . '%', PDO::PARAM_STR);
    }
    if (!empty($search_father_name)) {
        $stmt->bindValue(':father_name', '%' . $search_father_name . '%', PDO::PARAM_STR);
    }

    // Bind pagination parameters
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    // Execute the query
    $stmt->execute();

    // Fetch all results
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the total number of records for pagination
    $count_stmt = $conn->prepare("SELECT COUNT(*) AS total FROM student WHERE 1");
    if (!empty($search_student_name)) {
        $count_stmt->bindValue(':student_name', '%' . $search_student_name . '%', PDO::PARAM_STR);
    }
    if (!empty($search_father_name)) {
        $count_stmt->bindValue(':father_name', '%' . $search_father_name . '%', PDO::PARAM_STR);
    }
    $count_stmt->execute();
    $total_records = $count_stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Calculate total pages
    $total_pages = ceil($total_records / $limit);

    // Return results as JSON
    echo json_encode([
        "status" => "success",
        "data" => $students,
        "pagination" => [
            "current_page" => $page,
            "total_pages" => $total_pages,
            "total_records" => $total_records,
            "limit" => $limit
        ]
    ]);

} catch (PDOException $e) {
    // Return error message if there's an issue with the query or connection
    echo json_encode([
        "status" => "error",
        "message" => "Database Error: " . $e->getMessage()
    ]);
}

// Close the connection
$conn = null;
?>
