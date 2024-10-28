<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>General Certificate of Education</title>
    <link rel="stylesheet" href="./assets/css/result.css" />
    <script src="./js/tab.js" defer></script>
    <script src="./js/popup.js" defer></script>
    <script src="./js/admission_popup.js" defer></script>
    <script src="./js/add_result.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.3.4/axios.min.js"></script>
</head>

<body>
    <div class="form-btn">
        <!-- Admission Button -->
        <button type="button" id="openAdmissionForm">Add Admission</button>
        <button type="button" id="openResultsForm">Enter Results</button>
    </div>
    <!-- Popup Form for Admission -->
    <div id="admissionPopup" class="popup-form" style="display: none">
        <button type="button" class="close-button" id="closeAdmissionForm">
            ×
        </button>
        <!-- Close Button -->
        <form id="admissionForm">
            <h2>Student Admission Form</h2>

            <!-- Buttons to Select Admission Type -->
            <button type="button" id="olButton">O/L Admission</button>
            <button type="button" id="alButton">A/L Admission</button>

            <!-- Disabled form fields initially -->
            <fieldset id="admissionFields" disabled>
                <label for="studentName">Student Name:</label>
                <select id="studentName" name="studentName" required>
                    <option value="">Select Student</option>
                </select><br />

                <p id="guardianInfo"></p>

                <label for="nic">NIC:</label>
                <input type="text" id="nic" name="nic" required /><br />

                <label for="year">Year:</label>
                <input type="text" id="year" name="year" required /><br />

                <label for="examName">Exam Name:</label>
                <input type="text" id="examName" name="examName" required /><br />

                <label for="indexNo1">Index No:</label>
                <input type="text" id="indexNo1" name="indexNo1" required /><br />

                <!-- Centering the submit button -->
                <div style="text-align: center">
                    <button type="submit">Submit</button>
                </div>
            </fieldset>
        </form>
    </div>
    <!-- Popup Form for Results -->
    <div id="resultsPopup" class="popup-form" style="display: none">
        <button type="button" class="close-button" id="closeResultsForm">
            ×
        </button>
        <div style="text-align: center; margin-top: 15px">
            <button type="button" id="ol_Button">O/L Results</button>
            <button type="button" id="al_Button">A/L Results</button>
        </div>

        <form id="resultsForm">
            <h2 id="formTitle">General Certificate of Education</h2>
            <div class="index-container">
                <div>
                    <label for="indexNo">Index No:</label>
                    <input type="text" id="indexNo" name="indexNo" required />
                </div>
                <div id="studentInfo"></div>
            </div>
            <div id="subjectsContainer"></div>
            <div style="text-align: center">
                <button type="submit">Submit Results</button>
            </div>
        </form>
    </div>

    <div class="tab-container">
        <!-- Tab buttons -->
        <div class="tab-buttons">
            <button class="tab-button" data-tab="olResultsTab">O/L Results</button>
            <button class="tab-button" data-tab="alResultsTab">A/L Results</button>
        </div>

        <div class="controls">
            <input type="text" id="search-box" placeholder="Search ...">
            <button id="sort-year">Sort by Exam Year
                <span class="sort-icon">
                    <i class="up-arrow">▲</i>
                    <i class="down-arrow">▼</i>
                </span>
            </button>
            <button id="sort-name">Sort by Name
                <span class="sort-icon">
                    <i class="up-arrow">▲</i>
                    <i class="down-arrow">▼</i>
                </span>
            </button>
        </div>

        <!-- Tab content: O/L Results -->
        <div id="olResultsTab" class="tab-content">
            <h3>O/L Results</h3>
            <div id="olResultsTable"></div>
        </div>

        <!-- Tab content: A/L Results -->
        <div id="alResultsTab" class="tab-content">
            <h3>A/L Results</h3>
            <div id="alResultsTable"></div>
        </div>
    </div>

    <div class="pagination-controls"></div>


    </div>
</body>

</html>