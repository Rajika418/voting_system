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
       