    <div class="container">
        <h1 class="heading">Exam Results </h1>
        <div class="years-container" id="yearsContainer"></div>
        <div class="results-container" id="resultsContainer">
            <div class="tab-buttons">
                <div class="tab-group">
                    <button class="tab-btn active" onclick="showResults('o/l')">O/L Results</button>
                    <button class="tab-btn" onclick="showResults('A/L')">A/L Results</button>
                </div>
                <div class="search-container">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            type="text"
                            id="studentSearch"
                            placeholder="Search by name or index no..."
                            class="search-input">
                    </div>
                </div>
            </div>
            <div id="resultsTable"></div>
            <div class="pagination"></div>
        </div>
    </div>