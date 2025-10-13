<?php
session_start();
include('connection.php');
require('fpdf/fpdf.php');

// Get parameters
$type = $_GET['type'];
$student_id = $_GET['id'];

// Fetch student data
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

if ($type === 'profile') {
    // ========================================
    // PROFILE SUMMARY REPORT
    // ========================================
    $pdf->Cell(0, 10, 'UNIVERSITY PORTAL', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Student Profile Summary', 0, 1, 'C');
    $pdf->Ln(10);
    
    $pdf->Cell(50, 10, 'Name:', 0, 0);
    $pdf->Cell(0, 10, $student['name'] . ' ' . $student['surname'], 0, 1);
    
    $pdf->Cell(50, 10, 'Student ID:', 0, 0);
    $pdf->Cell(0, 10, $student['student_id'], 0, 1);
    
    $pdf->Cell(50, 10, 'Email:', 0, 0);
    $pdf->Cell(0, 10, $student['email'], 0, 1);
    
    $pdf->Cell(50, 10, 'Date of Birth:', 0, 0);
    $pdf->Cell(0, 10, $student['date_of_birth'], 0, 1);
    
    $pdf->Cell(50, 10, 'Course:', 0, 0);
    $pdf->Cell(0, 10, $student['course'], 0, 1);
    
    $pdf->Cell(50, 10, 'Enrollment Date:', 0, 0);
    $pdf->Cell(0, 10, $student['enrollment_date'], 0, 1);
    
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1);
    
    $filename = 'profile_' . $student_id . '.pdf';
    
} else {
    // ========================================
    // REGISTRATION CONFIRMATION
    // ========================================
    $pdf->Cell(0, 10, 'UNIVERSITY PORTAL', 0, 1, 'C');
    $pdf->Cell(0, 10, 'Registration Confirmation', 0, 1, 'C');
    $pdf->Ln(10);
    
    $pdf->Cell(50, 10, 'Student:', 0, 0);
    $pdf->Cell(0, 10, $student['name'] . ' ' . $student['surname'], 0, 1);
    
    $pdf->Cell(50, 10, 'Student ID:', 0, 0);
    $pdf->Cell(0, 10, $student['student_id'], 0, 1);
    
    $pdf->Ln(5);
    
    $pdf->Cell(50, 10, 'Registration Date:', 0, 0);
    $pdf->Cell(0, 10, $student['enrollment_date'], 0, 1);
    
    $pdf->Cell(50, 10, 'Course:', 0, 0);
    $pdf->Cell(0, 10, $student['course'], 0, 1);
    
    define("STATUS_ACTIVE", "Full-Time Student");
    $pdf->Cell(50, 10, 'Status:', 0, 0);
    $pdf->Cell(0, 10, STATUS_ACTIVE, 0, 1);
    
    $pdf->Ln(10);
    $pdf->Cell(0, 10, 'This confirms successful registration.', 0, 1);
    
    $pdf->Ln(5);
    $pdf->Cell(0, 10, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1);
    
    $filename = 'registration_' . $student_id . '.pdf';
}

// Download PDF
$pdf->Output('D', $filename);
?>