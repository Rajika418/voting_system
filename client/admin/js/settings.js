// Function to show success message
function showSuccess(message) {
  const successMessage = document.getElementById("successMessage");
  successMessage.textContent = message;
  successMessage.style.display = "block";
  setTimeout(() => {
    successMessage.style.display = "none";
  }, 3000);
}

// Function to change profile image
function changeImage() {
  const input = document.createElement("input");
  input.type = "file";
  input.accept = "image/*";
  input.onchange = function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (event) {
        document.getElementById("profileImage").src = event.target.result;
        showSuccess("Profile image updated successfully!");
      };
      reader.readAsDataURL(file);
    }
  };
  input.click();
}

// Function to delete profile image
function deleteImage() {
  document.getElementById("profileImage").src =
    "https://via.placeholder.com/150";
  showSuccess("Profile image removed!");
}

// Function to update profile details
function updateProfile() {
  const name = document.getElementById("fullName").value;
  const email = document.getElementById("email").value;

  // Update display name and email
  document.getElementById("userName").textContent = name;
  document.getElementById("userEmail").textContent = email;

  showSuccess("Profile details updated successfully!");
}

// Function to show password modal
function showChangePasswordModal() {
  document.getElementById("passwordModal").classList.add("active");
}

// Function to close modal
function closeModal() {
  document.getElementById("passwordModal").classList.remove("active");
  document.getElementById("passwordForm").reset();
}

// Function to change password
function changePassword() {
  const currentPassword = document.getElementById("currentPassword").value;
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (!currentPassword || !newPassword || !confirmPassword) {
    alert("Please fill in all password fields");
    return;
  }

  if (newPassword !== confirmPassword) {
    alert("New passwords do not match");
    return;
  }

  // Here you would typically make an API call to update the password
  closeModal();
  showSuccess("Password updated successfully!");
}
