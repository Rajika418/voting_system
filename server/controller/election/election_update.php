<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

// Set headers for JSON response
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

// Function to clean input data
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if it's a PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get the ID from the URL
    $url_components = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    $id = end($url_components);

    if (!is_numeric($id)) {
        echo json_encode(array("status" => "error", "message" => "Invalid election ID."));
        exit;
    }

    // Get the raw PUT data
    $put_data = file_get_contents("php://input");
    $data = json_decode($put_data, true);

    $year = isset($data['year']) ? clean_input($data['year']) : null;
    $electionName = isset($data['electionName']) ? clean_input($data['electionName']) : null;
    $nominationStart = isset($data['nominationStart']) ? clean_input($data['nominationStart']) : null;
    $nominationEnd = isset($data['nominationEnd']) ? clean_input($data['nominationEnd']) : null;
    $electionStart = isset($data['electionStart']) ? clean_input($data['electionStart']) : null;
    $electionEnd = isset($data['electionEnd']) ? clean_input($data['electionEnd']) : null;

    // Image handling
    $image = null;
    $image_dir = '../../../uploads/';
    $base_url = 'http://localhost/voting_system/uploads/';

    // Check if a new image file is provided (you may need to adjust this based on how you're sending the file)
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
    echo json_encode(array("status" => "error", "message" => "Invalid request method."));
}

$conn = null; // Close the connection
?>