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
        // Get the input data from the form submission
        $user_name = $_POST['user_name'];
        $user_password = $_POST['password'];

        // Prepare and execute the SQL statement to fetch user by username
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_name = :user_name");
        $stmt->bindParam(':user_name', $user_name);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists and the password is correct
        if ($user && password_verify($user_password, $user['password'])) {
            // Password matches, check role_id and set redirect URL
            $redirect_url = ($user['role_id'] == "1") ? "../../voting_system/client/admin/" : "../../voting_system/client/home/home.html";
            
            // Start session and set success flag
            session_start();
            $_SESSION['login_success'] = true;
            
            // Encode the redirect URL
            $encoded_redirect = urlencode($redirect_url);
            
            // Redirect to login page with success parameter and encoded redirect URL
            header("Location: ../../client/login.html?login=success&redirect=$encoded_redirect");
            exit();
        } else {
            // Invalid credentials, redirect to login page with error parameter
            header("Location: ../../client/login.html?error=invalid");
            exit();
        }
    } else {
        // Invalid request method, redirect to login page with error parameter
        header("Location: ../../client/login.html?error=method");
        exit();
    }
} catch (PDOException $e) {
    // Handle any errors
    error_log("Database error: " . $e->getMessage(), 3, '../log/error_log.log');
    header("Location: ../../client/login.html?error=database");
    exit();
}
?>