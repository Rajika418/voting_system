<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate List</title>
    <link rel="stylesheet" href="../../css/candidatelist.css">
</head>
<body>
    <div class="container">
        <h1>Candidate List</h1>
        <input type="text" id="search" placeholder="Search candidates..." />
        <table id="candidateTable">
            <thead>
                <tr>
                    <th onclick="sortTable('candidate_id')">Candidate ID</th>
                    <th onclick="sortTable('student_name')">Student Name</th>
                    <th>Motive</th>
                    <th>What</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody id="candidateBody">
                <!-- Candidate data will be inserted here -->
            </tbody>
        </table>
        <div class="pagination">
            <button id="prevPage" onclick="changePage(-1)">Prev</button>
            <span id="pageInfo">Page 1</span>
            <button id="nextPage" onclick="changePage(1)">Next</button>
        </div>
    </div>
    <script src="../../js/candidatelist.js"></script>
</body>
</html>
