document.addEventListener("DOMContentLoaded", () => {
  const gradeSelect = document.getElementById("gradeSelect");
  const gradeName = document.getElementById("gradeName");
  const studentCount = document.getElementById("studentCount");
  const studentList = document.getElementById("studentList");

  // Fetch grades to populate the dropdown
  fetch(
    "http://localhost/voting_system/server/controller/student/get_grades.php"
  )
    .then((response) => response.json())
    .then((data) => {
      data.forEach((grade) => {
        const option = document.createElement("option");
        option.value = grade.grade_id;
        option.textContent = grade.grade_name;
        gradeSelect.appendChild(option);
      });
    });

  // Update students when grade selection changes
  gradeSelect.addEventListener("change", () => {
    const gradeId = gradeSelect.value;
    if (!gradeId) return;

    fetch(
      `http://localhost/voting_system/server/controller/student/grade_count_get.php?grade_id=${gradeId}`
    )
      .then((response) => response.json())
      .then((data) => {
        if (data.error) {
          console.error(data.error);
          alert(data.error);
          return;
        }

        gradeName.textContent = data.grade_name;
        studentCount.textContent = data.count;
        studentList.innerHTML = "";

        data.students.forEach((student, index) => {
          const li = document.createElement("li");
          li.className = "student-card";

          const imageUrl =
            student.image_url ||
            "http://localhost/voting_system/uploads/default-avatar.png";

          li.innerHTML = `
            <img class="student-image" src="${imageUrl}" alt="${
            student.student_name
          }" 
                 onerror="this.src='http://localhost/voting_system/uploads/default-avatar.png'">
            <div class="student-info">
            <p class="student-id">ID: ${index + 1}</p>
              <h3 class="student-name">${student.student_name}</h3>
            </div>
          `;

          studentList.appendChild(li);
        });
      })
      .catch((error) => {
        console.error("Error fetching student data:", error);
        // alert("Failed to fetch student data. Please try again later.");
      });
  });
});
