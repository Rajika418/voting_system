<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

// Set the response header to JSON
header('Content-Type: application/json');

// Get pagination, sort, and search parameters from the request (if any)
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Default to page 1
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default limit to 10 per page
$year = isset($_GET['year']) ? intval($_GET['year']) : null; // Filter by year if provided
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'DESC'; // Sort order default is DESC

// Calculate offset for pagination
$offset = ($page - 1) * $limit;

try {
    // Base SQL query
    $sql = "SELECT * FROM elections";

    // If a year filter is applied, add a WHERE clause
    if ($year) {
        $sql .= " WHERE year = :year"; // Assuming 'year' is the column name for filtering
    }

    // Add sorting order by year column
    $sql .= " ORDER BY year $sortOrder"; // Sort by 'year'

    // Add pagination (LIMIT and OFFSET)
    $sql .= " LIMIT :limit OFFSET :offset";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    if ($year) {
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    }
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    // Execute the statement
    $stmt->execute();

    // Fetch all results
    $elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get the total number of elections for pagination
    $countSql = "SELECT COUNT(*) FROM elections";
    if ($year) {
        $countSql .= " WHERE year = :year"; // Change to filter by 'year'
    }
    $countStmt = $conn->prepare($countSql);
    if ($year) {
        $countStmt->bindParam(':year', $year, PDO::PARAM_INT);
    }
    $countStmt->execute();
    $totalElections = $countStmt->fetchColumn();

    // Calculate total pages
    $totalPages = ceil($totalElections / $limit);

    // Return response as JSON
    if ($elections) {
        echo json_encode(array(
            "status" => "success",
            "data" => $elections,
            "total" => $totalElections,
            "page" => $page,
            "totalPages" => $totalPages
        ));
    } else {
        echo json_encode(array("status" => "success", "data" => [], "total" => 0, "page" => $page, "totalPages" => 0));
    }
} catch (PDOException $e) {
    echo json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
}

$conn = null; // Close the connection
