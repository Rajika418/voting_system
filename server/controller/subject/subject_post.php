<?php
header('Content-Type: application/json');

// Database connection
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get the input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($data['subject_name']) || empty($data['subject_name']) || !isset($data['teacher_names']) || !is_array($data['teacher_names']) || empty($data['teacher_names'])) {
        echo json_encode(['status' => 'error', 'message' => 'subject_name and an array of teacher_names are required']);
        exit();
    }

    // Insert data into the subjects table (if not already existing)
    $subject_name = $data['subject_name'];
    $section = isset($data['section']) ? $data['section'] : null;

    // Check if subject already exists
    $stmt = $conn->prepare("SELECT subject_id FROM subjects WHERE subject_name = :subject_name");
    $stmt->bindParam(':subject_name', $subject_name);
    $stmt->execute();
    $subject = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($subject) {
        // If subject already exists, use the existing subject_id
        $subject_id = $subject['subject_id'];
    } else {
        // If subject does not exist, insert the new subject
        $stmt = $conn->prepare("INSERT INTO subjects (section, subject_name) VALUES (:section, :subject_name)");
        $stmt->bindParam(':section', $section);
        $stmt->bindParam(':subject_name', $subject_name);
        $stmt->execute();
        $subject_id = $conn->lastInsertId(); // Get the newly inserted subject_id
    }

    // Loop through each teacher name provided and associate them with the subject
    foreach ($data['teacher_names'] as $teacher_name) {
        // Retrieve the teacher_id based on the teacher_name
        $stmt = $conn->prepare("SELECT teacher_id FROM teacher WHERE teacher_name = :teacher_name");
        $stmt->bindParam(':teacher_name', $teacher_name);
        $stmt->execute();
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$teacher) {
            echo json_encode(['status' => 'error', 'message' => 'Teacher not found: ' . $teacher_name]);
            exit();
        }

        $teacher_id = $teacher['teacher_id'];

        // Insert into the subject_teacher table to associate subject with the teacher
        $stmt = $conn->prepare("INSERT INTO subject_teacher (subject_id, teacher_id) VALUES (:subject_id, :teacher_id)");
        $stmt->bindParam(':subject_id', $subject_id);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Subject and teacher associations added successfully']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
