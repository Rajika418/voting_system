const API_URL = 'http://localhost/voting_system/server/controller/subject/subject_get.php?action=read';
const RESULT_OPTIONS = ['A', 'B', 'C', 'S', 'W'];

async function fetchSubjects() {
    try {
        const response = await axios.get(API_URL);
        return response.data;
    } catch (error) {
        console.error('Error fetching subjects:', error);
        return null;
    }
}

function createSubjectSelect(subjects, sectionName) {
    const select = document.createElement('select');
    select.className = 'subject-select';
    select.name = `subject_${sectionName}`;
    select.required = true;

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select Subject';
    select.appendChild(defaultOption);

    subjects.forEach(subject => {
        const option = document.createElement('option');
        option.value = subject.subject_id;
        option.textContent = subject.subject_name;
        select.appendChild(option);
    });

    return select;
}

function createResultSelect(sectionName) {
    const select = document.createElement('select');
    select.className = 'result-select';
    select.name = `result_${sectionName}`;
    select.required = true;

    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Select Results';
    select.appendChild(defaultOption);

    RESULT_OPTIONS.forEach(option => {
        const optionElement = document.createElement('option');
        optionElement.value = option;
        optionElement.textContent = option;
        select.appendChild(optionElement);
    });

    return select;
}

function renderSubjects(subjects) {
    const container = document.getElementById('subjectsContainer');
    
    // Render subjects with no section
    const noSectionDiv = document.createElement('div');
    noSectionDiv.className = 'section';
    noSectionDiv.innerHTML = '<div class="section-title">Core Subjects</div>';
    subjects.no_section.forEach(subject => {
        const div = document.createElement('div');
        div.className = 'subject-row';
        div.innerHTML = `
            <label class="subject-name">${subject.subject_name}</label>
        `;
        div.appendChild(createResultSelect(subject.subject_id));
        noSectionDiv.appendChild(div);
    });
    container.appendChild(noSectionDiv);

    // Render subjects with sections
    Object.entries(subjects.sections).forEach(([sectionName, sectionSubjects]) => {
        const sectionDiv = document.createElement('div');
        sectionDiv.className = 'section';
        sectionDiv.innerHTML = `<div class="section-title">${sectionName}</div>`;

        const subjectDiv = document.createElement('div');
        subjectDiv.className = 'subject-row';
        subjectDiv.appendChild(createSubjectSelect(sectionSubjects, sectionName));
        subjectDiv.appendChild(createResultSelect(sectionName));
        sectionDiv.appendChild(subjectDiv);

        container.appendChild(sectionDiv);
    });
}

document.getElementById('resultsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const indexNo = formData.get('indexNo');
    const results = [];

    // Add core subjects (no section)
    document.querySelectorAll('.section:first-child .subject-row').forEach(row => {
        const subjectId = row.querySelector('select').name.split('_')[1];
        const result = row.querySelector('select').value;
        if (result) {
            results.push({ subject_id: subjectId, result, index_no: indexNo });
        }
    });

    // Add selected subjects from each section
    document.querySelectorAll('.section:not(:first-child)').forEach(section => {
        const subjectSelect = section.querySelector('.subject-select');
        const resultSelect = section.querySelector('.result-select');
        if (subjectSelect.value && resultSelect.value) {
            results.push({
                subject_id: subjectSelect.value,
                result: resultSelect.value,
                index_no: indexNo
            });
        }
    });

    console.log('Results to be submitted:', results);

    // Send results to the server
    fetch('http://localhost/voting_system/server/controller/results/ol/ol_post.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(results)
    })
    .then(response => response.json())
    .then(data => {
        console.log('Success:', data);
        alert('Results submitted successfully!');
    })
    .catch((error) => {
        console.error('Error:', error);
        alert('An error occurred while submitting results. Please try again.');
    });
});

// Function to fetch data from the GET API when the index number changes
function fetchData() {
    const indexNo = document.getElementById('indexNo').value;
    
    if (indexNo) {
        axios.get(`http://localhost/voting_system/server/controller/results/ol/admission_get.php?index_no=${indexNo}`)
            .then(response => {
                if (response.data.status === 'success') {
                    const studentData = response.data.data;
                    document.getElementById('studentInfo').innerHTML = `
                        <p><strong>Student Name:</strong> ${studentData.student_name}</p>
                        <p><strong>NIC:</strong> ${studentData.nic}</p>
                        <p><strong>Year:</strong> ${studentData.year}</p>
                        <p><strong>Exam Name:</strong> ${studentData.exam_name}</p>
                    `;
                } else {
                    document.getElementById('studentInfo').innerHTML = `<p>${response.data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('studentInfo').innerHTML = `<p>Error fetching data. Please try again.</p>`;
            });
    } else {
        document.getElementById('studentInfo').innerHTML = '';
    }
}

// Add event listener to the index number input
document.getElementById('indexNo').addEventListener('input', fetchData);

(async function init() {
    const subjects = await fetchSubjects();
    if (subjects) {
        renderSubjects(subjects);
    } else {
        document.getElementById('subjectsContainer').innerHTML = '<p>Error loading subjects. Please try again later.</p>';
    }
})();


