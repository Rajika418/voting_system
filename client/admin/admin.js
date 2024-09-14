// Function to load content dynamically using AJAX
function loadPage(pageUrl) {
    fetch(pageUrl)
        .then(response => response.text()) // Fetch the HTML page as text
        .then(data => {
            document.querySelector('#dynamic-content').innerHTML = data; // Inject the content into the main content area
        })
        .catch(error => console.error('Error:', error)); // Log any errors
}


document.querySelectorAll('.toggleable > a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default anchor behavior
        const submenu = this.parentNode.querySelector('.submenu');
        if (submenu.style.display === 'block') {
            submenu.style.display = 'none';
        } else {
            submenu.style.display = 'block';
        }
    });
});