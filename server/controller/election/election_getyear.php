<?php
// Include the database configuration file
require '../../db_config.php';

// Get current year
$currentYear = date("Y");

// Prepare the SQL statement
$sql = "SELECT id, year, election_name, nom_start_date, nom_end_date, ele_start_date, ele_end_date, image 
        FROM elections 
        WHERE year = :currentYear";

$stmt = $conn->prepare($sql);
$stmt->bindParam(':currentYear', $currentYear, PDO::PARAM_INT);
$stmt->execute();

// Fetch the election details if any
$elections = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the result as JSON
header('Content-Type: application/json');
echo json_encode($elections);

?>
