let currentPage = 1;
let limit = 20;
let sortDirection = "asc";
let sortColumn = "grade_name";
let searchQuery = "";

async function fetchStudents() {
  const searchElement = document.getElementById("search");
  searchQuery = searchElement ? searchElement.value : "";

  const url = `http://localhost/voting_system/server/controller/student/student_get.php?page=${currentPage}&limit=${limit}&search=${searchQuery}&sort_by=${sortColumn}&order=${sortDirection}`;

  try {
    const response = await axios.get(url);
    renderTable(response.data);
  } catch (error) {
    console.error("Error fetching student data:", error);
  }
}

function renderTable(students) {
  const tbody = document.querySelector("#studentTable tbody");

  tbody.innerHTML = "";

  students.forEach((student, index) => {
    const tr = document.createElement("tr");

    tr.innerHTML = `
                <td>${index + 1 + (currentPag - 1) * limit}</td>
                <td>${student.registration_number}</td>
                <td>${student.student_name}</td>
                <td>${student.grade_name}</td>
                <td>${student.teacher_name}</td>
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

  document.getElementById("pageInfo").innerText = `Page ${currentPag}`;
}

function sortTable(column) {
  if (sortColumn === column) {
    sortDirection = sortDirection === "asc" ? "desc" : "asc";
  } else {
    sortColumn = column;
    sortDirection = "asc";
  }

  fetchStudents();
}

function nextPage() {
  currentPag++;
  fetchStudents();
}

function previousPage() {
  if (currentPag > 1) {
    currentPag--;
    fetchStudents();
  }
}

function editStudent(studentId, button) {
  const updateForm = document.getElementById("updateForm");
  const editPopup = document.getElementById("editPopup");

  const row = button.parentElement.parentElement;
  const cells = row.getElementsByTagName("td");

  document.getElementById("editRegNo").value = cells[1].innerText;
  document.getElementById("editName").value = cells[2].innerText;
  document.getElementById("editGrade").value = cells[3].innerText;
  document.getElementById("editClassTeacher").value = cells[4].innerText;
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
}

function closePopup() {
  document.getElementById("editPopup").style.display = "none";
}

async function updateStudent() {
  const studentId = document.getElementById("editPopup").dataset.studentId;
  const formData = new FormData();

  formData.append("student_id", studentId);
  formData.append(
    "registration_number",
    document.getElementById("editRegNo").value
  );
  formData.append("student_name", document.getElementById("editName").value);
  formData.append("grade_name", document.getElementById("editGrade").value);
  formData.append(
    "teacher_name",
    document.getElementById("editClassTeacher").value
  );
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
      closePopup();
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
}

async function deleteStudent(studentId) {
  if (confirm("Are you sure you want to delete this student?")) {
    try {
      const response = await axios.delete(
        `http://localhost/voting_system/server/controller/student/student_delete.php?id=${studentId}`
      );
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
}

function showToast(message, type = "success") {
  const toast = document.getElementById("toast");
  toast.innerText = message;
  toast.classList.add("show", type);
  setTimeout(() => toast.classList.remove("show"), 4000);
}

(async function init() {
  await fetchStudents();
})();
