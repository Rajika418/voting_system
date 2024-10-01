// Function to handle opening and closing of popups, ensuring only one is open at a time
function togglePopup(popupType, action) {
    const popupIds = ['admissionPopup', 'resultsPopup'];

    // Close all popups before opening the new one
    popupIds.forEach(id => {
        const popupElement = document.getElementById(id);
        popupElement.style.display = 'none';
    });

    let popupId;

    switch (popupType) {
        case 'admission':
            popupId = 'admissionPopup';
            break;
        case 'results':
            popupId = 'resultsPopup';
            break;
        case 'newPopup':
            popupId = 'newPopup';
            break;
        default:
            console.error('Unknown popup type');
            return;
    }

    const popupElement = document.getElementById(popupId);

    if (action === 'open') {
        popupElement.style.display = 'block';
    } else if (action === 'close') {
        popupElement.style.display = 'none';
    } else {
        console.error('Unknown action type');
    }
}

// Add event listeners for Admission form buttons
document.getElementById('openAdmissionForm').addEventListener('click', function() {
    togglePopup('admission', 'open');
});

// Close button functionality for the form
document.getElementById('closeAdmissionForm').addEventListener('click', function() {
    togglePopup('admission', 'close');
    admissionFields.disabled = true;  // Reset form to disabled when closed
});

// Add event listeners for Results form buttons
document.getElementById('openResultsForm').addEventListener('click', function() {
    togglePopup('results', 'open');
});
document.getElementById('closeResultsForm').addEventListener('click', function() {
    togglePopup('results', 'close');
});

