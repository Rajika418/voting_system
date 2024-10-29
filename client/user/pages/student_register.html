<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student Registration</title>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Get all required elements
        const guardianCheckbox = document.getElementById("guardian_checkbox");
        const fatherNameGroup = document.querySelector(".father-name-group");
        const guardianFields = document.querySelector(".guardian-fields");
        const fatherNameInput = document.getElementById("father_name");
        const guardianInput = document.getElementById("guardian");

        function toggleGuardianField() {
          if (guardianCheckbox.checked) {
            // Hide father's name input only, keep checkbox visible
            fatherNameGroup.style.display = "none";
            guardianFields.style.display = "flex";
            fatherNameInput.required = false;
            guardianInput.required = true;

            // Clear father's name value
            fatherNameInput.value = "";
          } else {
            // Show father's name input
            fatherNameGroup.style.display = "flex";
            guardianFields.style.display = "none";
            fatherNameInput.required = true;
            guardianInput.required = false;

            // Clear guardian value
            guardianInput.value = "";
          }
        }

        // Initialize the form state
        toggleGuardianField();

        // Add event listener to checkbox
        guardianCheckbox.addEventListener("change", toggleGuardianField);

        // Single form submission handler
        document
          .querySelector("form")
          .addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Validate parent/guardian fields
            if (guardianCheckbox.checked && !guardianInput.value) {
              alert("Please enter Guardian Name");
              return;
            } else if (!guardianCheckbox.checked && !fatherNameInput.value) {
              alert("Please enter Father's Name");
              return;
            }

            // Log form data if needed
            console.log("Father's Name:", fatherNameInput.value);
            console.log("Use Guardian:", guardianCheckbox.checked);
            if (guardianCheckbox.checked) {
              console.log("Guardian Name:", guardianInput.value);
            }

            // Submit the form
            this.submit();
          });

        // Function to populate the grade dropdown
        function populateGradeDropdown() {
          const dropdown = document.getElementById("grade_name");

          fetch(
            "http://localhost/voting_system/server/controller/grade/grade_get.php"
          )
            .then((response) => response.json())
            .then((data) => {
              if (data.status === "success") {
                dropdown.innerHTML = "";
                const placeholderOption = document.createElement("option");
                placeholderOption.value = "";
                placeholderOption.text = "Select a grade";
                dropdown.appendChild(placeholderOption);

                data.grades.forEach((grade) => {
                  const option = document.createElement("option");
                  option.value = grade.grade_id;
                  option.text = grade.grade_name;
                  dropdown.appendChild(option);
                });
              } else {
                console.error("Failed to retrieve grades:", data.message);
              }
            })
            .catch((error) => console.error("Error fetching grades:", error));
        }

        // Populate the grade dropdown on page load
        populateGradeDropdown();
      });
    </script>
    <link rel="stylesheet" href="../css/student_register.css" />
  </head>
  <body>
    <header>
      <img src="../../admin/assets/images/school.png" alt="School Logo" />
      <h2>Kalaimahal TMV.Hopton</h2>
      <h1>School Management System</h1>
    </header>

    <div class="container">
      <div class="form-wrapper">
        <h1>Student Registration</h1>
        <form
          action="../../../server/controller/student/student_post.php"
          method="POST"
          enctype="multipart/form-data"
        >
          <div class="form-row">
            <div class="form-group">
              <label for="student_name">Student Name</label>
              <input
                type="text"
                id="student_name"
                name="student_name"
                required
              />
            </div>

            <div class="form-group">
              <label for="user_name">Username</label>
              <input type="text" id="user_name" name="user_name" required />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="address">Address</label>
              <input type="text" id="address" name="address" required />
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="contact_number">Contact Number</label>
              <input
                type="tel"
                id="contact_number"
                name="contact_number"
                required
              />
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" required />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="registration_number">Registration Number</label>
              <input
                type="text"
                id="registration_number"
                name="registration_number"
                required
              />
            </div>

            <div class="form-group">
              <label for="grade_name">Grade Name (if applicable):</label>
              <select id="grade_name" name="grade_id">
                <!-- Options will be populated here -->
              </select>
            </div>
          </div>

          <!-- Father's Name Field -->
          <div class="form-row parent-fields">
            <div class="form-group father-name-group">
              <label for="father_name">Father's Name with Initial</label>
              <input type="text" id="father_name" name="father_name" />
            </div>

            <div class="form-group checkbox-group">
              <label class="checkbox-wrapper">
                <input
                  type="checkbox"
                  id="guardian_checkbox"
                  name="use_guardian"
                />
                <span class="checkbox-label"
                  >Use Guardian instead of Father</span
                >
              </label>
            </div>
          </div>

          <div class="form-row guardian-fields" style="display: none">
            <div class="form-group">
              <label for="guardian">Guardian Name</label>
              <input type="text" id="guardian" name="guardian" />
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="join_date">Join Date</label>
              <input type="date" id="join_date" name="join_date" required />
            </div>

            <div class="form-group">
              <label for="image">Upload Image (optional)</label>
              <input type="file" id="image" name="image" accept="image/*" />
            </div>
          </div>

          <div class="form-group-submit">
            <button type="submit">Register</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
