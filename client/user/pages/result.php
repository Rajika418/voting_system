<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>

</head>
<body>
    <div class="container">
        <h1 class="heading">Exam Results Portal</h1>
        <div class="years-container" id="yearsContainer"></div>
        <div class="results-container" id="resultsContainer">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="showResults('o/l')">O/L Results</button>
                <button class="tab-btn" onclick="showResults('A/L')">A/L Results</button>
            </div>
            <div id="resultsTable"></div>
        </div>
        <div id="debug"></div>
    </div>

 
</body>
</html>