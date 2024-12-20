<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration Form</title>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Fetch subjects from the server
            fetch('http://localhost/voting_system/server/controller/subject/subject_get.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const subjectSelect = document.getElementById('subject');
                        data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.subject_id;
                            option.textContent = subject.subject_name;
                            subjectSelect.appendChild(option);
                        });
                    } else {
                        console.error('Error fetching subjects:', data.message);
                    }
                })
                .catch(error => console.error('Error:', error));

            // Check for registration error message in URL
            var urlParams = new URLSearchParams(window.location.search);
            var errorMessage = urlParams.get('error');
            if (errorMessage) {
                alert(decodeURIComponent(errorMessage));
            }

            // Handle form submission
            document.querySelector('form').addEventListener('submit', function (event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('../../../server/controller/teacher/teacher_post.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    
                    // Check if the response is JSON
                    const contentType = response.headers.get("content-type");
                    if (contentType && contentType.indexOf("application/json") !== -1) {
                        return response.json();
                    } else {
                        // If not JSON, treat it as text and throw an error
                        return response.text().then(text => {
                            throw new Error('Received non-JSON response: ' + text);
                        });
                    }
                })
                .then(data => {
                    if (data && data.message) {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during registration: ' + error.message);
                });
            });
        });
    </script>
    <link rel="stylesheet" href="../css/teacher_register.css">
</head>
<body>

    <header>
        <img src="../../admin/assets/images/school.png" alt="School Logo">
        <h2>Kalaimahal TMV.Hopton</h2>
        <h1>School Management System</h1>
    </header>

    <div class="form-container">
        <h2>Teacher Registration </h2>
        <form action="../../../server/controller/teacher/teacher_post.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group left">
                    <label for="teacher_name">Teacher Name:</label>
                    <input type="text" id="teacher_name" name="teacher_name" required>
                </div>
                <div class="form-group right">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group left">
                    <label for="contact_number">Contact Number:</label>
                    <input type="text" id="contact_number" name="contact_number" required>
                </div>
                <div class="form-group right">
                    <label for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group left">
                    <label for="user_name">Username:</label>
                    <input type="text" id="user_name" name="user_name" required>
                </div>
                <div class="form-group right">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group left">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group right">
                    <label for="image">Profile Image:</label>
                    <input type="file" id="image" name="image">
                </div>
            </div>

            <div class="form-row">
                
                <div class="form-group right">
                    <label for="subject">Select Subject:</label>
            <select id="subject" name="subject_id">
                <option value="">--Select Subject--</option>
                <!-- Options will be populated dynamically -->
            </select>
                </div>
                <div class="form-group right">
                    <label for="join_date">Join Date:</label>
                    <input type="date" id="join_date" name="join_date">
                </div>
            </div> 
            <div class="form-row">
                <button type="submit" class="submit-btn">Register</button>
            </div>
        </form>
    </div>


</body>
</html>