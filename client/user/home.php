<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page
    header("Location: http://localhost/voting_system/client/");
    exit();
}

$userId = $_SESSION['user_id'] ?? '';
$userName = $_SESSION['user_name'] ?? 'User';
$imageUrl = $_SESSION['image'] ?? 'Profile';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home - Kalaimahal School Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="brand">
                <img src="./assets/images/school.png" alt="Logo">
                <span>KalaiEdu Connect</span>
            </div>
        </div>

        <div class="header-right">
            <div class="header-actions">
                <a href="?page=settings" class="user-profile">
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="User Avatar" class="user-avatar" onerror="this.onerror=null; this.src='http://localhost/voting_system/uploads/default-avatar.png';" />
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($userName); ?></h4>
                    </div>
                </a>
                <!-- Logout icon -->
                <div class="logout-icon">
                    <a href="../../server/controller/logout.php" title="Logout">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navbar -->
    <nav class="navbar">
        <ul class="nav-items">
            <li class="nav-item">
                <a href="?page=home" class="nav-link active">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=elections" class="nav-link">
                    <i class="fas fa-vote-yea"></i>
                    <span class="nav-text">Elections</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=contact" class="nav-link">
                    <i class="fas fa-envelope"></i>
                    <span class="nav-text">Contact Us</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <h1>Welcome to the User Home Page!</h1>
        <p>This is where users can find information related to their profile and activities.</p>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Kalaimahal T.M.V. Hopton. All rights reserved.</p>
    </footer>

    <!-- JavaScript -->
    <script src="./js/script.js"></script>
</body>

</html>
