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
$roleName = $_SESSION['role_name'] ?? 'Role';
$imageUrl = $_SESSION['image'] ?? 'Profile';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalaimahal School Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css" />
    <?php
    // Default to 'dashboard' if no page parameter is set
    $page = $_GET['page'] ?? 'dashboard';

    // Dynamic CSS inclusion based on the page
    switch ($page) {
        case 'dashboard':
            echo '<link rel="stylesheet" href="./assets/css/dashboard.css" />';
            break;
        case 'teachers':
            echo '<link rel="stylesheet" href="./assets/css/teacherlist.css" />';
            break;
        case 'students':
            echo '<link rel="stylesheet" href="./assets/css/studentlist.css" />';
            break;
        case 'election':
            echo '<link rel="stylesheet" href="./assets/css/election.css" />';
            break;
        case 'result':
            echo '<link rel="stylesheet" href="./assets/css/result.css" />';
            break;
        case 'settings':
            echo '<link rel="stylesheet" href="./assets/css/settings.css" />';
            break;
        default:
            echo '<link rel="stylesheet" href="./assets/css/dashboard.css" />';
            break;
    }
    ?>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="toggle-sidebar" id="toggleSidebar">
                <i class="fas fa-bars"></i>
            </button>
            <div class="brand">
                <img src="./assets/images/school.png" alt="Logo">
                <span>KalaiEdu Connect</span>
            </div>
        </div>

        <div class="header-right">
            <div class="header-actions">
                <a href="?page=settings" class="user-profile">
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="User Avatar" class="user-avatar" onerror="this.onerror=null; this.src='http://localhost/voting_system/uploads/default-avatar.png;'" />
                    <div class="user-info">
                        <h4><?php echo htmlspecialchars($userName); ?></h4>
                        <p><?php echo htmlspecialchars($roleName); ?></p>
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

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="nav-section">
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link <?= $page === 'dashboard' ? 'active' : '' ?>">
                        <i class="fas fa-home-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=teachers" class="nav-link <?= $page === 'teachers' ? 'active' : '' ?>">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="nav-text">Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=students" class="nav-link <?= $page === 'students' ? 'active' : '' ?>">
                        <i class="fas fa-user-graduate"></i>
                        <span class="nav-text">Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=election" class="nav-link <?= $page === 'election' ? 'active' : '' ?>">
                        <i class="fas fa-vote-yea"></i>
                        <span class="nav-text">Election</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=result" class="nav-link <?= $page === 'result' ? 'active' : '' ?>">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">Result</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link <?= $page === 'settings' ? 'active' : '' ?>">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <?php
        // Dynamic PHP inclusion based on the page
        switch ($page) {
            case 'dashboard':
                include 'dashboard.php';
                break;
            case 'teachers':
                include './modules/teachers.php';
                break;
            case 'students':
                include './modules/students.php';
                break;
            case 'election':
                include './modules/election.php';
                break;
            case 'result':
                include './modules/results.php';
                break;
            case 'settings':
                include './modules/settings.php';
                break;
            default:
                echo "<p>Page not found.</p>";
                break;
        }
        ?>
    </main>

    <!-- Footer -->
    <footer class="footer" id="footer">
        <div class="footer-left">
            <p>&copy; 2024 Kalaimahal T.M.V. Hopton. All rights reserved.</p>
        </div>
        <div class="footer-right">
            <a href="#" class="footer-link">Privacy Policy</a>
            <a href="#" class="footer-link">Terms of Service</a>
            <a href="#" class="footer-link">Contact Support</a>
        </div>
    </footer>

    <!-- Dynamic JS inclusion based on the page -->
    <script src="./js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <?php
    // Dynamic JavaScript inclusion based on the page
    switch ($page) {
        case 'dashboard':
            echo '<script src="./js/dashboard.js"></script>';
            break;
        case 'teachers':
            echo '<script src="./js/teacherlist.js"></script>';
            echo '<script src="./js/classAssignPopup.js"></script>';
            break;
        case 'students':
            echo '<script src="./js/studentlist.js"></script>';
            break;
        case 'election':
            echo '<script src="./js/election.js" defer></script>';
            break;
        case 'result':
            echo '<script src="./js/tab.js" defer></script>';
            echo '<script src="./js/popup.js" defer></script>';
            echo '<script src="./js/admission_popup.js" defer></script>';
            echo '<script src="./js/add_result.js" defer></script>';
            break;
        case 'settings':
            echo '<script>const userId = ' . json_encode($_SESSION['user_id']) . ';</script>';
            echo '<script src="./js/settings.js"></script>';
            break;
        default:
            echo '<script src="./js/dashboard.js"></script>';
            break;
    }
    ?>
</body>

</html>