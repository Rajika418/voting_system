<div class="container1">
    <h1>Elections Management</h1>

    <button id="addElectionButton" class="add-button">Add Election</button>

    <div class="controls">
        <div class="sort-controls">
            <label for="sortOrder">Sort by Year:</label>
            <button id="sortButton" class="sort-button">Sort <span id="sortArrow">⬇️</span></button>
        </div>
        <div class="search-bar">
            <input type="text" id="searchInput" placeholder="Search by Year">
        </div>
    </div>

    <table id="electionTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Election Name</th>
                <th>Year</th>
                <th>Nomination Start Date</th>
                <th> Nomination End Date</th>
                <th>Election Start Date</th>
                <th> Election End Date</th>
                <th>Details</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="electionBody">
            <!-- Election data will be populated here -->
        </tbody>
    </table>

</div>



<div class="pagination">
    <button id="prevPage">Previous</button>
    <span id="pageInfo"></span>
    <button id="nextPage">Next</button>
</div>



<!-- Add Election Modal -->
<div id="addModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add Election</h2>
        <form id="addElectionForm" enctype="multipart/form-data">
            <label for="electionName">Election Name:</label>
            <input type="text" id="electionName" name="electionName" required>

            <label for="year">Year:</label>
            <input type="number" id="year" name="year" required>

            <label for="nomStart">Nomination Start Date:</label>
            <input type="date" id="nomStart" name="nominationStart" required>

            <label for="nomEnd">Nomination End Date:</label>
            <input type="date" id="nomEnd" name="nominationEnd" required>

            <label for="eleStart">Election Start Date:</label>
            <input type="date" id="eleStart" name="electionStart" required>

            <label for="eleEnd">Election End Date:</label>
            <input type="date" id="eleEnd" name="electionEnd" required>

            <!-- Image Upload -->
            <label for="electionImage">Election Image:</label>
            <input type="file" id="electionImage" name="image" accept="image/*">

            <button type="submit">Add Election</button>
        </form>
    </div>
</div>

<!-- Update Election Modal -->
<div id="updateModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Edit Election</h2>
        <form id="updateForm">
            <label for="updateElectionName">Election Name:</label>
            <input type="text" id="updateElectionName" name="electionName" required>

            <label for="updateYear">Year:</label>
            <input type="number" id="updateYear" name="year" required>

            <label for="updateNomStart">Nomination Start Date:</label>
            <input type="date" id="updateNomStart" name="nominationStart" required>

            <label for="updateNomEnd">Nomination End Date:</label>
            <input type="date" id="updateNomEnd" name="nominationEnd" required>

            <label for="updateEleStart">Election Start Date:</label>
            <input type="date" id="updateEleStart" name="electionStart" required>

            <label for="updateEleEnd">Election End Date:</label>
            <input type="date" id="updateEleEnd" name="electionEnd" required>

            <button type="submit">Update Election</button>
        </form>
    </div>
</div>