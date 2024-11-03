<div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); justify-content: center; align-items: center; z-index: 1000;">
  <div class="loading-spinner" style="width: 50px; height: 50px; border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
</div>

<div class="profile-content">
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
                    <label class="form-label" for="fullName">User Name</label>
                    <input type="text" id="fullName" class="form-input" value="John Doe">
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" class="form-input" value="john.doe@example.com">
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

                <button type="button" class="pwd_btn btn-primary" onclick="changePassword()">Update Password</button>
            </form>
        </div>
    </div>