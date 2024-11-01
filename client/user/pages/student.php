<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Student Details by Grade</title>
</head>
<body>
    <div class="container">
        <h1>Student Details by Grade</h1>
        <select id="gradeSelect">
            <!-- Options will be populated with JavaScript -->
        </select>
        <button id="fetchStudents">Fetch Students</button>

        <h2 id="gradeName"></h2>
        <p>Total Students: <span id="studentCount">0</span></p>
        <ul id="studentList"></ul>
    </div>
    
   
</body>
</html>
