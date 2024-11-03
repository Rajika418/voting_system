document.addEventListener("DOMContentLoaded", () => {
  // Fetch election data from the API with current year filter
  fetch(
    "http://localhost/voting_system/server/controller/election/election_getyear.php?action=read"
  )
    .then((response) => response.json())
    .then((data) => {
      if (data.status === "success" && data.data.length > 0) {
        const election = data.data[0];

        // Update election details in HTML
        document.getElementById("election-name").textContent =
          election.election_name;
        document.getElementById(
          "election-date"
        ).textContent = `Election Date: ${election.ele_start_date} - ${election.ele_end_date}`;
        document.getElementById(
          "nomination-period"
        ).textContent = `Nomination Period: ${election.nom_start_date} - ${election.nom_end_date}`;

        // Show the election card
        document.getElementById("election-card").style.display = "block";

        // Set the election ID and open nomination form
        document.getElementById("status").addEventListener("click", () => {
          openNominationForm(election.id, election.election_name);
        });
        document.querySelector(".btn-primary").addEventListener("click", () => {
          openNominationForm(election.id, election.election_name);
        });
      } else {
        console.error("No election data found or an error occurred.");
      }
    })
    .catch((error) => console.error("Error fetching election data:", error));

  // Close the pop-up form
  document.querySelector(".close-btn").addEventListener("click", () => {
    document.getElementById("nomination-popup").style.display = "none";
  });

  // Fetch and display student information when registration number is entered
  document.getElementById("student-id").addEventListener("input", function () {
    const regNo = this.value;
    if (regNo) {
      fetch(
        `http://localhost/voting_system/server/controller/election/nomination/reg_no_get.php?registration_number=${regNo}`
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            // Display student info on the form
            document.getElementById(
              "student-info"
            ).textContent = `Student: ${data.student_name}, Grade: ${data.grade_name}`;
            document.getElementById("hidden-student-id").value =
              data.student_id;
          } else {
            document.getElementById("student-info").textContent =
              "Student not found.";
          }
        })
        .catch((error) => console.error("Error fetching student info:", error));
    }
  });

  // Submit the nomination form
  document
    .getElementById("nomination-form")
    .addEventListener("submit", function (event) {
      event.preventDefault();

      const electionId = document.getElementById("election-id").value;
      const studentId = document.getElementById("hidden-student-id").value;
      const why = document.getElementById("why").value;
      const motive = document.getElementById("motive").value;
      const what = document.getElementById("what").value;

      fetch(
        "http://localhost/voting_system/server/controller/election/nomination/nomination_post.php?action=create",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `election_id=${electionId}&student_id=${studentId}&why=${encodeURIComponent(
            why
          )}&motive=${encodeURIComponent(motive)}&what=${encodeURIComponent(
            what
          )}`,
        }
      )
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            const toast = document.getElementById("toast");
            toast.classList.add("show"); // Add a 'show' class to trigger visibility
            toast.textContent = data.message;

            // Close the form and reset inputs
            document.getElementById("nomination-popup").style.display = "none";
            document.getElementById("nomination-form").reset();
            document.getElementById("student-info").textContent = "";

            // Hide the toast after 3 seconds
            setTimeout(() => {
              toast.classList.remove("show");
            }, 3000);
          } else {
            alert(data.message);
          }
        })
        .catch((error) => console.error("Error submitting nomination:", error));
    });

  function openNominationForm(electionId, electionName) {
    document.getElementById("election-id").value = electionId;
    document.getElementById("election-name-display").textContent = electionName;
    document.getElementById("nomination-popup").style.display = "flex";
  }
});
