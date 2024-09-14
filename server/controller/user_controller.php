<?php
// Database configuration
$host = 'localhost';
$dbname = 'voting_system';
$username = 'root';
$password = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the input data
        $input = json_decode(file_get_contents('php://input'), true);
        $user_name = $input['user_name'];
        $user_password = $input['password'];
        $email = $input['email'];
        $role_name = $input['role_name'];

        // Hash the password before storing
        $hashedPassword = password_hash($user_password, PASSWORD_BCRYPT);

        // Determine role_id based on role_name
        switch (strtolower($role_name)) {
            case 'admin':
                $role_id = 1;
                break;
            case 'teacher':
                $role_id = 2;
                break;
            case 'student':
                $role_id = 3;
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid role name']);
                exit();
        }

        // Prepare SQL statement to insert new user
        $stmt = $pdo->prepare("INSERT INTO users (user_name, password, email, role_id) VALUES (:user_name, :password, :email, :role_id)");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role_id', $role_id);

        // Execute the statement
        if($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'User created successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to create user']);
        }
    } else {
        // Invalid request method
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
} catch (PDOException $e) {
    // Handle any errors
    error_log("Database error: " . $e->getMessage(), 3, '/path_to_your_log_folder/error_log.log');
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
