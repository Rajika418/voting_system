// Disable form fields by default
const admissionFields = document.getElementById("admissionFields");
admissionFields.disabled = true;

let selectedYear = null; // To store the selected year (11 for O/L, 13 for A/L)

// Handle the O/L Admission button click
document.getElementById("olButton").addEventListener("click", function () {
  selectedYear = 11; // Set year to 11 for O/L
  populateStudentNames(); // Populate student names for O/L
  admissionFields.disabled = false; // Enable form fields
  document.getElementById("examName").value =
    "General Certificate of Education - Ordinary Level (G.C.E(O/L))"; // Pre-fill the exam name as O/L
});

// Handle the A/L Admission button click
document.getElementById("alButton").addEventListener("click", function () {
  selectedYear = 13; // Set year to 13 for A/L
  populateStudentNames(); // Populate student names for A/L
  admissionFields.disabled = false; // Enable form fields
  document.getElementById("examName").value =
    "General Certificate of Education - Advanced Level (G.C.E(A/L))"; // Pre-fill the exam name as A/L
});

// Function to populate the student name dropdown from the GET API
function populateStudentNames() {
  if (selectedYear === null) {
    console.error("Year is not selected");
    return;
  }

  // Send request with the selected year as a parameter
  axios
    .get(
      `http://localhost/voting_system/server/controller/admission/student_get.php?action=read&year=${selectedYear}`
    )
    .then((response) => {
      if (response.data.status === "success") {
        const students = response.data.data;
        console.log("hi", response.data);
        const studentNameSelect = document.getElementById("studentName");
        studentNameSelect.innerHTML =
          '<option value="">Select Student</option>'; // Reset options

        students.forEach((student) => {
          // Add each student as an option in the dropdown
          const option = document.createElement("option");
          option.value = student.student_id; // Store student_id as value
          option.textContent = student.student_name; // Display student_name
          studentNameSelect.appendChild(option);
        });

        // Add event listener for student selection
        studentNameSelect.addEventListener("change", (event) => {
          const selectedStudentId = event.target.value;
          const selectedStudent = students.find(
            (student) => student.student_id === selectedStudentId
          );
          console.log("selectedStudent", selectedStudent);

          if (selectedStudent) {
            const guardianInfo = document.getElementById("guardianInfo");

            console.log("guardian", guardianInfo);

            if (
              selectedStudent.father_name &&
              selectedStudent.father_name !== "null"
            ) {
              guardianInfo.textContent = `Father Name: ${selectedStudent.father_name}`;
            } else if (
              selectedStudent.guardian &&
              selectedStudent.guardian !== "null"
            ) {
              guardianInfo.textContent = `Guardian: ${selectedStudent.guardian}`;
            } else {
              guardianInfo.textContent = "";
            }
          }
        });
      } else {
        console.error("Failed to fetch students");
      }
    })
    .catch((error) => {
      console.error("Error fetching students:", error);
    });
}

// Function to submit the form data via POST API
function submitAdmissionForm(event) {
  event.preventDefault(); // Prevent form default submit

  const studentId = document.getElementById("studentName").value;
  const nic = document.getElementById("nic").value;
  const year = document.getElementById("year").value;
  const examName = document.getElementById("examName").value;
  const indexNo = document.getElementById("indexNo1").value;

  if (studentId && nic && year && examName && indexNo) {
    // Construct the data payload
    const postData = {
      student_id: studentId,
      nic: nic,
      year: year,
      exam_name: examName,
      index_no: indexNo,
    };

    // Send POST request
    axios
      .post(
        "http://localhost/voting_system/server/controller/admission/ol_admission_post.php",
        postData
      )
      .then((response) => {
        if (response.data.status === "success") {
          alert("Data successfully submitted");
        } else {
          alert(`Submission failed: ${response.data.message}`);
        }
      })
      .catch((error) => {
        console.error("Error submitting data:", error);
        alert("Error submitting data.");
      });
  } else {
    alert("Please fill all the required fields");
  }
}

// Add event listeners
document
  .getElementById("admissionForm")
  .addEventListener("submit", submitAdmissionForm);

// Call function to populate student names on page load
populateStudentNames();
