<?php
// Include database configuration
require '../../db_config.php';  // Adjust the path based on your directory structure

// Create a connection using PDO

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

    // File upload handling
    $imageFileName = NULL;  // Set default to NULL

    // Check if a file is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileSize = $_FILES['image']['size'];
        $fileType = $_FILES['image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Allowed file extensions
        $allowedfileExtensions = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Generate a unique file name to avoid overwriting
            $imageFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '../../../uploads';  // Absolute path to the uploads directory
            $dest_path = $uploadFileDir . $imageFileName;

            // Try to move uploaded file
            if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                echo json_encode(array("status" => "error", "message" => "File upload failed."));
                exit;
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Invalid file extension."));
            exit;
        }
    }

    // Insert into database using PDO
    $sql = "INSERT INTO elections (year, election_name, nom_start_date, nom_end_date, ele_start_date, ele_end_date, image)
            VALUES (:year, :election_name, :nom_start_date, :nom_end_date, :ele_start_date, :ele_end_date, :image)";

    $stmt = $conn->prepare($sql);
    
    // Binding parameters
    $stmt->bindParam(':year', $year);
    $stmt->bindParam(':election_name', $electionName);
    $stmt->bindParam(':nom_start_date', $nominationStart);
    $stmt->bindParam(':nom_end_date', $nominationEnd);
    $stmt->bindParam(':ele_start_date', $electionStart);
    $stmt->bindParam(':ele_end_date', $electionEnd);
    $stmt->bindParam(':image', $imageFileName);

    if ($stmt->execute()) {
        echo json_encode(array("status" => "success", "message" => "Election added successfully."));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to add election."));
    }
}

$conn = null; // Close the connection
?>
