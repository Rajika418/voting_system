let currentPage = 1;
let limit = 20;
let sortDirection = 'asc'; // Default sort direction
let sortColumn = 'grade_name'; // Default sort column
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
    alert(`Edit student ID: ${studentId}`);
}

function deleteStudent(studentId) {
    if (confirm('Are you sure you want to delete this student?')) {
        alert(`Student ID: ${studentId} will be deleted`);
        // Implement DELETE logic here.
    }
}

// Initial fetch when page loads
fetchStudents();
