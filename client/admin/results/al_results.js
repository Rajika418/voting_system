// Function to fetch data from the GET API when the index number changes
function fetchData() {
    const indexNo = document.getElementById('indexNo2').value;
    console.log("AL index",indexNo);
    
    if (indexNo) {
        axios.get(`http://localhost/voting_system/server/controller/results/admission_get.php?index_no=${indexNo}`)
            .then(response => {
                if (response.data.status === 'success') {
                    const studentData = response.data.data;
                    document.getElementById('studentInfo1').innerHTML = `
                        <p><strong>Student Name:</strong> ${studentData.student_name}</p>
                        <p><strong>NIC:</strong> ${studentData.nic}</p>
                        <p><strong>Year:</strong> ${studentData.year}</p>
                        <p><strong>Exam Name:</strong> ${studentData.exam_name}</p>
                    `;
                } else {
                    document.getElementById('studentInfo1').innerHTML = `<p>${response.data.message}</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching data:', error);
                document.getElementById('studentInfo1').innerHTML = `<p>Error fetching data. Please try again.</p>`;
            });
    } else {
        document.getElementById('studentInfo1').innerHTML = '';
    }
}

document.getElementById('indexNo2').addEventListener('input', fetchData);


    // Send results to the server
    fetch('http://localhost/voting_system/server/controller/results/results_post.php?action=create', {
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
