// Function to fetch and display teacher details
async function fetchTeachers() {
    try {
        const response = await fetch('http://localhost/voting_system/server/controller/teacher/teacher_get.php?action=read');
        const data = await response.json();
        
        const teacherCardsContainer = document.getElementById('teacher-cards');
        teacherCardsContainer.innerHTML = ''; // Clear existing content

        // Loop through the teacher data and create cards
        data.data.forEach(teacher => {
            const teacherCard = document.createElement('div');
            teacherCard.classList.add('teacher-card');

            // Create the teacher image section
            const teacherImageDiv = document.createElement('div');
            teacherImageDiv.classList.add('teacher-image');
            const teacherImage = document.createElement('img');
            teacherImage.src = teacher.image || 'https://via.placeholder.com/150'; // Default image if none provided
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

            // Append the teacher card to the container
            teacherCardsContainer.appendChild(teacherCard);
        });
    } catch (error) {
        console.error('Error fetching teacher data:', error);
    }
}

// Call the function to fetch teachers when the page loads
document.addEventListener('DOMContentLoaded', fetchTeachers);
