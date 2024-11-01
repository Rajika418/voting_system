<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomination List</title>
    <link rel="stylesheet" href="../assets/css/nominationlist.css">
</head>
<body>
    <div class="container">
        <h1>Nomination List</h1>
        <div class="election-header">
            <h2 id="electionName">Loading...</h2>
            <p id="electionYear">Loading...</p>
        </div>
        
        <div class="controls">
            <input type="text" id="search" placeholder="Search by name or year" oninput="filterTable()">
            <select id="sort" onchange="sortTable()">
                <option value="name">Sort by Name</option>
                <option value="year">Sort by Grade</option>
            </select>
        </div>
        
        <table id="nominationTable">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Student Name</th>
                    <th>Grade</th>
                    <th>Why</th>
                    <th>Motive</th>
                    <th>What</th>
                    <th>Select</th>
                </tr>
            </thead>
            <tbody id="nominationBody">
                <!-- Rows will be populated by JavaScript -->
            </tbody>
        </table>
        
        <div class="pagination" id="pagination">
            <button onclick="prevPage()">Previous</button>
            <span id="pageInfo">Page 1</span>
            <button onclick="nextPage()">Next</button>
        </div>
    </div>

    <div class="toast-container" id="toastContainer"></div>
    
    <script src="../js/nominationlist.js"></script>
</body>
</html>
