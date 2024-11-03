<?php
require '../../db_config.php';
require('../fpdf/fpdf.php');

// Function to fetch student data
function getStudentData($conn, $student_id)
{
    $sql = "
        SELECT 
            se.id,
            se.index_no,
            se.student_id,
            se.year AS exam_year,
            se.nic,
            s.student_name,
            r.result,
            r.subject_id,
            sub.subject_name
        FROM 
            student_exam se
        LEFT JOIN 
            student s ON se.student_id = s.student_id
        LEFT JOIN 
            results r ON se.id = r.exam_id
        LEFT JOIN 
            subjects sub ON r.subject_id = sub.subject_id
        WHERE 
            se.student_id = :student_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();

    $student_data = null;
    $results = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (!$student_data) {
            $student_data = [
                'id' => $row['id'],
                'index_no' => $row['index_no'],
                'student_id' => $row['student_id'],
                'student_name' => $row['student_name'],
                'year' => $row['exam_year'],
                'nic' => $row['nic'],
                'results' => []
            ];
        }
        $student_data['results'][] = [
            'subject_id' => $row['subject_id'],
            'subject_name' => $row['subject_name'],
            'result' => $row['result']
        ];
    }

    return $student_data;
}

// Check if student_id is provided
if (!isset($_GET['student_id'])) {
    die("Student ID not provided");
}

$student_id = $_GET['student_id'];

try {
    $student_data = getStudentData($conn, $student_id);

    if (!$student_data) {
        die("Student data not found");
    }

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('Arial', 'B', 16);

    // Title
    $pdf->Cell(0, 10, 'Student Results', 0, 1, 'C');
    $pdf->Ln(10);

    // Student Information
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Name: ' . $student_data['student_name'], 0, 1);
    $pdf->Cell(0, 10, 'Index No: ' . $student_data['index_no'], 0, 1);
    $pdf->Cell(0, 10, 'Year: ' . $student_data['year'], 0, 1);
    $pdf->Cell(0, 10, 'NIC: ' . $student_data['nic'], 0, 1);
    $pdf->Ln(10);

    // Results Table
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(95, 10, 'Subject', 1);
    $pdf->Cell(95, 10, 'Result', 1);
    $pdf->Ln();

    $pdf->SetFont('Arial', '', 12);
    foreach ($student_data['results'] as $result) {
        $pdf->Cell(95, 10, $result['subject_name'], 1);
        $pdf->Cell(95, 10, $result['result'], 1);
        $pdf->Ln();
    }

    // Output PDF
    $pdf->Output('D', $student_data['student_name'] . '_' . $student_data['year'] . '_results.pdf');
} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

// Close connection
$conn = null;
