// Function to populate the student name dropdown from the GET API
function populateStudentNames() {
    axios.get('http://localhost/voting_system/server/controller/admission/ol_student_get.php?action=read')
        .then(response => {
            console.log(response)
            if (response.data.status === 'success') {
                const students = response.data.data;
                console.log(response.data,'hiii')
                const studentNameSelect = document.getElementById('studentName');
                studentNameSelect.innerHTML = '<option value="">Select Student</option>'; // Reset options

                students.forEach(student => {
                    // Add each student as an option in the dropdown
                    const option = document.createElement('option');
                    option.value = student.student_id; // Store student_id as value
                    option.textContent = student.student_name; // Display student_name
                    studentNameSelect.appendChild(option);
                });

                // Add event listener for student selection
                studentNameSelect.addEventListener('change', (event) => {
                    const selectedStudentId = event.target.value;
                    const selectedStudent = students.find(student => student.student_id === selectedStudentId);
                    
                    if (selectedStudent) {
                        const guardianInfo = document.getElementById('guardianInfo');
                        if (selectedStudent.father_name && selectedStudent.father_name !== 'null') {
                            guardianInfo.textContent = `Father Name: ${selectedStudent.father_name}`;
                        } else if (selectedStudent.guardian && selectedStudent.guardian !== 'null') {
                            guardianInfo.textContent = `Guardian: ${selectedStudent.guardian}`;
                        } else {
                            guardianInfo.textContent = ''; // Clear if both are null
                        }
                    }
                });
            } else {
                console.error('Failed to fetch students');
            }
        })
        .catch(error => {
            console.error('Error fetching students:', error);
        });
}

// Function to submit the form data via POST API
function submitAdmissionForm(event) {
    event.preventDefault(); // Prevent form default submit

    const studentId = document.getElementById('studentName').value;
    const nic = document.getElementById('nic').value;
    const year = document.getElementById('year').value;
    const examName = document.getElementById('examName').value;
    const indexNo = document.getElementById('indexNo').value;
             console.log(studentId,nic,year,examName,indexNo)
    if (studentId && nic && year && examName && indexNo) {
        // Construct the data payload
        const postData = {
            student_id: studentId,
            nic: nic,
            year: year,
            exam_name: examName,
            index_no: indexNo
        };

        // Send POST request
        axios.post('http://localhost/voting_system/server/controller/admission/ol_admission_post.php', postData)
            .then(response => {
                if (response.data.status === 'success') {
                    alert('Data successfully submitted');
                } else {
                    alert(`Submission failed: ${response.data.message}`);
                }
            })
            .catch(error => {
                console.error('Error submitting data:', error);
                alert('Error submitting data.');
            });
    } else {
        alert('Please fill all the required fields');
    }
}

// Add event listeners
document.getElementById('admissionForm').addEventListener('submit', submitAdmissionForm);

// Call function to populate student names on page load
populateStudentNames();
