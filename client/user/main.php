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

// Get the current page from URL parameter, default to 'home'
$currentPage = $_GET['page'] ?? 'home';
$view = $_GET['view'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home - Kalaimahal School Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/election.css" />
    <link rel="stylesheet" href="./css/nomination.css" />
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <div class="brand">
                <img src="../admin/assets/images/school.png" alt="Logo">
                <span>KalaiEdu Connect</span>
            </div>
        </div>

        <div class="header-right">
            <div class="header-actions">
                <a href="?page=settings" class="user-profile">
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="User Avatar" class="user-avatar"
                        onerror="this.onerror=null; this.src='http://localhost/voting_system/uploads/default-avatar.png';" />
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
                <a href="?page=home" class="nav-link <?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=elections" class="nav-link <?php echo $currentPage === 'elections' ? 'active' : ''; ?>">
                    <i class="fas fa-vote-yea"></i>
                    <span class="nav-text">Elections</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=results" class="nav-link <?php echo $currentPage === 'results' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span class="nav-text">Exam Results</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=teachers" class="nav-link <?php echo $currentPage === 'teachers' ? 'active' : ''; ?>">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span class="nav-text">Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?page=profile" class="nav-link <?php echo $currentPage === 'profile' ? 'active' : ''; ?>">
                    <i class="fas fa-user-cog"></i>
                    <span class="nav-text">Profile</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        switch ($currentPage) {
            case 'home':
                include './pages/home.php';
                break;
            case 'elections':
                 // Nested switch for different views in the elections page
                 switch ($view) {
                    case 'current':
                        include './pages/elections/current_election.php'; // Page for current elections
                        break;
                    case 'apply-nomination':
                        include './pages/elections/apply_nomination.php'; // Page for applying nomination
                        break;
                    case 'results':
                        include './pages/elections/results.php'; // Page for election results
                        break;
                    default:
                        include './pages/elections/election_dash.php'; // Default view for elections dashboard
                        break;
                }
                break;
            case 'results':
                    include './pages/result.php';
                    break;
            case 'teachers':
                include './pages/teacher.php';
                break;
            case 'profile':
                include './pages/profile.php';
                break;
           
            default:
                echo "<p>Page not found.</p>";
                break;
        ?>
           
                <div class="error-content">
                    <h1>404 - Page Not Found</h1>
                    <p>The requested page could not be found.</p>
                    <a href="?page=home" class="btn-back">Back to Home</a>
                </div>
        <?php
                break;
        }
        ?>
    </main>


    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 Kalaimahal T.M.V. Hopton. All rights reserved.</p>
    </footer>

 

    <script src="./js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
    <?php
    // Dynamic JavaScript inclusion based on the page
    switch ($currentPage) {
       
        case 'elections':
             // Nested switch for different views in the elections page
             switch ($view) {
                case 'current':
                    echo '<script src="./js/"></script>';// Page for current elections
                    break;
                case 'apply-nomination':
                    echo '<script src="./js/nomination.js"></script>'; ; // Page for applying nomination
                    break;
                case 'results':
                   // Page for election results
                    break;
                default:
                echo '<script src="./js/election.js"></script>';// Default view for elections dashboard
                    break;
            }
        
            echo '<script src="./js/script.js"></script>';
            break;
    }
    ?>
</body>

</html>