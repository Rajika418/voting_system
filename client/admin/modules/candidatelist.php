<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate List</title>
    <link rel="stylesheet" href="../assets/css/candidatelist.css">
</head>
<body>
    <div class="container">
        <h1 id="electionTitle">Candidate List</h1>
        
        <input 
            type="text" 
            id="search" 
            placeholder="Search candidates..." 
            class="search-input"
        />

        <table id="candidateTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Election Name</th>
                    <th>Year</th>
                    <th>Candidate Name</th>
                    <th>Total Votes</th>
                </tr>
            </thead>
            <tbody id="candidateBody">
                <!-- Data will be inserted here -->
            </tbody>
        </table>

        <div class="pagination">
            <button id="prevPage" onclick="changePage(-1)">Previous</button>
            <span id="pageInfo">Page 1</span>
            <button id="nextPage" onclick="changePage(1)">Next</button>
        </div>
    </div>

    <script src="../js/candidatelist.js"></script>
</body>
</html>