const indexNoField = document.getElementById("indexNo");
const submitButton = document.getElementById("submitResults");

indexNoField.disabled = true;

let selectedYear1 = "";
const olButton = document.getElementById("ol_Button");
const alButton = document.getElementById("al_Button");
const formTitle = document.getElementById("formTitle");

const API_URL = (year) =>
  `http://localhost/voting_system/server/controller/subject/exam_subject_get.php?action=read&year=${year}`;

// Event listener for O/L button
olButton.addEventListener("click", async () => {
  selectedYear1 = "o/l";
  indexNoField.disabled = false;
  const subjects = await fetchSubjects(selectedYear1);
  formTitle.innerText =
    "General Certificate of Education - Ordinary Level (G.C.E(O/L))";
  if (subjects) {
    renderSubjects(subjects);
  } else {
    document.getElementById("subjectsContainer").innerHTML =
      "<p>Error loading subjects. Please try again later.</p>";
  }
});

// Event listener for A/L button
alButton.addEventListener("click", async () => {
  selectedYear1 = "a/l";
  indexNoField.disabled = false;
  const subjects = await fetchSubjects(selectedYear1);
  formTitle.innerText =
    "General Certificate of Education - Advanced Level (G.C.E(A/L))";
  if (subjects) {
    renderSubjects(subjects);
  } else {
    document.getElementById("subjectsContainer").innerHTML =
      "<p>Error loading subjects. Please try again later.</p>";
  }
});

const RESULT_OPTIONS = ["A", "B", "C", "S", "W"];

async function fetchSubjects(year) {
  console.log(year, "kk");

  try {
    const response = await axios.get(API_URL(year));
    console.log(response.data, "mm");

    return response.data;
  } catch (error) {
    console.error("Error fetching subjects:", error);
    return null;
  }
}

function createSubjectSelect(subjects, sectionName) {
  const select = document.createElement("select");
  select.className = "subject-select";
  select.name = `subject_${sectionName}`;
  select.required = true;

  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "Select Subject";
  select.appendChild(defaultOption);

  subjects.forEach((subject) => {
    const option = document.createElement("option");
    option.value = subject.subject_id;
    option.textContent = subject.subject_name;
    select.appendChild(option);
  });

  return select;
}

function createResultSelect(sectionName) {
  const select = document.createElement("select");
  select.className = "result-select";
  select.name = `result_${sectionName}`;
  select.required = true;

  const defaultOption = document.createElement("option");
  defaultOption.value = "";
  defaultOption.textContent = "Select Results";
  select.appendChild(defaultOption);

  RESULT_OPTIONS.forEach((option) => {
    const optionElement = document.createElement("option");
    optionElement.value = option;
    optionElement.textContent = option;
    select.appendChild(optionElement);
  });

  return select;
}

function renderSubjects(subjects) {
  console.log(subjects, "mj");

  const container = document.getElementById("subjectsContainer");

  // Clear the container before rendering new subjects
  container.innerHTML = "";

  // Render subjects with no section
  const noSectionDiv = document.createElement("div");
  noSectionDiv.className = "section";
  noSectionDiv.innerHTML = '<div class="section-title">Core Subjects</div>';

  const noSectionSubjectsWrapper = document.createElement("div");
  noSectionSubjectsWrapper.className = "subject-rows-wrapper";

  subjects.no_section.forEach((subject) => {
    const div = document.createElement("div");
    div.className = "subject-row";
    div.innerHTML = `
            <label class="subject-name">${subject.subject_name}</label>
        `;
    div.appendChild(createResultSelect(subject.subject_id));
    noSectionSubjectsWrapper.appendChild(div);
  });
  noSectionDiv.appendChild(noSectionSubjectsWrapper);
  container.appendChild(noSectionDiv);

  // Render subjects with sections
  Object.entries(subjects.sections).forEach(
    ([sectionName, sectionSubjects]) => {
      const repeatCount = sectionName === "Subject 1" ? 3 : 1;

      for (let i = 0; i < repeatCount; i++) {
        const sectionDiv = document.createElement("div");
        sectionDiv.className = "section";
        const dynamicSectionName =
          sectionName === "Subject 1" ? `Subject ${i + 1}` : sectionName;
        sectionDiv.innerHTML = `<div class="section-title">${dynamicSectionName}</div>`;

        const subjectDiv = document.createElement("div");
        subjectDiv.className = "subject-row";
        subjectDiv.appendChild(
          createSubjectSelect(sectionSubjects, sectionName)
        );
        subjectDiv.appendChild(createResultSelect(dynamicSectionName));
        //subjectDiv.appendChild(createResultSelect(sectionName));
        sectionDiv.appendChild(subjectDiv);

        container.appendChild(sectionDiv);
      }
    }
  );
}

document.getElementById("resultsForm").addEventListener("submit", function (e) {
  e.preventDefault();
  const formData = new FormData(this);
  const indexNo = formData.get("indexNo");
  const results = [];

  // Add core subjects (no section)
  document
    .querySelectorAll(".section:first-child .subject-row")
    .forEach((row) => {
      const subjectId = row.querySelector("select").name.split("_")[1];
      const result = row.querySelector("select").value;
      if (result) {
        results.push({ subject_id: subjectId, result, index_no: indexNo });
      }
    });

  // Add selected subjects from each section
  document.querySelectorAll(".section:not(:first-child)").forEach((section) => {
    const subjectSelect = section.querySelector(".subject-select");
    const resultSelect = section.querySelector(".result-select");
    if (subjectSelect.value && resultSelect.value) {
      results.push({
        subject_id: subjectSelect.value,
        result: resultSelect.value,
        index_no: indexNo,
      });
    }
  });

  console.log("Results to be submitted:", results);

  // Send results to the server
  fetch(
    "http://localhost/voting_system/server/controller/results/results_post.php?action=create",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(results),
    }
  )
    .then((response) => response.json())
    .then((data) => {
      console.log("Success:", data);
      alert("Results submitted successfully!");
      document.getElementById("resultsPopup").style.display = "none";
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while submitting results. Please try again.");
    });
});

// Function to fetch data from the GET API when the index number changes
function fetchData() {
  const indexNo = document.getElementById("indexNo").value;

  if (indexNo) {
    axios
      .get(
        `http://localhost/voting_system/server/controller/results/admission_get.php?index_no=${indexNo}`
      )
      .then((response) => {
        console.log("kk", response);

        if (response.data.status === "success") {
          const studentData = response.data.data;
          document.getElementById("studentInfo").innerHTML = `
                        <p><strong>Student Name:</strong> ${studentData.student_name}</p>
                        <p><strong>NIC:</strong> ${studentData.nic}</p>
                        <p><strong>Year:</strong> ${studentData.year}</p>
                        <p><strong>Exam Name:</strong> ${studentData.exam_name}</p>
                    `;
        } else {
          document.getElementById(
            "studentInfo"
          ).innerHTML = `<p>${response.data.message}</p>`;
        }
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
        document.getElementById(
          "studentInfo"
        ).innerHTML = `<p>Error fetching data. Please try again.</p>`;
      });
  } else {
    document.getElementById("studentInfo").innerHTML = "";
  }
}

// Add event listener to the index number input
document.getElementById("indexNo").addEventListener("input", fetchData);
