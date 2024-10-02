<?php 

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Origin: *');

require "../../db_config.php";

// Prepare the SQL query to get teachers and their related grades
$sql = "
    SELECT t.teacher_id, t.teacher_name, GROUP_CONCAT(g.grade_name) AS grades 
    FROM teacher t
    LEFT JOIN grade g ON t.teacher_id = g.teacher_id
    GROUP BY t.teacher_id, t.teacher_name
";

// Execute the SQL statement
try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Fetch all results
    $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Format the grades as an array instead of a comma-separated string
    foreach ($teachers as &$teacher) {
        $teacher['grades'] = !empty($teacher['grades']) ? explode(',', $teacher['grades']) : [];
    }

    if ($teachers) {
        echo json_encode(["status" => "success", "teachers" => $teachers]);
    } else {
        echo json_encode(["status" => "success", "teachers" => []]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Query failed: " . $e->getMessage()]);
}

?>
