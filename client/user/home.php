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
                <a href="?page=contact" class="nav-link <?php echo $currentPage === 'contact' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope"></i>
                    <span class="nav-text">Contact Us</span>
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
                    <p>This is where users can find information related to their profile and activities.</p>
                    <div class="dashboard-stats">
                        <div class="stat-card">
                            <i class="fas fa-poll"></i>
                            <h3>Active Elections</h3>
                            <p>View ongoing elections and cast your vote</p>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-history"></i>
                            <h3>Voting History</h3>
                            <p>Check your past voting activities</p>
                        </div>
                    </div>
                </div>
                <?php
                break;

            case 'elections':
                // Check if there's a sub-page parameter
                $electionPage = $_GET['view'] ?? 'list';

                switch ($electionPage) {
                    case 'current':
                        // Include the election detail page we created
                        include 'pages/election.php';
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

                                <div class="election-card">
                                    <div class="card-header">
                                        <i class="fas fa-calendar"></i>
                                        <h3>Upcoming Elections</h3>
                                    </div>
                                    <div class="card-body">
                                        <p>See what elections are scheduled</p>
                                        <div class="election-info">
                                            <span class="status upcoming">Starting Soon</span>
                                            <span class="deadline">Starts in: 5 days</span>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn-secondary" disabled>Coming Soon</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                        break;
                }
                break;

            case 'contact':
                ?>
                <div class="contact-content">
                    <h1>Contact Us</h1>
                    <div class="contact-form">
                        <form action="process_contact.php" method="POST">
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" id="subject" name="subject" required>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn-submit">Send Message</button>
                        </form>
                    </div>
                </div>
            <?php
                break;

            case 'settings':
            ?>
                <div class="settings-content">
                    <h1>User Settings</h1>
                    <div class="settings-form">
                        <form action="update_settings.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="profile-image">Profile Image</label>
                                <input type="file" id="profile-image" name="profile_image" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($userName); ?>" required>
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