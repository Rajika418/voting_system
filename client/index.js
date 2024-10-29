function showToast(message) {
  var toast = document.getElementById("toast");
  toast.textContent = message;
  toast.className = "show";
  setTimeout(function () {
    toast.className = toast.className.replace("show", "");
  }, 3000);
}

window.onload = function () {
  var urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get("registration") === "success") {
    showToast("Registration successful! Please log in.");
  } else if (urlParams.get("login") === "success") {
    showToast("Login successful!");
  } else if (urlParams.get("error") === "invalid") {
    showToast("Invalid username or password");
  } else if (urlParams.get("error") === "method") {
    showToast("Invalid request method");
  } else if (urlParams.get("error") === "database") {
    showToast("Database error occurred");
  }

  // Get the popup and buttons AFTER the DOM is fully loaded
  const registerPopup = document.getElementById("registerPopup");
  const openRegisterPopup = document.getElementById("openRegisterPopup");
  const closePopup = document.getElementById("closePopup");
  const studentBtn = document.getElementById("studentBtn");
  const teacherBtn = document.getElementById("teacherBtn");

  // Open the popup when the register link is clicked
  openRegisterPopup.addEventListener("click", function (event) {
    event.preventDefault(); // Prevent the link from navigating
    registerPopup.style.display = "flex";
  });

  // Close the popup when close button is clicked
  closePopup.addEventListener("click", function () {
    registerPopup.style.display = "none";
  });

  // Redirect to the student registration page
  studentBtn.addEventListener("click", function () {
    window.location.href =
      "http://localhost/voting_system/client/user/pages/student_register.php"; // Replace with your student registration URL
  });

  // Redirect to the teacher registration page
  teacherBtn.addEventListener("click", function () {
    window.location.href =
      "http://localhost/voting_system/client/user/pages/teacher_register.php"; // Replace with your teacher registration URL
  });

  // Close the popup if clicked outside the content
  window.addEventListener("click", function (event) {
    if (event.target === registerPopup) {
      registerPopup.style.display = "none";
    }
  });

  // Get forgot password elements
  const forgotPasswordLink = document.querySelector(".remember-forgot a");
  const forgotPasswordPopup = document.getElementById("forgotPasswordPopup");
  const forgotPasswordForm = document.getElementById("forgotPasswordForm");

  // Add click event to forgot password link
  forgotPasswordLink.addEventListener("click", function (event) {
    event.preventDefault();
    forgotPasswordPopup.style.display = "flex";
  });

  // Close forgot password popup when clicking outside
  forgotPasswordPopup.addEventListener("click", function (event) {
    if (event.target === forgotPasswordPopup) {
      forgotPasswordPopup.style.display = "none";
    }
  });

  // Close button functionality for forgot password popup
  const forgotPasswordClose = forgotPasswordPopup.querySelector(".popup-close");
  forgotPasswordClose.addEventListener("click", function () {
    forgotPasswordPopup.style.display = "none";
  });

  // Handle forgot password form submission
  forgotPasswordForm.addEventListener("submit", function (event) {
    event.preventDefault();
    const email = this.querySelector('input[name="email"]').value;

    // Here you would typically make an API call to handle password reset
    // For now, we'll just show a success message
    showToast("Password reset link sent to your email!");
    forgotPasswordPopup.style.display = "none";
    this.reset();
  });
};
