<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

// Set headers for JSON response
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

// Function to clean input data
function clean_input($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if it's a POST request and if `_method=PUT` is present
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    // Get the ID from the URL
    $url_components = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $id = end($url_components);

    if (!is_numeric($id)) {
        echo json_encode(array("status" => "error", "message" => "Invalid election ID."));
        exit;
    }

    // Collect form data from POST request
    $year = isset($_POST['year']) ? clean_input($_POST['year']) : null;
    $electionName = isset($_POST['electionName']) ? clean_input($_POST['electionName']) : null;
    $nominationStart = isset($_POST['nominationStart']) ? clean_input($_POST['nominationStart']) : null;
    $nominationEnd = isset($_POST['nominationEnd']) ? clean_input($_POST['nominationEnd']) : null;
    $electionStart = isset($_POST['electionStart']) ? clean_input($_POST['electionStart']) : null;
    $electionEnd = isset($_POST['electionEnd']) ? clean_input($_POST['electionEnd']) : null;

    // Image handling
    $image = null;
    $image_dir = '../../../uploads/';
    $base_url = 'http://localhost/voting_system/uploads/';

    // Check if a new image file is provided
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Allowed file extensions
        $allowedfileExtensions = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $imageFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $image_dir . $imageFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image = $base_url . $imageFileName;
            } else {
                echo json_encode(array("status" => "error", "message" => "File upload failed."));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Invalid file extension."));
            exit;
        }
    }

    // Prepare SQL statement
    $sql = "UPDATE elections SET ";
    $params = array();

    if ($year !== null) {
        $sql .= "year = :year, ";
        $params[':year'] = $year;
    }
    if ($electionName !== null) {
        $sql .= "election_name = :election_name, ";
        $params[':election_name'] = $electionName;
    }
    if ($nominationStart !== null) {
        $sql .= "nom_start_date = :nom_start_date, ";
        $params[':nom_start_date'] = $nominationStart;
    }
    if ($nominationEnd !== null) {
        $sql .= "nom_end_date = :nom_end_date, ";
        $params[':nom_end_date'] = $nominationEnd;
    }
    if ($electionStart !== null) {
        $sql .= "ele_start_date = :ele_start_date, ";
        $params[':ele_start_date'] = $electionStart;
    }
    if ($electionEnd !== null) {
        $sql .= "ele_end_date = :ele_end_date, ";
        $params[':ele_end_date'] = $electionEnd;
    }
    if ($image !== null) {
        $sql .= "image = :image, ";
        $params[':image'] = $image;
    }

    // Remove trailing comma and space
    $sql = rtrim($sql, ", ");

    $sql .= " WHERE id = :id";
    $params[':id'] = $id;

    try {
        $stmt = $conn->prepare($sql);
        if ($stmt->execute($params)) {
            echo json_encode(array("status" => "success", "message" => "Election updated successfully."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Failed to update election."));
        }
    } catch (PDOException $e) {
        echo json_encode(array("status" => "error", "message" => "Database error: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid request method or missing _method=PUT."));
}

$conn = null; // Close the connection
