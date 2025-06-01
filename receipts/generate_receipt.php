<?php
require_once "../config.php";
require_once "../lib/fpdf/fpdf.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid receipt ID.");
}

$receipt_id = (int)$_GET['id'];

// Get receipt info
$sql = "SELECT r.*, s.first_name, s.last_name, s.class_level, s.student_id
        FROM receipts r
        JOIN students s ON r.student_id = s.student_id
        WHERE r.receipt_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Receipt not found.");
}

$data = $result->fetch_assoc();

// Get school info
$school = $conn->query("SELECT * FROM settings WHERE id = 1")->fetch_assoc();
$logo = "../images/" . $school['logo_filename'];

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);

// Logo
if (file_exists($logo)) {
    $pdf->Image($logo, 10, 10, 25);
}

$pdf->Cell(40);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, strtoupper($school['school_name']), 0, 1, 'C');

$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, $school['address_line1'] . ', ' . $school['address_line2'], 0, 1, 'C');
$pdf->Cell(0, 5, $school['city'] . ', ' . $school['state'] . ' - ' . $school['zipcode'], 0, 1, 'C');
$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 8, "RECEIPT", 0, 1, 'C');
$pdf->Ln(3);

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(100, 6, "Receipt No: " . str_pad($data['receipt_id'], 5, '0', STR_PAD_LEFT), 0, 0);
$pdf->Cell(90, 6, "Date: " . $data['issued_date'], 0, 1);

$pdf->Cell(100, 6, "Received From: " . $data['first_name'] . " " . $data['last_name'], 0, 0);
$pdf->Cell(90, 6, "Class: " . $data['class_level'], 0, 1);

$pdf->Cell(190, 6, "Description: " . $data['description'], 0, 1);
$pdf->Cell(190, 6, "Amount: GHS " . number_format($data['amount'], 2), 0, 1);
$pdf->Ln(12);

// Signature
$pdf->Cell(100, 6, "__________________________", 0, 1);
$pdf->Cell(100, 6, "Issued By: " . $data['issued_by'], 0, 1);

$pdf->Output("I", "receipt_" . $receipt_id . ".pdf");