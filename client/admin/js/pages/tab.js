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
        .then(responseData => {
            if (responseData && responseData.data && Object.keys(responseData.data).length > 0) {
                let tableHTML = '<table><thead><tr><th>ID</th><th>Index No</th><th>Student Name</th><th>Year</th><th>NIC</th><th>Results</th><th>Download Results</th></tr></thead><tbody>';

                // Populate the rows with student data
                Object.values(responseData.data).forEach(student => {
                    let resultsHTML = '<div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start;">';
                    student.results.forEach(result => {
                        resultsHTML += `<div>${result.subject_name}: ${result.result}</div>`;
                    });
                    resultsHTML += '</div>';

                    tableHTML += `
                        <tr>
                            <td>${student.id}</td>
                            <td>${student.index_no}</td>
                            <td>${student.student_name}</td>
                            <td>${student.year}</td>
                            <td>${student.nic}</td>
                            <td>${resultsHTML}</td>
                            <td><a href="http://localhost/voting_system/server/controller/results/generate_pdf.php?student_id=${student.student_id}" target="_blank">${student.student_name}.pdf</a></td>
                        </tr>`;
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
