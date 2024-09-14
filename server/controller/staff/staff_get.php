<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Origin: *');

// Database connection
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if a specific staff_id is provided in the query string
    if (isset($_GET['staff_id'])) {
        $staff_id = $_GET['staff_id'];

        // Prepare and execute the SQL statement to fetch data for a specific staff member
        $stmt = $conn->prepare("SELECT * FROM staffs WHERE staff_id = :staff_id");
        $stmt->bindParam(':staff_id', $staff_id, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $staff = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($staff) {
            echo json_encode(['status' => 'success', 'staff' => $staff]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Staff not found']);
        }
    } else {
        // Fetch all staff members if no staff_id is provided
        $stmt = $conn->prepare("SELECT * FROM staffs");
        $stmt->execute();

        $staffs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($staffs) {
            echo json_encode(['status' => 'success', 'staffs' => $staffs]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No staff found']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>
