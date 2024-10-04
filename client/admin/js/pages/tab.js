const tabButtons = document.querySelectorAll(".tab-button");
const tabContents = document.querySelectorAll(".tab-content");

tabButtons.forEach((button) => {
    button.addEventListener("click", function () {
        // Remove 'active' class from all buttons and content
        tabButtons.forEach((btn) => btn.classList.remove("active"));
        tabContents.forEach((content) => content.classList.remove("active"));

        // Add 'active' class to the clicked button and corresponding tab content
        this.classList.add("active");
        const tabId = this.getAttribute("data-tab");
        document.getElementById(tabId).classList.add("active");
    
        // Fetch and display results based on the clicked tab
        if (tabId === "olResultsTab") {
            fetchResults('o/l');
        } else {
            fetchResults('A/L');
        }
    });
});

// Fetch and display results based on the year (O/L or A/L)
function fetchResults(year) {
    const resultsTable = document.getElementById(year === 'o/l' ? 'olResultsTable' : 'alResultsTable');
    resultsTable.innerHTML = ''; // Clear previous results

    const url = `http://localhost/voting_system/server/controller/results/ol_result_get.php?action=read&year=${year}`;
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data && data.data.length > 0 && data.subjects.length > 0) {
                let tableHTML = '<table><thead><tr><th>Index No</th><th>Student Name</th>';

                // Add subject headers from the response's subjects array
                const subjectHeaders = data.subjects;

                subjectHeaders.forEach(subject => {
                    tableHTML += `<th>${subject.subject_name}</th>`;
                });

                tableHTML += '</tr></thead><tbody>';

                // Populate the rows with student data
                data.data.forEach(student => {
                    tableHTML += `<tr><td>${student.index_no}</td><td>${student.student_name}</td>`;

                    // Display results for each subject, or show '-' if no result for that subject
                    subjectHeaders.forEach(subject => {
                        const result = student.results.find(r => r.subject_name === subject.subject_name);
                        tableHTML += `<td>${result ? result.result : '-'}</td>`;
                    });

                    tableHTML += '</tr>';
                });

                tableHTML += '</tbody></table>';
                resultsTable.innerHTML = tableHTML;
            } else {
                resultsTable.innerHTML = '<p>No results available.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching results:', error);
            resultsTable.innerHTML = '<p>Error fetching results.</p>';
        });
}

// Set the first tab as active by default
document.querySelector(".tab-button").click();

export function init() {
    fetchResults(); 
}
