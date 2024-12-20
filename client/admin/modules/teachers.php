<h2>Teacher List</h2>

<!-- Search input -->
<div class="search-container">
    <input
        type="text"
        id="searchInput"
        placeholder="Search for names.."
        oninput="searchTeacher()" />

    <button class="classAssing-btn" onclick="openAssignPopup()">
        Assign Class Teacher
    </button>
</div>

<!-- Sorting options -->
<div class="sort-container">
    <div class="sort">
        <p>Sortby:</p>
    </div>
    <div class="sortbutton">
        <button id="sortButton" onclick="toggleSort()">Sort by Name <span id="sortArrow">▲</span></button>
    </div>
</div>

<!-- Teacher table -->
<table id="teacherTable">
    <thead>
        <tr>
            <th>NO</th>
            <th>Profile</th>
            <th>Teacher Name</th>
            <th>Grade</th>
            <th>Address</th>
            <th>Contact Number</th>
            <th>NIC</th>
            <th>Email</th>
            <th>Join Date</th>
            <th>Leave Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <!-- Table rows will be generated here by JavaScript -->
    </tbody>
</table>

<!-- Pagination buttons -->
<div class="pagination" id="pagination">
    <!-- Pagination will be generated here by JavaScript -->
</div>

<!-- Popup form for editing teacher details -->
<div class="popup-overlay" id="popupOverlay"></div>
<div id="popupForm">
    <h3>Edit Teacher Details</h3>
    <div class="form-group">
        <label for="editTeacherName">Teacher Name</label>
        <input type="text" id="editTeacherName" />
    </div>

    <div class="form-group">
        <label for="editAddress">Address</label>
        <input type="text" id="editAddress" />
    </div>
    <div class="form-group">
        <label for="editContactNumber">Contact Number</label>
        <input type="text" id="editContactNumber" />
    </div>
    <div class="form-group">
        <label for="editNIC">NIC</label>
        <input type="text" id="editNIC" />
    </div>
    <div class="form-group">
        <label for="editEmail">Email</label>
        <input type="email" id="editEmail" />
    </div>
    <div class="form-group">
        <label for="editJoinDate">Join Date</label>
        <input type="date" id="editJoinDate" />
    </div>
    <div class="form-group">
        <label for="editLeaveDate">Leave Date</label>
        <input type="date" id="editLeaveDate" />
    </div>

    <div class="form-actions">
        <button onclick="closeTeacherPopup()">Close</button>
        <!-- <button onclick="updateTeacher()">Update</button> -->
        <button class="update-btn" onclick="updateTeacher()">Update</button>
    </div>
</div>

<!-- Popup form for assigning class teacher -->
<div class="popup-overlay" id="assignPopupOverlay"></div>
<div id="assignPopupForm">
    <h3>Assign Class Teacher</h3>

    <!-- Teacher Dropdown -->
    <div class="form-group">
        <label for="teacher_name">Teacher Name</label>
        <select id="teacher_name" onchange="fetchGradesForTeacher()"></select>
    </div>

    <div class="form-group" id="grades_display" style="display:none;">
        <label for="grade_name">Grades</label>
        <div id="grades_list"></div>
    </div>
    <!-- Grade Dropdown -->
    <div class="form-group">
        <label for="grade_name">Grade Name</label>
        <select id="grade_name"></select>
    </div>

    <div class="form-actions">
        <button onclick="closeAssignPopup()">Cancel</button>
        <button class="assign-btn" onclick="assignTeacher()">Assign</button>
    </div>
</div>
<div id="toastContainer"></div>
<div class="toast-show"></div>