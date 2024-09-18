let currentPage = 1;
let limit = 20;
let sortDirection = 'asc';
let sortColumn = 'grade_name';
let searchQuery = '';

function fetchStudents() {
    const search = document.getElementById('search').value;
    searchQuery = search;

    const url = `http://localhost/voting_system/server/controller/student/student_get.php?page=${currentPage}&limit=${limit}&search=${searchQuery}&sort_by=${sortColumn}&order=${sortDirection}`;

    fetch(url)
        .then(response => response.json())
        .then(data => renderTable(data))
        .catch(error => console.error('Error fetching student data:', error));
}

function renderTable(students) {
    const tbody = document.querySelector('#studentTable tbody');
    tbody.innerHTML = '';

    students.forEach((student, index) => {
        const tr = document.createElement('tr');

        tr.innerHTML = `
            <td>${index + 1 + (currentPage - 1) * limit}</td>
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
                <button class="edit-btn" onclick="editStudent(${student.student_id})">Edit</button>
                <button class="delete-btn" onclick="deleteStudent(${student.student_id})">Delete</button>
            </td>
        `;

        tbody.appendChild(tr);
    });

    document.getElementById('pageInfo').innerText = `Page ${currentPage}`;
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
    currentPage++;
    fetchStudents();
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        fetchStudents();
    }
}

function editStudent(studentId) {
    document.getElementById('editPopup').style.display = 'flex';

    // Fetch student details using studentId and populate the form fields
    fetch(`http://localhost/voting_system/server/controller/student/student_get_by_id.php?id=${studentId}`)
        .then(response => response.json())
        .then(student => {
            document.getElementById('editRegNo').value = student.registration_number;
            document.getElementById('editName').value = student.student_name;
            document.getElementById('editGrade').value = student.grade_name;
            document.getElementById('editClassTeacher').value = student.teacher_name;
            document.getElementById('editGuardian').value = student.guardian;
            document.getElementById('editAddress').value = student.address;
            document.getElementById('editContact').value = student.contact_number;
            document.getElementById('editEmail').value = student.email;
            document.getElementById('editJoinDate').value = student.join_date;
            document.getElementById('editLeaveDate').value = student.leave_date || ''; // Leave date can be empty
        })
        .catch(error => console.error('Error fetching student details:', error));
}

function updateStudent() {
    const updatedStudent = {
        registration_number: document.getElementById('editRegNo').value,
        student_name: document.getElementById('editName').value,
        grade_name: document.getElementById('editGrade').value,
        teacher_name: document.getElementById('editClassTeacher').value,
        guardian: document.getElementById('editGuardian').value,
        address: document.getElementById('editAddress').value,
        contact_number: document.getElementById('editContact').value,
        email: document.getElementById('editEmail').value,
        join_date: document.getElementById('editJoinDate').value,
        leave_date: document.getElementById('editLeaveDate').value
    };

    fetch(`http://localhost/voting_system/server/controller/student/student_update.php?id=${studentId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedStudent),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closePopup();
            showToast('Student updated successfully!');
            fetchStudents(); // Refresh the student list
        } else {
            showToast('Failed to update student.');
        }
    })
    .catch(error => console.error('Error updating student:', error));
}


function updateStudent() {
    // Implement the update API call here
    closePopup();
    showToast('Student updated successfully!');
}

function closePopup() {
    document.getElementById('editPopup').style.display = 'none';
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
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Fetch students on page load
fetchStudents();
