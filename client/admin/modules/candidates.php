<div class="container">
    <h1 id="electionTitle">Candidate List</h1>

    <div id="electionDetails">
        <h2 id="electionName"></h2>
        <p id="electionYear"></p>
    </div>
    
    <div class="search-container">
        <i class="search-icon">🔍</i>
        <input
            type="text"
            id="search"
            placeholder="Search candidates..."
            class="search-input" />
    </div>

    <div class="table-responsive">
        <table id="candidateTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Candidate Name</th>
                    <th>Total Votes</th>
                </tr>
            </thead>
            <tbody id="candidateInfo">
                <!-- Data will be inserted here -->
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <button id="prevPage" onclick="changePage(-1)" class="page-btn">
            &laquo; Previous
        </button>
        <span id="pageInfo">Page 1</span>
        <button id="nextPage" onclick="changePage(1)" class="page-btn">
            Next &raquo;
        </button>
    </div>
</div>