<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile Settings</title>
    <link rel="stylesheet" href="./assets/css/settings.css" />
</head>
<body>
    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-image-container">
                    <img src="https://via.placeholder.com/150" alt="Profile" class="profile-image" id="profileImage">
                    <div class="image-actions">
                        <button class="image-action-btn" onclick="changeImage()">
                            <i class="fas fa-camera"></i>
                        </button>
                        <button class="image-action-btn delete" onclick="deleteImage()">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="profile-info">
                    <h2 class="profile-name" id="userName">John Doe</h2>
                    <p class="profile-email" id="userEmail">john.doe@example.com</p>
                </div>
            </div>

            <div class="success-message" id="successMessage"></div>

            <form id="profileForm">
                <div class="form-group">
                    <label class="form-label" for="fullName">Full Name</label>
                    <input type="text" id="fullName" class="form-input" value="John Doe">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" class="form-input" value="john.doe@example.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">Phone</label>
                    <input type="tel" id="phone" class="form-input" value="+1 234 567 890">
                </div>

                <div class="form-group">
                    <label class="form-label" for="location">Location</label>
                    <input type="text" id="location" class="form-input" value="New York, USA">
                </div>

                <div class="button-group">
                    <button type="button" class="btn btn-primary" onclick="updateProfile()">Update Details</button>
                    <button type="button" class="btn btn-outline" onclick="showChangePasswordModal()">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal" id="passwordModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Change Password</h3>
                <button class="close-modal" onclick="closeModal()">&times;</button>
            </div>
            <form id="passwordForm">
                <div class="form-group">
                    <label class="form-label" for="currentPassword">Current Password</label>
                    <input type="password" id="currentPassword" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" for="newPassword">New Password</label>
                    <input type="password" id="newPassword" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                    <input type="password" id="confirmPassword" class="form-input">
                </div>

                <button type="button" class="btn btn-primary" onclick="changePassword()">Update Password</button>
            </form>
        </div>
    </div>

    <script src="./js/settings.js"></script>
    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>

</html>