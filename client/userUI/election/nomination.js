// Fetch student info by registration number
function fetchStudentInfo() {
    const registrationNumber = document.getElementById("registration_number").value;
    
    fetch(`http://localhost/voting_system/server/controller/election/nomination/reg_no_get.php?registration_number=${registrationNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById("student_id").value = data.student_id;
                document.getElementById("student-name").textContent = `Name: ${data.student_name}`;
                document.getElementById("student-grade").textContent = `Grade: ${data.grade_name}`;
                document.getElementById("student-image").src = data.image;
                document.getElementById("student-image").style.display = "block";
            } else {
                document.getElementById("message").textContent = "Student not found.";
            }
        })
        .catch(error => {
            document.getElementById("message").textContent = "Error fetching student info.";
            console.error("Error:", error);
        });
}

// Submit nomination form
function submitNomination(event) {
    event.preventDefault();
    
    const electionId = document.getElementById("election_id").value;
    const studentId = document.getElementById("student_id").value;
    const why = document.getElementById("why").value;
    const motive = document.getElementById("motive").value;
    const what = document.getElementById("what").value;
    
    const formData = new FormData();
    formData.append("election_id", electionId);
    formData.append("student_id", studentId);
    formData.append("why", why);
    formData.append("motive", motive);
    formData.append("what", what);

    fetch("http://localhost/voting_system/server/controller/election/nomination/nomination_post.php?action=create", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            document.getElementById("message").textContent = "Nomination submitted successfully!";
            document.getElementById("nominationForm").reset();
        } else {
            document.getElementById("message").textContent = data.message;
        }
    })
    .catch(error => {
        document.getElementById("message").textContent = "Error submitting nomination.";
        console.error("Error:", error);
    });
}
