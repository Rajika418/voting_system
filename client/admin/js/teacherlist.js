let currentPage = 1;
const resultsPerPage = 8;
let sortOrder = "ASC";
let searchQuery = "";
let currentSortOrder = "ASC";

function toggleSort() {
  currentSortOrder = currentSortOrder === "ASC" ? "DESC" : "ASC";
  sortTeacher(currentSortOrder);

  // Update the arrow direction
  const sortArrow = document.getElementById("sortArrow");
  sortArrow.textContent = currentSortOrder === "ASC" ? "▲" : "▼";
}

fetchTeachers = async function fetchTeachers() {
  const searchInputElement = document.getElementById("searchInput");
  searchQuery = searchInputElement
    ? searchInputElement.value.toLowerCase()
    : "";

  try {
    const response = await axios.get(
      `http://localhost/voting_system/server/controller/teacher/teacher_get.php`,
      {
        params: {
          page: currentPage,
          search: searchQuery,
          results_per_page: resultsPerPage,
          sort_order: sortOrder,
        },
      }
    );

    const data = response.data;
    if (data.data) {
      displayTable(data.data, data.pagination);
    } else {
      console.error("Error fetching data:", data.message);
    }
  } catch (error) {
    console.error("Fetch error:", error);
  }
};

function displayTable(teachers, pagination) {
  const tableBody = document.querySelector("#teacherTable tbody");

  tableBody.innerHTML = ""; // Clear existing rows
  teachers.forEach((teacher, index) => {
    const grades =
      teacher.grades && teacher.grades.length > 0
        ? teacher.grades.join(", ")
        : "No grade assigned";

    const row = `
            <tr data-id="${teacher.teacher_id}">
                <td>${index + 1}</td>
                <td><img src="${
                  teacher.image ? teacher.image : ""
                }" alt="Profile Image" width="50" height="50"></td>
                <td>${teacher.teacher_name}</td>
                <td>${grades}</td>
                <td>${teacher.address}</td>
                <td>${teacher.contact_number}</td>
                <td>${teacher.nic}</td>
                <td>${teacher.email}</td>
                <td>${teacher.join_date || "-"}</td>
                <td>${teacher.leave_date || "-"}</td>
                <td class= "action">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </td>
            </tr>
        `;
    tableBody.insertAdjacentHTML("beforeend", row);
  });
  addEditListeners();
  addDeleteListeners();
  displayPagination(pagination);
}

function displayPagination(pagination) {
  const paginationDiv = document.getElementById("pagination");
  paginationDiv.innerHTML = "";
  for (let i = 1; i <= pagination.total_pages; i++) {
    const button = document.createElement("button");
    button.innerText = i;
    button.onclick = () => {
      currentPage = i;
      fetchTeachers();
    };
    if (i === pagination.current_page) {
      button.style.backgroundColor = "#4CAF50";
      button.style.color = "white";
    }
    paginationDiv.appendChild(button);
  }
}

searchTeacher = function searchTeacher() {
  fetchTeachers();
};

sortTeacher = function sortTable(order) {
  sortOrder = order;
  fetchTeachers();
};

updateTeacher = async function updateTeacher() {
  const teacherId = document.getElementById("popupForm").dataset.teacherId;
  const formData = new FormData();
  formData.append("teacher_id", teacherId);
  formData.append(
    "teacher_name",
    document.getElementById("editTeacherName").value
  );
  formData.append("address", document.getElementById("editAddress").value);
  formData.append(
    "contact_number",
    document.getElementById("editContactNumber").value
  );
  formData.append("nic", document.getElementById("editNIC").value);
  formData.append("email", document.getElementById("editEmail").value);
  formData.append("join_date", document.getElementById("editJoinDate").value);
  formData.append("leave_date", document.getElementById("editLeaveDate").value);

  const updateButton = document.querySelector(".update-btn");
  updateButton.disabled = true; // Disable button during update

  try {
    const response = await axios.post(
      "http://localhost/voting_system/server/controller/teacher/teacher_update.php?action=update",
      formData
    );

    const data = response.data;
    console.log(data);

    if (data.success) {
      showToast("Teacher updated successfully", "success");
      closeTeacherPopup(); // Ensure the popup is closed after a successful update
      fetchTeachers(); // Refresh the teacher list
    } else {
      showToast("Update failed: " + data.message, "error");
    }
  } catch (error) {
    showToast("Update error: " + error, "error");
  } finally {
    updateButton.disabled = false; // Re-enable button after completion
  }
};

function openPopup(row) {
  const popupForm = document.getElementById("popupForm");
  const overlay = document.getElementById("popupOverlay");

  const teacherId = row.dataset.id;
  popupForm.dataset.teacherId = teacherId;

  const cells = row.getElementsByTagName("td");
  const editTeacherName = document.getElementById("editTeacherName");
  const editAddress = document.getElementById("editAddress");
  const editContactNumber = document.getElementById("editContactNumber");
  const editNIC = document.getElementById("editNIC");
  const editEmail = document.getElementById("editEmail");
  const editJoinDate = document.getElementById("editJoinDate");
  const editLeaveDate = document.getElementById("editLeaveDate");

  editTeacherName.value = cells[2].innerText;
  editAddress.value = cells[4].innerText;
  editContactNumber.value = cells[5].innerText;
  editNIC.value = cells[6].innerText;
  editEmail.value = cells[7].innerText;
  editJoinDate.value = cells[8].innerText !== "-" ? cells[8].innerText : "";
  editLeaveDate.value = cells[9].innerText !== "-" ? cells[9].innerText : "";

  popupForm.classList.add("active");
  overlay.classList.add("active");
}

closeTeacherPopup = function closePopup() {
  document.getElementById("popupForm").classList.remove("active");
  document.getElementById("popupOverlay").classList.remove("active");
};

function addEditListeners() {
  document.querySelectorAll(".edit-btn").forEach((button) => {
    button.addEventListener("click", function () {
      openPopup(button.closest("tr"));
    });
  });
}

function addDeleteListeners() {
  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const teacherId = button.closest("tr").dataset.id;
      deleteTeacher(teacherId);
    });
  });
}

async function deleteTeacher(teacherId) {
  if (confirm("Are you sure you want to delete this teacher?")) {
    const formData = new FormData();
    formData.append("teacher_id", teacherId);
    console.log("hi", teacherId);
    try {
      const response = await axios.post(
        "http://localhost/voting_system/server/controller/teacher/teacher_delete.php?action=delete",
        formData
      );
      const data = response.data;
      showToast("Teacher deleted successfully", "success");
      fetchTeachers();
    } catch (error) {
      showToast("Delete error: " + error, "error");
    }
  }
}

function showToast(message, type) {
  const toastContainer = document.getElementById("toastContainer");
  const toast = document.createElement("div");
  toast.classList.add("toast", type);
  toast.innerText = message;
  toastContainer.appendChild(toast);

  // Use a small timeout to ensure the element is in the DOM before the class is added
  setTimeout(() => {
    toast.classList.add("toast-show");
  }, 10); // Small delay to trigger transition

  setTimeout(() => {
    toast.classList.remove("toast-show");
    // Remove the toast after it fades out
    setTimeout(() => {
      toast.remove();
    }, 500); // Match the transition time in the CSS
  }, 5000); // Keep toast visible for 10 seconds
}

fetchTeachers();

(async function init() {
  await fetchTeachers();
})();
