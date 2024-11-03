<div class="student-container">
    <div class="controls-container">
        <select id="gradeSelect" class="grade-select">
            <option value="">Select Grade</option>
        </select>
        <div class="search-container">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search students...">
        </div>
    </div>

    <div class="grade-info">
        <h2 class="grade-name" id="gradeName"></h2>
        <p class="student-count">Total Students: <span id="studentCount">0</span></p>
    </div>

    <ul class="student-grid" id="studentList"></ul>
    <div class="pagination-container" id="pagination"></div>
</div>