<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalaimahal School Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
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
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search...">
            </div>

            <div class="header-actions">
                <div class="user-profile">
                    <div class="user-avatar">AD</div>
                    <div class="user-info">
                        <h4>Raji</h4>
                        <p>Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="nav-section">
            <ul class="nav-items">
                <li class="nav-item">
                    <a href="?page=dashboard" class="nav-link active">
                        <i class="fas fa-home-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=teachers" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span class="nav-text">Teachers</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=students" class="nav-link">
                        <i class="fas fa-user-graduate"></i>
                        <span class="nav-text">Students</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=election" class="nav-link">
                        <i class="fas fa-vote-yea"></i>
                        <span class="nav-text">Election</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=result" class="nav-link">
                        <i class="fas fa-clipboard-list"></i>
                        <span class="nav-text">Result</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="?page=settings" class="nav-link">
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
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
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
        } else {
            include 'dashboard.php';
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

    <script src="./assets/js/script.js"></script>
</body>

</html>
