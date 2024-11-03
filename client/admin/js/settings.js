// Global variables
const API_BASE_URL = "http://localhost/voting_system/server/controller/admin";

// Function to show success message
function showSuccess(message) {
  const successMessage = document.getElementById("successMessage");
  successMessage.textContent = message;
  successMessage.style.display = "block";
  setTimeout(() => {
    successMessage.style.display = "none";
  }, 3000);
}

// Function to handle API errors
function handleApiError(error) {
  console.error("API Error:", error);
  showSuccess("An error occurred. Please try again.");
}

// Function to change profile image
async function changeImage() {
  const input = document.createElement("input");
  input.type = "file";
  input.accept = "image/*";

  input.onchange = async function (e) {
    const file = e.target.files[0];
    if (file) {
      try {
        const formData = new FormData();
        formData.append("image", file);

        const response = await fetch(
          `${API_BASE_URL}/user_update.php/${userId}`,
          {
            method: "POST",
            body: formData,
          }
        );

        const data = await response.json();

        if (data.status === "success") {
          const reader = new FileReader();
          reader.onload = function (event) {
            document.getElementById("profileImage").src = event.target.result;
          };
          reader.readAsDataURL(file);
          showSuccess("Profile image updated successfully!");
        } else {
          showSuccess(data.message || "Failed to update image");
        }
      } catch (error) {
        handleApiError(error);
      }
    }
  };

  input.click();
}

// Function to delete profile image
async function deleteImage() {
  try {
    const response = await fetch(`${API_BASE_URL}/delete_image.php/${userId}`, {
      method: "DELETE",
    });
    const data = await response.json();

    if (data.status === "success") {
      document.getElementById("profileImage").src =
        "http://localhost/voting_system/uploads/default-avatar.png";
      showSuccess("Profile image removed successfully!");
    } else {
      showSuccess(data.message || "Failed to delete image");
    }
  } catch (error) {
    handleApiError(error);
  }
}

// Function to update profile details
async function updateProfile() {
  try {
    const formData = new FormData();
    formData.append("user_name", document.getElementById("fullName").value);
    formData.append("email", document.getElementById("email").value);

    const response = await fetch(`${API_BASE_URL}/user_update.php/${userId}`, {
      method: "POST",
      body: formData,
    });

    const data = await response.json();

    if (data.status === "success") {
      // Update display name and email
      document.getElementById("userName").textContent =
        document.getElementById("fullName").value;
      document.getElementById("userEmail").textContent =
        document.getElementById("email").value;
      showSuccess("Profile details updated successfully!");
    } else {
      showSuccess(data.message || "Failed to update profile");
    }
  } catch (error) {
    handleApiError(error);
  }
}

// Function to show password modal
function showChangePasswordModal() {
  document.getElementById("passwordModal").classList.add("active");
  document.body.classList.add("modal-active"); // Disable scrolling
}

// Function to close modal
function closeModal() {
  document.getElementById("passwordModal").classList.remove("active");
  document.body.classList.remove("modal-active"); // Enable scrolling
  document.getElementById("passwordForm").reset();
}


// Function to change password
async function changePassword() {
  const currentPassword = document.getElementById("currentPassword").value;
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (!currentPassword || !newPassword || !confirmPassword) {
    showSuccess("Please fill in all password fields");
    return;
  }

  if (newPassword !== confirmPassword) {
    showSuccess("New passwords do not match");
    return;
  }

  try {
    const formData = new FormData();
    formData.append("current_password", currentPassword);
    formData.append("new_password", newPassword);

    const response = await fetch(
      `${API_BASE_URL}/change_password.php/${userId}`,
      {
        method: "POST",
        body: formData,
      }
    );

    const data = await response.json();

    if (data.status === "success") {
      closeModal();
      showSuccess("Password updated successfully!");
    } else {
      showSuccess(data.message || "Failed to update password");
    }
  } catch (error) {
    handleApiError(error);
  }
}

// Function to get user details from the API
async function getUserDetails() {
  try {
    const response = await fetch(`${API_BASE_URL}/user_get.php/${userId}`);
    const data = await response.json();

    if (data.status === "success") {
      // Populate user details
      document.getElementById("fullName").value = data.data.user_name;
      document.getElementById("email").value = data.data.email;
      document.getElementById("profileImage").src =
        data.data.image ||
        "http://localhost/voting_system/uploads/default-avatar.png";
      document.getElementById("userName").textContent = data.data.user_name;
      document.getElementById("userEmail").textContent = data.data.email;
    } else {
      showSuccess(data.message || "Failed to fetch user details");
    }
  } catch (error) {
    handleApiError(error);
  }
}

// Initialize the page
window.onload = getUserDetails;
