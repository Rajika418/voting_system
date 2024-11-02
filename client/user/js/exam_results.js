
let currentYear = '';
let currentExam = 'o/l';

function debugLog(message, data) {
    console.log(message, data);
    const debugDiv = document.getElementById('debug');
    debugDiv.innerHTML += `<p>${message}: ${JSON.stringify(data)}</p>`;
}

// Fetch and display years
async function fetchYears() {
    try {
        debugLog('Fetching years', 'Started');
        const response = await fetch('http://localhost/voting_system/server/controller/results/get_examyear.php?action=read');
        const data = await response.json();
        debugLog('Years data received', data);
        
        if (data.status === 'success') {
            const yearsContainer = document.getElementById('yearsContainer');
            yearsContainer.innerHTML = ''; // Clear existing content
            data.years.forEach(yearObj => {
                const btn = document.createElement('button');
                btn.className = 'year-btn';
                btn.textContent = yearObj.year;
                btn.onclick = () => selectYear(yearObj.year);
                yearsContainer.appendChild(btn);
            });
            debugLog('Years buttons created', data.years);
        }
    } catch (error) {
        debugLog('Error fetching years', error.message);
        console.error('Error fetching years:', error);
    }
}

// Handle year selection
function selectYear(year) {
    debugLog('Year selected', year);
    currentYear = year;
    document.getElementById('resultsContainer').classList.add('active');
    document.querySelectorAll('.year-btn').forEach(btn => {
        btn.style.backgroundColor = btn.textContent === year ? '#0056b3' : '#007bff';
    });
    showResults(currentExam);
}

// Show results based on exam type
async function showResults(examType) {
    debugLog('Showing results for', { examType, currentYear });
    currentExam = examType;
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.toggle('active', btn.textContent.includes(examType.toUpperCase()));
    });

    try {
        const url = `http://localhost/voting_system/server/controller/results/ol_result_get.php?action=read&year=${examType}`;
        debugLog('Fetching results from URL', url);
        
        const response = await fetch(url);
        const data = await response.json();
        debugLog('Results data received', data);
        
        const filteredResults = data.data.filter(result => result.year === currentYear);
        debugLog('Filtered results', filteredResults);

        const resultsTable = document.getElementById('resultsTable');
        let html = `
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Index No</th>
                        <th>Student Name</th>
                        <th>NIC</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
        `;

        if (filteredResults.length === 0) {
            html += `
                <tr>
                    <td colspan="4" style="text-align: center;">No results found for year ${currentYear}</td>
                </tr>
            `;
        } else {
            filteredResults.forEach(result => {
                html += `
                    <tr>
                        <td>${result.index_no}</td>
                        <td>${result.student_name}</td>
                        <td>${result.nic}</td>
                        <td>${result.results.map(r => `Subject ${r.subject_id}: ${r.result}`).join('<br>')}</td>
                    </tr>
                `;
            });
        }

        html += '</tbody></table>';
        resultsTable.innerHTML = html;
        debugLog('Table HTML generated', { rowCount: filteredResults.length });
    } catch (error) {
        debugLog('Error fetching results', error.message);
        console.error('Error fetching results:', error);
        document.getElementById('resultsTable').innerHTML = `
            <div style="color: red; padding: 20px;">
                Error loading results. Please try again later.
            </div>
        `;
    }
}

// Initialize the page
fetchYears();
