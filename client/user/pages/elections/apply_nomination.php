<div class="elections-content">
    <h1>Apply for Nomination</h1>
    <div id="election-card" class="election-card" style="display: none;">
        <div class="card-header">
            <i class="fas fa-vote-yea"></i>
            <h3 id="election-name">Upcoming Election</h3>
        </div>
        <div class="card-body">
            <p id="election-date"></p>
            <p id="nomination-period"></p>
            <div class="election-info">
                <span id="status" class="status upcoming">Nomination Open</span>
            </div>
        </div>
        <div class="card-footer">
            <button id="apply-nomination-btn" class="btn-primary">Apply for Nomination</button>
        </div>
    </div>
</div>

<!-- Pop-up form for nomination -->
<div id="nomination-popup" class="popup" style="display: none;">
    <div class="popup-content">
        <span class="close-btn">&times;</span>
        <h2 id="election-name-display">Nomination Form</h2>
        <form id="nomination-form">
            <input type="hidden" id="election-id">
            <input type="hidden" id="hidden-student-id">
            <label for="student-id">Student Registration Number:</label>
            <input type="text" id="student-id" required>
            <p id="student-info"></p><br>
            <label for="why">Why do you want to nominate?</label>
            <textarea id="why" required></textarea><br>
            <label for="motive">What is your motive?</label>
            <textarea id="motive" required></textarea><br>
            <label for="what">What will you do if elected?</label>
            <textarea id="what" required></textarea><br>
            <button type="submit" class="btn-primary">Nominate</button>
        </form>
        <div id="toast" class="toast" style="display: none;">Nomination submitted successfully.</div>
    </div>
</div>
