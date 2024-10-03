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
            if (data && data.data.length > 0) {
                let tableHTML = '<table><thead><tr><th>Index No</th><th>Student Name</th>';

                // Add subject headers dynamically
                let subjectHeaders = new Set();
                data.data.forEach(student => {
                    student.results.forEach(subject => {
                        subjectHeaders.add(subject.subject_name);
                    });
                });

                // Create the subject columns
                subjectHeaders.forEach(subject => {
                    tableHTML += `<th>${subject}</th>`;
                });

                tableHTML += '</tr></thead><tbody>';

                // Populate the rows with student data
                data.data.forEach(student => {
                    tableHTML += `<tr><td>${student.index_no}</td><td>${student.student_name}</td>`;

                    // Fill results based on subjects, empty cells for missing subjects
                    subjectHeaders.forEach(subjectName => {
                        const result = student.results.find(subject => subject.subject_name === subjectName);
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
