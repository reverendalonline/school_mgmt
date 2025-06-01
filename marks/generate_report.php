<?php
require_once "../config.php";
require_once "../lib/fpdf/fpdf.php";

if (!isset($_GET['student_id'], $_GET['term'], $_GET['year'])) {
    die("Missing parameters.");
}

$student_id = (int) $_GET['student_id'];
$term = $_GET['term'];
$year = $_GET['year'];

// Fetch school settings
$school = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$logo = "../images/" . $school['logo_filename'];

// Fetch student info
$student = $conn->query("SELECT * FROM students WHERE student_id = $student_id")->fetch_assoc();

// Fetch term dates
$term_info = $conn->query("SELECT * FROM term_dates WHERE term = '$term' AND academic_year = '$year'")->fetch_assoc();

// Fetch attendance
$attendance = $conn->query("SELECT * FROM attendance_summary WHERE student_id = $student_id AND term = '$term' AND academic_year = '$year'")->fetch_assoc();

// Fetch comments
$comments = $conn->query("SELECT * FROM general_comments WHERE student_id = $student_id AND term = '$term' AND academic_year = '$year'")->fetch_assoc();

// Fetch marks
$marks = $conn->query("
    SELECT m.*, s.subject_name 
    FROM marks m
    JOIN subjects s ON m.subject_id = s.subject_id
    WHERE m.student_id = $student_id AND m.term = '$term' AND m.academic_year = '$year'
    ORDER BY s.subject_name
");

// Create PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Header
if (file_exists($logo)) {
    $pdf->Image($logo, 10, 10, 25);
}
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, strtoupper($school['school_name']), 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, $school['address_line1'] . ', ' . $school['address_line2'] . ', ' . $school['city'], 0, 1, 'C');
$pdf->Cell(0, 5, 'Tel: ' . $school['state'] . ' | Zip: ' . $school['zipcode'], 0, 1, 'C');
$pdf->Ln(5);

// Report Title
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, "TERMLY ACADEMIC REPORT", 0, 1, 'C');
$pdf->Ln(3);

// Student Info
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 6, "Name: " . $student['first_name'] . " " . $student['last_name'], 0, 0);
$pdf->Cell(95, 6, "Class: " . $student['class_level'], 0, 1);
$pdf->Cell(95, 6, "Term: " . $term, 0, 0);
$pdf->Cell(95, 6, "Academic Year: " . $year, 0, 1);
$pdf->Cell(95, 6, "Class Size: [fill manually]", 0, 0);
$pdf->Cell(95, 6, "Attendance: " . ($attendance['days_present'] ?? '-') . "/" . ($attendance['total_days'] ?? '-'), 0, 1);
$pdf->Cell(95, 6, "Vacation Date: " . ($term_info['vacation_date'] ?? '-'), 0, 0);
$pdf->Cell(95, 6, "Reopening Date: " . ($term_info['reopening_date'] ?? '-'), 0, 1);
$pdf->Ln(5);

// Table Header
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(55, 7, "Subject", 1);
$pdf->Cell(25, 7, "Class Score", 1);
$pdf->Cell(25, 7, "Exam Score", 1);
$pdf->Cell(25, 7, "Total", 1);
$pdf->Cell(20, 7, "Grade", 1);
$pdf->Cell(40, 7, "Remarks", 1);
$pdf->Ln();

// Table Rows
$pdf->SetFont('Arial', '', 10);
while ($row = $marks->fetch_assoc()) {
    $total = $row['cat1'] + $row['cat2'] + $row['exam'];
    $grade = ($total >= 85) ? 'A' : (($total >= 75) ? 'B' : (($total >= 60) ? 'C' : (($total >= 50) ? 'D' : 'F')));
    $remark = ''; // You can automate remarks if needed

    $pdf->Cell(55, 7, $row['subject_name'], 1);
    $pdf->Cell(25, 7, number_format($row['cat1'] + $row['cat2'], 1), 1);
    $pdf->Cell(25, 7, number_format($row['exam'], 1), 1);
    $pdf->Cell(25, 7, number_format($total, 1), 1);
    $pdf->Cell(20, 7, $grade, 1);
    $pdf->Cell(40, 7, $remark, 1);
    $pdf->Ln();
}
$pdf->Ln(4);

// Grade Key
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 6, "Grade Interpretation:", 0, 1);
$pdf->Cell(0, 6, "A (85–100) Excellent | B (75–84) Very Good | C (60–74) Good | D (50–59) Pass | F (0–49) Fail", 0, 1);
$pdf->Ln(4);

// Comments
$pdf->Cell(0, 6, "Conduct: " . ($comments['conduct'] ?? '-'), 0, 1);
$pdf->Cell(0, 6, "Interest: " . ($comments['interest'] ?? '-'), 0, 1);
$pdf->Cell(0, 6, "Appearance: " . ($comments['appearance'] ?? '-'), 0, 1);
$pdf->Ln(3);
$pdf->Cell(0, 6, "Class Teacher's Remarks: " . ($comments['teacher_remarks'] ?? '-'), 0, 1);
$pdf->Cell(0, 6, "Head of School's Remarks: " . ($comments['head_remarks'] ?? '-'), 0, 1);
$pdf->Ln(10);

// Signatures
$pdf->Cell(95, 6, "_________________________", 0, 0);
$pdf->Cell(95, 6, "_________________________", 0, 1);
$pdf->Cell(95, 6, "Class Teacher", 0, 0);
$pdf->Cell(95, 6, "Head of School", 0, 1);

// Output
$pdf->Output("I", "report_" . $student_id . ".pdf");