let allTeachers = []; // Store all teachers data for filtering

// Function to fetch and display teacher details
async function fetchTeachers() {
    try {
        const response = await fetch('http://localhost/voting_system/server/controller/teacher/teacher_get.php?action=read');
        const data = await response.json();
        
        allTeachers = data.data; // Store teacher data
        displayTeachers(allTeachers); // Display all teachers initially
    } catch (error) {
        console.error('Error fetching teacher data:', error);
    }
}

// Function to display teachers based on filtered data
function displayTeachers(teachers) {
    const teacherCardsContainer = document.getElementById('teacher-cards');
    teacherCardsContainer.innerHTML = ''; // Clear existing content

    teachers.forEach(teacher => {
        const teacherCard = document.createElement('div');
        teacherCard.classList.add('teacher-card');

        // Create the teacher image section
        const teacherImageDiv = document.createElement('div');
        teacherImageDiv.classList.add('teacher-image');
        const teacherImage = document.createElement('img');
        teacherImage.src = teacher.image || 'https://via.placeholder.com/150';
        teacherImage.alt = 'Teacher Image';
        teacherImageDiv.appendChild(teacherImage);
        teacherCard.appendChild(teacherImageDiv);

        // Create the teacher info section
        const teacherInfoDiv = document.createElement('div');
        teacherInfoDiv.classList.add('teacher-info');
        const teacherName = document.createElement('h3');
        teacherName.textContent = teacher.teacher_name;
        const teacherAddress = document.createElement('p');
        teacherAddress.textContent = teacher.address;
        const teacherContact = document.createElement('p');
        teacherContact.textContent = `Contact: ${teacher.contact_number}`;
        const teacherEmail = document.createElement('p');
        teacherEmail.textContent = `Email: ${teacher.email}`;

        teacherInfoDiv.appendChild(teacherName);
        teacherInfoDiv.appendChild(teacherAddress);
        teacherInfoDiv.appendChild(teacherContact);
        teacherInfoDiv.appendChild(teacherEmail);
        teacherCard.appendChild(teacherInfoDiv);

        teacherCardsContainer.appendChild(teacherCard);
    });
}

// Function to filter teachers based on search input
function filterTeachers() {
    const searchValue = document.getElementById('search-input').value.toLowerCase();
    const filteredTeachers = allTeachers.filter(teacher => 
        teacher.teacher_name.toLowerCase().includes(searchValue)
    );
    displayTeachers(filteredTeachers); // Display filtered teachers
}

// Call the function to fetch teachers when the page loads
document.addEventListener('DOMContentLoaded', fetchTeachers);
