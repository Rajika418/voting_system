let electionId = null;

function extractelectionId() {
  const hash = window.location.hash;
  const match = hash.match(/#\/elections\/nominations\/(\d+)/);
  if (match && match[1]) {
    electionId = parseInt(match[1]);
    console.log("Extracted election ID:", electionId);
  } else {
    console.error("Failed to extract election ID from URL");
  }
}

async function fetchNominationData() {
  if (!electionId) {
    console.error("Election ID is not set");
    return;
  }

  try {
    const response = await fetch(
      `http://localhost/voting_system/server/controller/election/nomination/nomination_get.php?id=${electionId}`
    );
    const data = await response.json();

    if (data.status === "success") {
      renderNominationData(data.data);
    } else {
      console.error("Failed to fetch nomination data:", data.message);
    }
  } catch (error) {
    console.error("Error fetching nomination data:", error);
  }
}

function renderNominationData(nomination) {
  const nominationInfo = document.getElementById("nominationInfo");
  if (!nominationInfo) {
    console.error("Nomination info element not found");
    return;
  }

  nominationInfo.innerHTML = `
        <h2>Nomination Information</h2>
        <p><strong>Election:</strong> ${nomination.election_name}</p>
        <p><strong>Position:</strong> ${nomination.position}</p>
        <p><strong>Nomination Start Date:</strong> ${new Date(
          nomination.start_date
        ).toLocaleDateString()}</p>
        <p><strong>Nomination End Date:</strong> ${new Date(
          nomination.end_date
        ).toLocaleDateString()}</p>
        <!-- Add more nomination details as needed -->
    `;
}

export function init() {
  console.log("Initializing nomination page");
  extractelectionId();
}

export function render() {
  console.log("Rendering nomination page");
  fetchNominationData();
}
