
 function fetchDashboard() {    fetch('http://localhost/voting_system/server/controller/dashboard_get.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Update the counts in the HTML
            document.getElementById('studentCount').innerText = data.student;
            document.getElementById('subjectCount').innerText = data.subjects;
            document.getElementById('teacherCount').innerText = data.teacher;
            document.getElementById('electionCount').innerText = data.elections;
            document.getElementById('gradeCount').innerText = data.grade;
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });

    }
        export function init() {
            fetchDashboard(); 
        }