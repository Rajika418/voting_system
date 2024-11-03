let currentPage = 1;
let limit = 10;
let sortDirection = "asc";
let sortColumn = "grade_name";
let searchQuery = "";

fetchStudents = async function fetchStudents() {
  const searchElement = document.getElementById("search");
  searchQuery = searchElement ? searchElement.value : "";

  const url = `http://localhost/voting_system/server/controller/student/student_get.php?page=${currentPage}&limit=${limit}&search=${searchQuery}&sort_by=${sortColumn}&order=${sortDirection}`;

  try {
    const response = await axios.get(url);
    console.log(response.data);

    renderTable(response.data);
  } catch (error) {
    console.error("Error fetching student data:", error);
  }
};

function renderTable(students) {
  console.log(students);

  const tbody = document.querySelector("#studentTable tbody");

  tbody.innerHTML = "";

  students.forEach((student, index) => {
    const tr = document.createElement("tr");

    tr.innerHTML = `
                <td>${index + 1 + (currentPage - 1) * limit}</td>
                <td><img src="${
                  student.image ? student.image : ""
                }" alt="Profile Image" width="50" height="50"></td>
                <td>${student.registration_number}</td>
                <td>${student.student_name}</td>
                <td>${student.grade_name}</td>
                <td>${student.guardian}</td>
                <td>${student.father_name}</td>
                <td>${student.address}</td>
                <td>${student.contact_number}</td>
                <td>${student.email}</td>
                <td>${student.join_date}</td>
                <td>${student.leave_date || ""}</td>
                <td class="actions">
                    <button class="edit-btn" onclick="editStudent(${
                      student.student_id
                    }, this)">Edit</button>
                    <button class="delete-btn" onclick="deleteStudent(${
                      student.student_id
                    })">Delete</button>
                </td>
            `;

    tbody.appendChild(tr);
  });

  document.getElementById("pageInfo").innerText = `Page ${currentPage}`;

  // Update the sort button text dynamically based on the current state
  updateSortButtons();
}

sortStudent = function sortStudent(column) {
  if (sortColumn === column) {
    sortDirection = sortDirection === "asc" ? "desc" : "asc";
  } else {
    sortColumn = column;
    sortDirection = "asc";
  }

  fetchStudents();
};

sortGrade = function sortGrade(column) {
  if (sortColumn === column) {
    sortDirection = sortDirection === "asc" ? "desc" : "asc";
  } else {
    sortColumn = column;
    sortDirection = "asc";
  }

  fetchStudents();
};

// Dynamically update the button text based on the sorting state
function updateSortButtons() {
  const studentNameButton = document.getElementById("sortStudentName");
  const gradeNameButton = document.getElementById("sortGradeName");

  // Define arrow symbols
  const ascArrow = "▲"; // Up arrow for ascending
  const descArrow = "▼"; // Down arrow for descending

  // Update student name button text based on sort state
  if (sortColumn === "student_name") {
    studentNameButton.innerHTML = `Sort by Name ${
      sortDirection === "asc" ? ascArrow : descArrow
    }`;
  } else {
    studentNameButton.textContent = "Sort by Name";
  }

  // Update grade name button text based on sort state
  if (sortColumn === "grade_name") {
    gradeNameButton.innerHTML = `Sort by Grade ${
      sortDirection === "asc" ? ascArrow : descArrow
    }`;
  } else {
    gradeNameButton.textContent = "Sort by Grade";
  }
}

studentNextPage = function nextPage() {
  currentPage++;
  fetchStudents();
};

studentPreviousPage = function previousPage() {
  if (currentPage > 1) {
    currentPage--;
    fetchStudents();
  }
};

editStudent = function editStudent(studentId, button) {
  const updateForm = document.getElementById("updateForm");
  const editPopup = document.getElementById("editPopup");

  const row = button.parentElement.parentElement;
  const cells = row.getElementsByTagName("td");

  document.getElementById("editRegNo").value = cells[2].innerText;
  document.getElementById("editName").value = cells[3].innerText;
  document.getElementById("editGrade").value = cells[4].innerText;
  document.getElementById("editGuardian").value = cells[5].innerText;
  document.getElementById("editFather").value = cells[6].innerText;
  document.getElementById("editAddress").value = cells[7].innerText;
  document.getElementById("editContact").value = cells[8].innerText;
  document.getElementById("editEmail").value = cells[9].innerText;
  document.getElementById("editJoinDate").value =
    cells[10].innerText !== "-" ? cells[10].innerText : "";
  document.getElementById("editLeaveDate").value =
    cells[11].innerText !== "-" ? cells[11].innerText : "";

  editPopup.dataset.studentId = studentId;
  updateForm.classList.add("active");
  editPopup.style.display = "block";
};

closeStudentPopup = function closePopup() {
  document.getElementById("editPopup").style.display = "none";
};

updateStudent = async function updateStudent() {
  const studentId = document.getElementById("editPopup").dataset.studentId;
  const formData = new FormData();

  formData.append("student_id", studentId);
  formData.append(
    "registration_number",
    document.getElementById("editRegNo").value
  );
  formData.append("student_name", document.getElementById("editName").value);
  formData.append("grade_name", document.getElementById("editGrade").value);
  formData.append("guardian", document.getElementById("editGuardian").value);
  formData.append("father_name", document.getElementById("editFather").value);
  formData.append("address", document.getElementById("editAddress").value);
  formData.append(
    "contact_number",
    document.getElementById("editContact").value
  );
  formData.append("email", document.getElementById("editEmail").value);
  formData.append("join_date", document.getElementById("editJoinDate").value);
  formData.append("leave_date", document.getElementById("editLeaveDate").value);

  const updateButton = document.querySelector(".update-btn");

  updateButton.disabled = true;

  try {
    const response = await axios.post(
      "http://localhost/voting_system/server/controller/student/student_update.php?action=update",
      formData
    );
    const data = response.data;
    if (data.status === "success") {
      showToast(data.message, "success");
      closeStudentPopup();
      fetchStudents();
    } else {
      showToast("Update failed: " + data.message, "error");
    }
  } catch (error) {
    console.error("Error updating student:", error);
    showToast("Update error: " + error, "error");
  } finally {
    updateButton.disabled = false;
  }
};

deleteStudent = async function deleteStudent(studentId) {
  if (confirm("Are you sure you want to delete this student?")) {
    try {
      console.log(studentId, "kk");

      const response = await axios.delete(
        `http://localhost/voting_system/server/controller/student/student_delete.php?id=${studentId}`
      );
      console.log(response);

      if (response.status === 200) {
        showToast("Student deleted successfully!", "success");
        fetchStudents();
      } else {
        showToast("Failed to delete student.", "error");
      }
    } catch (error) {
      console.error("Error deleting student:", error);
      showToast("Error deleting student: " + error, "error");
    }
  }
};

function showToast(message, type) {
  const toast = document.getElementById("toast");
  toast.innerText = message;
  toast.classList.add("show", type);
  setTimeout(() => toast.classList.remove("show"), 4000);
}

(async function init() {
  await fetchStudents();
})();

fetchStudents();
