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
        ?>
                <div class="home-content">
                    <h1>Welcome to the User Home Page!</h1>
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <i class="fas fa-vote-yea"></i>
                            <h3>Current Elections</h3>
                            <p>View ongoing elections and cast your vote</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-chart-line"></i>
                            <h3>Exam Results</h3>
                            <p>Check the latest exam results</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-chalkboard-teacher"></i>
                            <h3>Teacher Directory</h3>
                            <p>View information about our teachers</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-user-cog"></i>
                            <h3>My Profile</h3>
                            <p>Manage your account and settings</p>
                        </div>
                    </div>
                </div>
                <?php
                break;

                case 'elections':
                    $electionPage = $_GET['view'] ?? 'list';
        
                    switch ($electionPage) {
                        case 'current':
                            // Include the election detail page we created
                            include 'pages/election.php';
                            break;
        
                        case 'apply-nomination':
                            // Show the application for nomination page
                            ?>
                            <div class="elections-content">
                                <h1>Apply for Nomination</h1>
                                <div class="election-card">
                                    <div class="card-header">
                                        <i class="fas fa-vote-yea"></i>
                                        <h3>Upcoming Election</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Election Date: June 15, 2024</p>
                                        <p>Nomination Period: May 1 - May 15, 2024</p>
                                        <div class="election-info">
                                            <span class="status upcoming">Nomination Open</span>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn-primary">Apply for Nomination</button>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
        
                        case 'results':
                            // Show the election results page
                            ?>
                            <div class="elections-content">
                                <h1>Election Results</h1>
                                <div class="election-card">
                                    <div class="card-header">
                                        <i class="fas fa-chart-line"></i>
                                        <h3>Latest Election Results</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>Election Date: June 1, 2024</p>
                                        <div class="election-info">
                                            <span class="status active">Completed</span>
                                        </div>
                                        <!-- Add election result details here -->
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
        
                        default:
                            // Show the elections listing page
                            ?>
                            <div class="elections-content">
                                <h1>Elections</h1>
                                <div class="elections-list">
                                    <div class="election-card clickable" onclick="window.location.href='?page=elections&view=current'">
                                        <div class="card-header">
                                            <i class="fas fa-vote-yea"></i>
                                            <h3>Current Elections</h3>
                                        </div>
                                        <div class="card-body">
                                            <p>View and participate in ongoing elections</p>
                                            <div class="election-info">
                                                <span class="status active">Active</span>
                                                <span class="deadline">Ends in: 3 days</span>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn-primary">Participate Now</button>
                                        </div>
                                    </div>
                                    <div class="election-card clickable" onclick="window.location.href='?page=elections&view=apply-nomination'">
                                        <div class="card-header">
                                            <i class="fas fa-edit"></i>
                                            <h3>Apply for Nomination</h3>
                                        </div>
                                        <div class="card-body">
                                            <p>Submit your application to run for election</p>
                                            <div class="election-info">
                                                <span class="status upcoming">Nomination Open</span>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn-primary">Apply Now</button>
                                        </div>
                                    </div>
                                    <div class="election-card clickable" onclick="window.location.href='?page=elections&view=results'">
                                        <div class="card-header">
                                            <i class="fas fa-chart-line"></i>
                                            <h3>Election Results</h3>
                                        </div>
                                        <div class="card-body">
                                            <p>View the latest election results</p>
                                            <div class="election-info">
                                                <span class="status active">Available</span>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn-primary">View Results</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            break;
                    }
                    break;

  case 'results':
            // Show exam results page
            ?>
            <div class="results-content">
                <h1>Exam Results</h1>
                <div class="results-year-tabs">
                    <button class="btn-primary active">2024</button>
                    <button class="btn-primary">2021</button>
                    <button class="btn-primary">2020</button>
                </div>
                <div class="results-cards">
                    <div class="result-card">
                        <h3>A/L Results</h3>
                        <!-- Add A/L results content here -->
                    </div>
                    <div class="result-card">
                        <h3>O/L Results</h3>
                        <!-- Add O/L results content here -->
                    </div>
                </div>
            </div>
            <?php
            break;

        case 'teachers':
            // Show teacher directory page
            ?>
            <div class="teachers-content">
                <h1>Teacher Directory</h1>
                <div class="teacher-cards">
                    <div class="teacher-card">
                        <div class="teacher-image">
                            <img src="https://via.placeholder.com/150" alt="Teacher Image">
                        </div>
                        <div class="teacher-info">
                            <h3>John Doe</h3>
                            <p>Mathematics</p>
                            <p>Years of Experience: 10</p>
                        </div>
                    </div>
                    <div class="teacher-card">
                        <div class="teacher-image">
                            <img src="https://via.placeholder.com/150" alt="Teacher Image">
                        </div>
                        <div class="teacher-info">
                            <h3>Jane Smith</h3>
                            <p>Biology</p>
                            <p>Years of Experience: 8</p>
                        </div>
                    </div>
                    <!-- Add more teacher cards here -->
                </div>
            </div>
            <?php
            break;

        case 'profile':
            // Show user profile page
            ?>
            <div class="profile-content">
                <h1>My Profile</h1>
                <div class="profile-info">
                    <div class="profile-image">
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="User Avatar" class="user-avatar"
                            onerror="this.onerror=null; this.src='http://localhost/voting_system/uploads/default-avatar.png';" />
                    </div>
                    <div class="profile-details">
                        <h3><?php echo htmlspecialchars($userName); ?></h3>
                        <p>Email: user@example.com</p>
                        <p>Phone: 123-456-7890</p>
                    </div>
                </div>
                <div class="profile-form">
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="profile-image">Update Profile Image</label>
                            <input type="file" id="profile-image" name="profile_image" accept="image/*">
                        </div>
                        <div class="form-group">
                            <label for="username">Update Username</label>
                            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userName); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Change Password</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn-submit">Save Changes</button>
                    </form>
                </div>
            </div>
        <?php
            break;


            default:
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

    <!-- JavaScript -->
    <script src="./js/script.js"></script>
    <script src="./js/election.js"></script>
</body>

</html>