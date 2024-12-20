<?php

// Include the database connection file
require "../../db_config.php";

// Set headers for the API response
// Enable CORS (Cross-Origin Resource Sharing)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods:GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // Check if the 'year' parameter is provided in the request
    if (isset($_GET['year']) && !empty($_GET['year'])) {
        // Retrieve the year parameter from the request
        $year = strtolower($_GET['year']);

        // Query to retrieve data from the 'subjects' table based on the year parameter
        $sql = "SELECT subject_id, year, section, subject_name FROM subjects WHERE LOWER(year) = :year";

        // Prepare and execute the query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':year', $year, PDO::PARAM_STR);
        $stmt->execute();

        // Check if any records are returned
        if ($stmt->rowCount() > 0) {
            // Fetch all rows as an associative array
            $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize arrays for grouped data
            $no_section = []; // For subjects with no section (null)
            $sectioned_subjects = []; // For subjects grouped by section

            // Loop through the subjects and categorize them
            foreach ($subjects as $subject) {
                if (is_null($subject['section']) || empty($subject['section'])) {
                    // Add to the 'no_section' array
                    $no_section[] = $subject;
                } else {
                    // Group subjects by their section
                    if (!isset($sectioned_subjects[$subject['section']])) {
                        $sectioned_subjects[$subject['section']] = [];
                    }
                    $sectioned_subjects[$subject['section']][] = $subject;
                }
            }

            // Combine the results
            $response = [
                "no_section" => $no_section,
                "sections" => $sectioned_subjects
            ];

            // Return the response as JSON
            echo json_encode($response);
        } else {
            // If no records are found, return a message
            echo json_encode(array("message" => "No subjects found for the specified year."));
        }
    } else {
        // If 'year' parameter is not provided, return an error message
        echo json_encode(array("message" => "Year parameter is missing."));
    }
} catch (Exception $e) {
    // Return an error message if there's an exception
    echo json_encode(array("message" => "Error occurred: " . $e->getMessage()));
}
