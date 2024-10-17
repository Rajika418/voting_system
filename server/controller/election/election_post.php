<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With");

// Function to clean input data
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = clean_input($_POST['year']);
    $electionName = clean_input($_POST['electionName']);
    $nominationStart = clean_input($_POST['nominationStart']);
    $nominationEnd = clean_input($_POST['nominationEnd']);
    $electionStart = clean_input($_POST['electionStart']);
    $electionEnd = clean_input($_POST['electionEnd']);

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
            // Generate a unique file name to avoid overwriting
            $imageFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $dest_path = $image_dir . $imageFileName;

            // Try to move the uploaded file
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $image = $base_url . $imageFileName;  // Save the file path to store in the database
            } else {
                echo json_encode(array("status" => "error", "message" => "File upload failed."));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Invalid file extension."));
            exit;
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO elections (year, election_name, nom_start_date, nom_end_date, ele_start_date, ele_end_date, image)
            VALUES (:year, :election_name, :nom_start_date, :nom_end_date, :ele_start_date, :ele_end_date, :image)";

    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':election_name', $electionName);
    $stmt->bindParam(':nom_start_date', $nominationStart);
    $stmt->bindParam(':nom_end_date', $nominationEnd);
    $stmt->bindParam(':ele_start_date', $electionStart);
    $stmt->bindParam(':ele_end_date', $electionEnd);
    $stmt->bindParam(':image', $image);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Election added successfully."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to add election."));
    }
}

$conn = null; // Close the connection
?>
