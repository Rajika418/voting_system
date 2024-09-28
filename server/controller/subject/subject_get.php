
<?php 

/*

header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all subjects with corresponding teacher details (if available)
    $stmt = $conn->prepare("
        SELECT s.subject_id, s.subject_name, t.teacher_id, t.teacher_name 
        FROM subjects s
        LEFT JOIN subject_teacher st ON s.subject_id = st.subject_id
        LEFT JOIN teacher t ON st.teacher_id = t.teacher_id
    ");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$subjects) {
        echo json_encode(['status' => 'error', 'message' => 'No subjects found']);
        exit();
    }

    // Prepare the final result
    $result = [
        'status' => 'success',
        'subjects' => []
    ];

    foreach ($subjects as $subject) {
        $result['subjects'][] = [
            'subject_id' => $subject['subject_id'],
            'subject_name' => $subject['subject_name'],
            'teacher' => !empty($subject['teacher_id']) ? [
                'teacher_id' => $subject['teacher_id'],
                'teacher_name' => $subject['teacher_name']
            ] : null
        ];
    }

    // Return the result as JSON
    echo json_encode($result);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
*/

// Include the database connection file
require "../../db_config.php";

// Set headers for the API response
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Origin: *");

try {
    // Query to retrieve data from the 'subjects' table where year is 'O/L' or 'o/l'
    $sql = "SELECT subject_id, year, section, subject_name FROM subjects WHERE LOWER(year) = 'o/l'";
    
    // Prepare and execute the query
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Check if any records are returned
    if ($stmt->rowCount() > 0) {
        // Fetch all rows as an associative array
        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Initialize arrays for grouped data
        $no_section = []; // For subjects with no section (null)
        $sectioned_subjects = []; // For subjects grouped by section

        // Loop through the subjects and categorize them
        foreach ($subjects as $subject) {
            if (is_null($subject['section']) || empty($subject['section'])) {
                // Add to the 'no_section' array
                $no_section[] = $subject;
            } else {
                // Group subjects by their section
                if (!isset($sectioned_subjects[$subject['section']])) {
                    $sectioned_subjects[$subject['section']] = [];
                }
                $sectioned_subjects[$subject['section']][] = $subject;
            }
        }

        // Combine the results
        $response = [
            "no_section" => $no_section,
            "sections" => $sectioned_subjects
        ];

        // Return the response as JSON
        echo json_encode($response);
    } else {
        // If no records are found, return a message
        echo json_encode(array("message" => "No subjects found."));
    }
} catch (Exception $e) {
    // Return an error message if there's an exception
    echo json_encode(array("message" => "Error occurred: " . $e->getMessage()));
}

// PDO automatically closes the connection when the script ends.
