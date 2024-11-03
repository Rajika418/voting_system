<?php
header('Content-Type: application/pdf');
require '../../db_config.php';
require('../fpdf/fpdf.php');

if (!isset($_GET['grade_id'])) {
    die('Grade ID is required');
}

$grade_id = $_GET['grade_id'];

// First, get the grade name
$gradeStmt = $conn->prepare("SELECT grade_name FROM grade WHERE grade_id = :grade_id");
$gradeStmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);
$gradeStmt->execute();
$grade = $gradeStmt->fetch(PDO::FETCH_ASSOC);

// Fetch students
$stmt = $conn->prepare("
    SELECT s.student_id, s.student_name, s.father_name, s.registration_number
    FROM student s
    WHERE s.grade_id = :grade_id
    ORDER BY s.student_name
");
$stmt->bindValue(':grade_id', $grade_id, PDO::PARAM_INT);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

class PDF extends FPDF {
    // Page header
    function Header() {
        // Add logo if needed
        // $this->Image('logo.png',10,6,30);
        
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Move to the right
        $this->Cell(60);
        // Title
        $this->Cell(70,10,'Students List of Kalaimahal',0,0,'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Create PDF instance
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);

// Add grade name
$pdf->Cell(0,10,$grade['grade_name'] . ' - Students List',0,1,'C');
$pdf->Ln(5);

// Table headers
$pdf->SetFillColor(200,200,200);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(10,10,'No',1,0,'C',true);
$pdf->Cell(40,10,'Reg Number',1,0,'C',true);
$pdf->Cell(70,10,'Student Name',1,0,'C',true);
$pdf->Cell(70,10,'Father Name',1,1,'C',true);

// Table content
$pdf->SetFont('Arial','',10);
$i = 1;
foreach($students as $student) {
    // Check if we need a new page
    if($pdf->GetY() > 250) {
        $pdf->AddPage();
        
        // Reprint headers on new page
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(10,10,'No',1,0,'C',true);
        $pdf->Cell(40,10,'Reg Number',1,0,'C',true);
        $pdf->Cell(70,10,'Student Name',1,0,'C',true);
        $pdf->Cell(70,10,'Father Name',1,1,'C',true);
        $pdf->SetFont('Arial','',10);
    }
    
    $pdf->Cell(10,10,$i,1,0,'C');
    $pdf->Cell(40,10,$student['registration_number'],1,0,'L');
    $pdf->Cell(70,10,$student['student_name'],1,0,'L'); // Removed utf8_decode
    $pdf->Cell(70,10,$student['father_name'],1,1,'L'); // Removed utf8_decode
    $i++;
}

// Output the PDF
$pdf->Output('D', $grade['grade_name'] . '_students.pdf');