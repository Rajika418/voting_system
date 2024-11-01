document.addEventListener("DOMContentLoaded", () => {
    const gradeSelect = document.getElementById('gradeSelect');
    const fetchStudentsButton = document.getElementById('fetchStudents');
    const gradeName = document.getElementById('gradeName');
    const studentCount = document.getElementById('studentCount');
    const studentList = document.getElementById('studentList');

    // Fetch grades to populate the dropdown
    fetch('http://localhost/voting_system/server/controller/student/get_grades.php?') // Create this API to return grades
        .then(response => response.json())
        .then(data => {
            data.forEach(grade => {
                const option = document.createElement('option');
                option.value = grade.grade_id;
                option.textContent = grade.grade_name;
                gradeSelect.appendChild(option);
            });
        });

    fetchStudentsButton.addEventListener('click', () => {
        const gradeId = gradeSelect.value;

        // Use the same API to fetch grade and students
        fetch(`http://localhost/voting_system/server/controller/student/grade_count_get.php?grade_id=${gradeId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    // Handle any error returned by the API
                    console.error(data.error);
                    alert(data.error);
                    return;
                }

                gradeName.textContent = data.grade_name;
                studentCount.textContent = data.count;
                studentList.innerHTML = '';

                data.students.forEach(student => {
                    const li = document.createElement('li');
                    li.textContent = `${student.student_name} (ID: ${student.student_id})`;
                    studentList.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error fetching student data:', error);
                alert('Failed to fetch student data. Please try again later.');
            });
    });
});
