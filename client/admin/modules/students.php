<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Student List</title>
    <link rel="stylesheet" href="./assets/css/studentlist.css" />
    <script src="./js/studentlist.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
  </head>
  <body>
    <h1>Student List</h1>

    <div class="search-box">
      <label for="search">Search:</label>
      <input
        type="text"
        id="search"
        placeholder="Enter name or registration number"
        onkeyup="fetchStudents()"
      />
    </div>

    <div class="sort-box">
      <button id="sortStudentName" onclick="sortStudent('student_name')">
        Sort by Name
      </button>
      <button id="sortGradeName" onclick="sortGrade('grade_name')">
        Sort by Grade
      </button>
    </div>

    <table id="studentTable">
      <thead>
        <tr>
          <th>No</th>
          <th>Profile</th>
          <th>Registration Number</th>
          <th>Student Name</th>
          <th onclick="sortStudent('grade_name')">Grade</th>
          <th>Guardian</th>
          <th>Father Name</th>
          <th>Address</th>
          <th>Contact Number</th>
          <th>Email</th>
          <th>Join Date</th>
          <th>Leave Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <!-- Data will be injected here -->
      </tbody>
    </table>

    <div class="pagination">
      <button onclick="studentPreviousPage()">Previous</button>
      <span id="pageInfo">Page 1</span>
      <button onclick="studentNextPage()">Next</button>
    </div>

    <!-- Update Student Popup Form -->
    <div id="editPopup" class="popup-form">
      <div class="popup-content">
        <h2>Update Student</h2>
        <form id="updateForm">
          <div class="form-group">
            <label for="editRegNo">Registration Number:</label>
            <input type="text" id="editRegNo" name="editRegNo" required />
          </div>

          <div class="form-group">
            <label for="editName">Student Name:</label>
            <input type="text" id="editName" name="editName" required />
          </div>

          <div class="form-group">
            <label for="editGrade">Grade:</label>
            <input type="text" id="editGrade" name="editGrade" required />
          </div>

          <div class="form-group">
            <label for="editGuardian">Guardian:</label>
            <input type="text" id="editGuardian" name="editGuardian" required />
          </div>

          <div class="form-group">
            <label for="editFather">Father Name:</label>
            <input type="text" id="editFather" name="editFather" required />
          </div>

          <div class="form-group">
            <label for="editAddress">Address:</label>
            <input type="text" id="editAddress" name="editAddress" required />
          </div>

          <div class="form-group">
            <label for="editContact">Contact Number:</label>
            <input type="text" id="editContact" name="editContact" required />
          </div>

          <div class="form-group">
            <label for="editEmail">Email:</label>
            <input type="email" id="editEmail" name="editEmail" required />
          </div>

          <div class="form-group">
            <label for="editJoinDate">Join Date:</label>
            <input type="date" id="editJoinDate" name="editJoinDate" required />
          </div>

          <div class="form-group">
            <label for="editLeaveDate">Leave Date:</label>
            <input type="date" id="editLeaveDate" name="editLeaveDate" />
          </div>

          <button type="button" class="update-btn" onclick="updateStudent()">
            Update
          </button>

          <button type="button" onclick="closeStudentPopup()">Close</button>
        </form>
      </div>
    </div>

    <!-- Toast Messages -->
    <div id="toast" class="toast"></div>
  </body>
</html>
