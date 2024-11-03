// JavaScript
let allTeachers = [];
let currentPage = 1;
const teachersPerPage = 6; // 3x2 grid

async function fetchTeachers() {
  try {
    const response = await fetch(
      "http://localhost/voting_system/server/controller/teacher/teacher_get.php"
    );
    const data = await response.json();
    allTeachers = data.data;
    displayTeachers(allTeachers);
    setupPagination(allTeachers);
  } catch (error) {
    console.error("Error fetching teacher data:", error);
  }
}

function displayTeachers(teachers) {
  const startIndex = (currentPage - 1) * teachersPerPage;
  const endIndex = startIndex + teachersPerPage;
  const paginatedTeachers = teachers.slice(startIndex, endIndex);

  const teacherCardsContainer = document.getElementById("teacher-cards");
  teacherCardsContainer.innerHTML = "";

  paginatedTeachers.forEach((teacher, index) => {
    const teacherCard = document.createElement("div");
    teacherCard.classList.add("teacher-card");

    const teacherNumber = document.createElement("div");
    teacherNumber.classList.add("teacher-number");
    teacherNumber.textContent = startIndex + index + 1;

    const teacherImageDiv = document.createElement("div");
    teacherImageDiv.classList.add("teacher-image");
    const teacherImage = document.createElement("img");
    teacherImage.src = teacher.image || "https://via.placeholder.com/150";
    teacherImage.alt = "Teacher Image";
    teacherImageDiv.appendChild(teacherImage);

    const teacherInfoDiv = document.createElement("div");
    teacherInfoDiv.classList.add("teacher-info");
    teacherInfoDiv.innerHTML = `
      <h3>${teacher.teacher_name}</h3>
      <p>${teacher.address}</p>
      <p>Contact: ${teacher.contact_number}</p>
      <p>Email: ${teacher.email}</p>
    `;

    teacherCard.appendChild(teacherNumber);
    teacherCard.appendChild(teacherImageDiv);
    teacherCard.appendChild(teacherInfoDiv);
    teacherCardsContainer.appendChild(teacherCard);
  });
}

function setupPagination(teachers) {
  const totalPages = Math.ceil(teachers.length / teachersPerPage);
  const paginationContainer = document.getElementById("pagination");
  paginationContainer.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const pageButton = document.createElement("button");
    pageButton.classList.add("page-btn");
    if (i === currentPage) pageButton.classList.add("active");
    pageButton.textContent = i;
    pageButton.addEventListener("click", () => {
      currentPage = i;
      displayTeachers(teachers);
      setupPagination(teachers);
    });
    paginationContainer.appendChild(pageButton);
  }
}

function filterTeachers() {
  const searchValue = document
    .getElementById("search-input")
    .value.toLowerCase();
  const filteredTeachers = allTeachers.filter((teacher) =>
    teacher.teacher_name.toLowerCase().includes(searchValue)
  );
  currentPage = 1;
  displayTeachers(filteredTeachers);
  setupPagination(filteredTeachers);
}

document.addEventListener("DOMContentLoaded", fetchTeachers);
