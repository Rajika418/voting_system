<?php
header('Content-Type: application/json');
require '../../db_config.php'; // Include your database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['grade_id'])) {
    $grade_id = $_GET['grade_id'];

    // Pagination parameters
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default page is 1
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Default limit is 10
    $offset = ($page - 1) * $limit;

    // Search parameter
    $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';

    // Prepare SQL statement to get students with user details
    $stmt = $conn->prepare("
        SELECT s.student_id, s.student_name, s.father_name, s.grade_id, 
               u.image, s.registration_number 
        FROM student s 
        JOIN users u ON s.user_id = u.user_id
        WHERE s.grade_id = :grade_id AND 
              (s.student_name LIKE :search OR s.registration_number LIKE :search)
        LIMIT :limit OFFSET :offset
    ");
    $stmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch total student count for pagination
        $countStmt = $conn->prepare("
            SELECT COUNT(*) as total 
            FROM student s 
            WHERE s.grade_id = :grade_id AND 
                  (s.student_name LIKE :search OR s.registration_number LIKE :search)
        ");
        $countStmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);
        $countStmt->bindValue(':search', $search, PDO::PARAM_STR);

        if ($countStmt->execute()) {
            $countResult = $countStmt->fetch(PDO::FETCH_ASSOC);
            $totalCount = $countResult['total'];

            // Fetch grade details
            $gradeStmt = $conn->prepare("SELECT g.grade_name FROM grade g WHERE g.grade_id = :grade_id");
            $gradeStmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);

            if ($gradeStmt->execute()) {
                $grade_result = $gradeStmt->fetch(PDO::FETCH_ASSOC);

                // Check if grade was found
                if ($grade_result) {
                    $response = [
                        'grade_name' => $grade_result['grade_name'],
                        'students' => $students,
                        'count' => count($students),
                        'total_count' => $totalCount,
                        'current_page' => $page,
                        'total_pages' => ceil($totalCount / $limit)
                    ];
                } else {
                    $response = ['error' => 'Grade not found'];
                }
            } else {
                $response = ['error' => 'Failed to fetch grade details'];
            }
        } else {
            $response = ['error' => 'Failed to fetch total student count'];
        }
    } else {
        $response = ['error' => 'Failed to execute student query'];
    }

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'No grade ID provided']);
}

$conn = null; // Close the connection
