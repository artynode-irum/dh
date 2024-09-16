<?php
require('fpdf/fpdf.php');
include 'include/config.php';

session_start();

if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'patient' && $_SESSION['role'] != 'doctor' && $_SESSION['role'] != 'admin')) {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: telehealth.php");
    exit();
}

$appointment_id = $_GET['id'];

// Determine the query based on user role
if ($_SESSION['role'] == 'patient') {
    $query = "SELECT appointments.id, doctor.username as doctor_name, appointments.description, appointments.request_date_time, appointments.status, appointments.video_link, appointments.prescription 
              FROM appointments 
              JOIN doctor ON appointments.doctor_id = doctor.id 
              WHERE appointments.id = ? AND appointments.patient_id = (SELECT id FROM patient WHERE username = ?)";
} elseif ($_SESSION['role'] == 'doctor') {
    $query = "SELECT appointments.id, patient.username as patient_name, appointments.description, appointments.request_date_time, appointments.status, appointments.video_link, appointments.prescription 
              FROM appointments 
              JOIN patient ON appointments.patient_id = patient.id 
              WHERE appointments.id = ? AND appointments.doctor_id = (SELECT id FROM doctor WHERE username = ?)";
} elseif ($_SESSION['role'] == 'admin') {
    $query = "SELECT appointments.id, patient.username as patient_name, doctor.username as doctor_name, appointments.description, appointments.request_date_time, appointments.status, appointments.video_link, appointments.prescription 
              FROM appointments 
              JOIN patient ON appointments.patient_id = patient.id 
              JOIN doctor ON appointments.doctor_id = doctor.id 
              WHERE appointments.id = ?";
}

$stmt = $conn->prepare($query);

// Bind parameters based on user role
if ($_SESSION['role'] == 'admin') {
    $stmt->bind_param("i", $appointment_id);
} else {
    $stmt->bind_param("is", $appointment_id, $_SESSION['username']);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: telehealth.php");
    exit();
}

$row = $result->fetch_assoc();

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Add an image (e.g., a logo) at the top
// Adjust the path, width, height, and position (x, y) as needed
// $pdf->Image('path_to_your_image/logo.png', 10, 10, 30); // Example: (image_path, x, y, width)

// Move the cursor below the image
$pdf->Ln(35); // Adjust to move down according to the image height

// Title
$pdf->Cell(0, 10, 'TeleHealth Appointment Details', 0, 1, 'C');

// Add a line break
$pdf->Ln(10);

// Display different titles based on role
if ($_SESSION['role'] == 'patient' || $_SESSION['role'] == 'admin') {
    // Doctor Name
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Doctor:');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $row['doctor_name'], 0, 1);
}

if ($_SESSION['role'] == 'doctor' || $_SESSION['role'] == 'admin') {
    // Patient Name
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Patient:');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $row['patient_name'], 0, 1);
}

// Description
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Description:');
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, $row['description']);

// Date
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Date:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['request_date_time'], 0, 1);

// Status
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Status:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['status'], 0, 1);

// Video Link
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Video Link:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $row['video_link'], 0, 1);

// Prescription
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Prescription:');
$pdf->SetFont('Arial', '', 12);
$pdf->MultiCell(0, 10, $row['prescription']);

$pdf->Output('D', 'Appointment_Details.pdf'); // D forces download
