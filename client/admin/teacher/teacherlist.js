let currentPage = 1; 
const resultsPerPage = 8;
let sortOrder = 'ASC'; 

function fetchTeachers() {
    const searchQuery = document.getElementById('searchInput').value.toLowerCase();
    fetch(`http://localhost/voting_system/server/controller/teacher/teacher_get.php?page=${currentPage}&results_per_page=${resultsPerPage}&sort_order=${sortOrder}`)
        .then(response => response.json())
        .then(data => {
            if (data.data) {
                const teachers = data.data.filter(teacher => 
                    teacher.teacher_name.toLowerCase().includes(searchQuery)
                );
                displayTable(teachers, data.pagination);
            } else {
                console.error('Error fetching data:', data.message);
            }
        })
        .catch(error => console.error('Fetch error:', error));
}

function displayTable(teachers, pagination) {
    const tableBody = document.querySelector("#teacherTable tbody");
    tableBody.innerHTML = "";
    teachers.forEach((teacher, index) => {
        const row = `
            <tr data-id="${teacher.teacher_id}">
                <td>${index + 1}</td>
                <td><img src="${teacher.image ? teacher.image : 'default-profile.png'}" alt="Profile Image" width="50" height="50"></td>
                <td>${teacher.teacher_name}</td>
                <td>${teacher.address}</td>
                <td>${teacher.contact_number}</td>
                <td>${teacher.nic}</td>
                <td>${teacher.email}</td>
                <td>${teacher.join_date || '-'}</td>
                <td>${teacher.leave_date || '-'}</td>
                <td>
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

function searchTeacher() {
    fetchTeachers();
}

function sortTable(order) {
    sortOrder = order;
    fetchTeachers();
}

function openPopup(row) {
    const popupForm = document.getElementById("popupForm");
    const overlay = document.getElementById("popupOverlay");

    const teacherId = row.dataset.id;
    popupForm.dataset.teacherId = teacherId;

    const cells = row.getElementsByTagName("td");
    document.getElementById("editTeacherName").value = cells[2].innerText;
    document.getElementById("editAddress").value = cells[3].innerText;
    document.getElementById("editContactNumber").value = cells[4].innerText;
    document.getElementById("editNIC").value = cells[5].innerText;
    document.getElementById("editEmail").value = cells[6].innerText;
    document.getElementById("editJoinDate").value = cells[7].innerText !== '-' ? cells[7].innerText : '';
    document.getElementById("editLeaveDate").value = cells[8].innerText !== '-' ? cells[8].innerText : '';

    popupForm.classList.add("active");
    overlay.classList.add("active");
}

function closePopup() {
    document.getElementById("popupForm").classList.remove("active");
    document.getElementById("popupOverlay").classList.remove("active");
}

function updateTeacher() {
    const teacherId = document.getElementById("popupForm").dataset.teacherId;
    const formData = new FormData();
    formData.append("teacher_id", teacherId);
    formData.append("teacher_name", document.getElementById("editTeacherName").value);
    formData.append("address", document.getElementById("editAddress").value);
    formData.append("contact_number", document.getElementById("editContactNumber").value);
    formData.append("nic", document.getElementById("editNIC").value);
    formData.append("email", document.getElementById("editEmail").value);
    formData.append("join_date", document.getElementById("editJoinDate").value);
    formData.append("leave_date", document.getElementById("editLeaveDate").value);

    const updateButton = document.querySelector('.update-btn');
    updateButton.disabled = true; // Disable button during update

    fetch('http://localhost/voting_system/server/controller/teacher/teacher_update.php?action=update', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Teacher updated successfully', 'success');
            closePopup(); // Ensure the popup is closed after a successful update
            fetchTeachers(); // Refresh the teacher list
        } else {
            showToast('Update failed: ' + data.message, 'error');
        }
    })
    .catch(error => {
        showToast('Update error: ' + error, 'error');
    })
    .finally(() => {
        updateButton.disabled = false; // Re-enable button after completion
    });
}


function deleteTeacher(teacherId) {
    if (confirm('Are you sure you want to delete this teacher?')) {
        const formData = new FormData();
        formData.append("teacher_id", teacherId);

        fetch('http://localhost/voting_system/server/controller/teacher/teacher_delete.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Teacher deleted successfully', 'success');
                fetchTeachers();
            } else {
                showToast('Delete failed: ' + data.message, 'error');
            }
        })
        .catch(error => {
            showToast('Delete error: ' + error, 'error');
        });
    }
}

function addEditListeners() {
    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function() {
            openPopup(button.closest("tr"));
        });
    });
}

function addDeleteListeners() {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function() {
            const teacherId = button.closest("tr").dataset.id;
            deleteTeacher(teacherId);
        });
    });
}

function showToast(message, type) {
    const toastContainer = document.getElementById('toastContainer');
    const toast = document.createElement('div');
    toast.classList.add('toast', type);
    toast.innerText = message;
    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 10000);
}

document.addEventListener('DOMContentLoaded', function() {
    fetchTeachers();
});





