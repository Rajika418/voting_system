document.addEventListener('DOMContentLoaded', function () {
    let currentPag = 1;
    let limit = 20;
    let sortDirection = 'asc';
    let sortColumn = 'grade_name';
    let searchQuer = '';


    function fetchStudents() {
        const searchElement = document.getElementById('search');
        searchQuer = searchElement ? searchElement.value : '';

        const url = `http://localhost/voting_system/server/controller/student/student_get.php?page=${currentPag}&limit=${limit}&search=${searchQuer}&sort_by=${sortColumn}&order=${sortDirection}`;

        fetch(url)
            .then(response => response.json())
            .then(data => renderTable(data))
            .catch(error => console.error('Error fetching student data:', error));
    }

    function renderTable(students) {
        const tbody = document.querySelector('#studentTable tbody');
        
        if (!tbody) {
            console.error("Table body not found!");
            return; 
        }
        
        tbody.innerHTML = '';

        students.forEach((student, index) => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${index + 1 + (currentPag - 1) * limit}</td>
                <td>${student.registration_number}</td>
                <td>${student.student_name}</td>
                <td>${student.grade_name}</td>
                <td>${student.teacher_name}</td>
                <td>${student.guardian}</td>
                <td>${student.address}</td>
                <td>${student.contact_number}</td>
                <td>${student.email}</td>
                <td>${student.join_date}</td>
                <td>${student.leave_date || ''}</td>
                <td class="actions">
                    <button class="edit-btn" onclick="editStudent(${student.student_id}, this)">Edit</button>
                    <button class="delete-btn" onclick="deleteStudent(${student.student_id})">Delete</button>
                </td>
            `;

            tbody.appendChild(tr);
        });

        document.getElementById('pageInfo').innerText = `Page ${currentPag}`;
    }

    function sortTable(column) {
        if (sortColumn === column) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = column;
            sortDirection = 'asc';
        }

        fetchStudents();
    }

    function setSortDirection(direction) {
        sortDirection = direction;
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

        const row = button.parentElement.parentElement; // Get the row of the clicked button
        const cells = row.getElementsByTagName("td");

        // Populate the form fields with data from the table row
        document.getElementById("editRegNo").value = cells[1].innerText;
        document.getElementById("editName").value = cells[2].innerText;
        document.getElementById("editGrade").value = cells[3].innerText;
        document.getElementById("editClassTeacher").value = cells[4].innerText;
        document.getElementById("editGuardian").value = cells[5].innerText;
        document.getElementById("editAddress").value = cells[6].innerText;
        document.getElementById("editContact").value = cells[7].innerText;
        document.getElementById("editEmail").value = cells[8].innerText;
        document.getElementById("editJoinDate").value = cells[9].innerText !== '-' ? cells[9].innerText : '';
        document.getElementById("editLeaveDate").value = cells[10].innerText !== '-' ? cells[10].innerText : '';

        // Add the student ID to the popup for use during the update
        editPopup.dataset.studentId = studentId;

        // Show the popup
        updateForm.classList.add("active");
        editPopup.style.display = 'block'; // Make the popup visible
    }

    function closePopup() {
        document.getElementById("editPopup").style.display = 'none'; // Hide the popup
    }

    function updateStudent() {
        const studentId = document.getElementById("editPopup").dataset.studentId;
        const formData = new FormData();

        formData.append("student_id", studentId);
        formData.append("registration_number", document.getElementById("editRegNo").value);
        formData.append("student_name", document.getElementById("editName").value);
        formData.append("grade_name", document.getElementById("editGrade").value);
        formData.append("teacher_name", document.getElementById("editClassTeacher").value);
        formData.append("guardian", document.getElementById("editGuardian").value);
        formData.append("address", document.getElementById("editAddress").value);
        formData.append("contact_number", document.getElementById("editContact").value);
        formData.append("email", document.getElementById("editEmail").value);
        formData.append("join_date", document.getElementById("editJoinDate").value);
        formData.append("leave_date", document.getElementById("editLeaveDate").value);

        const updateButton = document.querySelector('.update-btn');

        updateButton.disabled = true; // Disable the button before the request

        fetch('http://localhost/voting_system/server/controller/student/student_update.php?action=update', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message, 'success');
                closePopup();
                fetchStudents();
            } else {
                showToast('Update failed: ' + data.message, 'error'); // Show specific error message
            }
        })
        .catch(error => {
            console.error('Error updating student:', error);
            showToast('Update error: ' + error, 'error');
        })
        .finally(() => {
            updateButton.disabled = false; // Re-enable button after completion
        });
    }

    function deleteStudent(studentId) {
        if (confirm('Are you sure you want to delete this student?')) {
            fetch(`http://localhost/voting_system/server/controller/student/student_delete.php?id=${studentId}`, {
                method: 'DELETE'
            })
            .then(response => {
                if (response.ok) {
                    showToast('Student deleted successfully!');
                    fetchStudents(); // Refresh the list
                } else {
                    showToast('Failed to delete student.');
                }
            })
            .catch(error => console.error('Error deleting student:', error));
        }
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.innerText = message;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    // Initial fetch
    fetchStudents();
});
