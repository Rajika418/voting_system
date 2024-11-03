document.addEventListener("DOMContentLoaded", () => {
  const gradeSelect = document.getElementById("gradeSelect");
  const gradeName = document.getElementById("gradeName");
  const studentCount = document.getElementById("studentCount");
  const studentList = document.getElementById("studentList");
  const searchInput = document.getElementById("searchInput");
  const paginationContainer = document.getElementById("pagination");
  let currentGradeId = null;
  let currentPage = 1;
  const studentsPerPage = 10; // Match server-side limit

  // Fetch grades to populate the dropdown
  fetch(
    "http://localhost/voting_system/server/controller/student/get_grades.php"
  )
    .then((response) => response.json())
    .then((data) => {
      data.forEach((grade) => {
        const option = document.createElement("option");
        option.value = grade.grade_id;
        option.textContent = grade.grade_name;
        gradeSelect.appendChild(option);
      });
    });

  // Function to fetch students with search and pagination
  function fetchStudents(gradeId, page = 1, searchTerm = '') {
    const url = new URL("http://localhost/voting_system/server/controller/student/grade_count_get.php");
    url.searchParams.append('grade_id', gradeId);
    url.searchParams.append('page', page);
    url.searchParams.append('limit', studentsPerPage);
    if (searchTerm) {
      url.searchParams.append('search', searchTerm);
    }

    return fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data.error) {
          throw new Error(data.error);
        }
        return data;
      });
  }

  // Search functionality with debounce
  let searchTimeout;
  searchInput.addEventListener("input", (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      const searchTerm = e.target.value.trim();
      if (currentGradeId) {
        currentPage = 1; // Reset to first page on search
        fetchAndDisplayStudents(currentGradeId, currentPage, searchTerm);
      }
    }, 300); // Debounce for 300ms
  });

  // Display students
  function displayStudents(data) {
    studentList.innerHTML = "";
    const startIndex = (data.current_page - 1) * studentsPerPage;

    data.students.forEach((student, index) => {
      const globalIndex = startIndex + index + 1;
      const li = document.createElement("li");
      li.className = "student-card";

      const imageUrl =
        student.image ||
        "http://localhost/voting_system/uploads/default-avatar.png";

      li.innerHTML = `
        <div class="student-number">${globalIndex}</div>
        <img class="student-image" src="${imageUrl}" alt="${student.student_name}"
        onerror="this.src='http://localhost/voting_system/uploads/default-avatar.png'">
        <div class="student-info">
          <p class="student-id">Reg No: ${student.registration_number}</p>
          <h3 class="student-name">${student.student_name}</h3>
        </div>
      `;

      studentList.appendChild(li);
    });
  }

  // Update pagination controls
  function updatePagination(data) {
    paginationContainer.innerHTML = "";
    const totalPages = data.total_pages;

    if (totalPages <= 1) return;

    // Previous button
    const prevButton = document.createElement("button");
    prevButton.className = "pagination-btn";
    prevButton.innerHTML = "←";
    prevButton.disabled = data.current_page === 1;
    prevButton.addEventListener("click", () => {
      if (data.current_page > 1) {
        fetchAndDisplayStudents(currentGradeId, data.current_page - 1, searchInput.value.trim());
      }
    });
    paginationContainer.appendChild(prevButton);

    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
      const pageButton = document.createElement("button");
      pageButton.className = `pagination-btn ${i === data.current_page ? "active" : ""}`;
      pageButton.textContent = i;
      pageButton.addEventListener("click", () => {
        fetchAndDisplayStudents(currentGradeId, i, searchInput.value.trim());
      });
      paginationContainer.appendChild(pageButton);
    }

    // Next button
    const nextButton = document.createElement("button");
    nextButton.className = "pagination-btn";
    nextButton.innerHTML = "→";
    nextButton.disabled = data.current_page === totalPages;
    nextButton.addEventListener("click", () => {
      if (data.current_page < totalPages) {
        fetchAndDisplayStudents(currentGradeId, data.current_page + 1, searchInput.value.trim());
      }
    });
    paginationContainer.appendChild(nextButton);
  }

  // Function to update grade info with PDF download button
  function updateGradeInfo(data) {
    const gradeInfoHtml = `
      <div class="grade-header">
        <h2 class="grade-name" id="gradeName">${data.grade_name}</h2>
        <a href="http://localhost/voting_system/server/controller/student/download_pdf.php?grade_id=${currentGradeId}" 
           class="pdf-download-btn" 
           title="Download PDF"
           target="_blank">
          <i class="fas fa-file-pdf"></i>
        </a>
      </div>
      <p class="student-count">Total Students: <span id="studentCount">${data.total_count}</span></p>
    `;
    document.querySelector('.grade-info').innerHTML = gradeInfoHtml;
  }

  // Main function to fetch and display students
  function fetchAndDisplayStudents(gradeId, page, searchTerm = '') {
    fetchStudents(gradeId, page, searchTerm)
      .then(data => {
        displayStudents(data);
        updatePagination(data);
        updateGradeInfo(data);
        currentPage = data.current_page;
      })
      .catch(error => {
        console.error("Error fetching student data:", error);
        alert(error.message);
      });
  }

  // Update students when grade selection changes
  gradeSelect.addEventListener("change", () => {
    const gradeId = gradeSelect.value;
    if (!gradeId) return;

    currentGradeId = gradeId;
    searchInput.value = ""; // Reset search when grade changes
    fetchAndDisplayStudents(gradeId, 1);
  });
});

