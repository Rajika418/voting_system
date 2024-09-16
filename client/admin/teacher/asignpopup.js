document.addEventListener('DOMContentLoaded', function() {
    // Populate teacher dropdown
    function populateTeacherDropdown() {
        const teacherDropdown = document.getElementById('teacher_name');
        
        fetch('http://localhost/voting_system/server/controller/teacher/teacher_get.php?action=read')
            .then(response => response.json())
            .then(data => {
                console.log(data);  // Use 'data' instead of 'response'
               
                    teacherDropdown.innerHTML = '';
                    const placeholderOption = document.createElement('option');
                    placeholderOption.value = '';
                    placeholderOption.text = 'Select a teacher';
                    teacherDropdown.appendChild(placeholderOption);
    
                    data.data.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.teacher_id;
                        option.text = teacher.teacher_name;
                        teacherDropdown.appendChild(option);
                    });
                
            })
            .catch(error => console.error('Error fetching teachers:', error));
    }
    
    // Populate grade dropdown
    function populateGradeDropdown() {
        const gradeDropdown = document.getElementById('grade_name');
        
        fetch('http://localhost/voting_system/server/controller/grade/grade_get.php?action=read')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    gradeDropdown.innerHTML = '';
                    const placeholderOption = document.createElement('option');
                    placeholderOption.value = '';
                    placeholderOption.text = 'Select a grade';
                    gradeDropdown.appendChild(placeholderOption);

                    data.grades.forEach(grade => {
                        const option = document.createElement('option');
                        option.value = grade.grade_id;
                        option.text = grade.grade_name;
                        gradeDropdown.appendChild(option);
                    });
                } else {
                    console.error('Failed to retrieve grades:', data.message);
                }
            })
            .catch(error => console.error('Error fetching grades:', error));
    }

    // Open assign class teacher popup
    window.openAssignPopup = function() {
        document.getElementById('assignPopupOverlay').classList.add('active');
        document.getElementById('assignPopupForm').classList.add('active');
        
        // Populate the dropdowns when the popup is opened
        populateTeacherDropdown();
        populateGradeDropdown();
    }

    // Close assign class teacher popup
    window.closeAssignPopup = function() {
        document.getElementById('assignPopupOverlay').classList.remove('active');
        document.getElementById('assignPopupForm').classList.remove('active');
    }

    // Assign teacher to class
    window.assignTeacher = function() {
        const teacherId = document.getElementById('teacher_name').value;
        const gradeId = document.getElementById('grade_name').value;
        
        if (teacherId && gradeId) {
            // Perform the assign operation here (e.g., send data to the server)
            console.log(`Assigned Teacher ID: ${teacherId} to Grade ID: ${gradeId}`);
            
            // Close the popup
            closeAssignPopup();
        } else {
            alert('Please select both a teacher and a grade.');
        }
    }
});
