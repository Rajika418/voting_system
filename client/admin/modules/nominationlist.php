<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nomination List</title>
  <link rel="stylesheet" href="../assets/css/nominationlist.css">
</head>
<div class="container">
  <h1>Nomination List</h1>
  <h2 id="electionName"></h2> <!-- Placeholder for Election Name -->
  <div class="controls">
    <input type="text" id="search" placeholder="Search by name or year" oninput="filterTable()">
    <select id="sort" onchange="sortTable()">
      <option value="name">Sort by Name</option>
      <option value="year">Sort by Year</option>
    </select>
  </div>
    <table id="nominationTable">
      <thead>
        <tr>
          <th>ID</th>
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
      <span id="pageInfo"></span>
      <button onclick="nextPage()">Next</button>
    </div>
  </div>

  <script src="../js/nominationlist.js"></script>
</body>
</html>
