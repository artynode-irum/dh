<?php
include("fpdf/fpdf.php");
include 'include/config.php';

// Start the session and check user authentication
session_start();
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'doctor', 'patient'])) {
    header("Location: index.php");
    exit();
}

$medical_certificate_id = $_GET['id'] ?? null;

if ($medical_certificate_id) {
    // Prepare and execute the query
    $query = "SELECT patient.username AS patient_username, doctor.signature AS doctor_signature, doctor.username AS doctor_username, doctor.aphra AS doctor_aphra, doctor.doctor_name AS doctor_name, patient.email, medical_certificate.description, medical_certificate.prescription, medical_certificate.country, medical_certificate.to_date, medical_certificate.from_date, medical_certificate.created_date, medical_certificate.request_date_time, medical_certificate.reason, medical_certificate.payment, medical_certificate.certificate_type, medical_certificate.illness_description, medical_certificate.name, medical_certificate.created_date_2
              FROM medical_certificate 
              LEFT JOIN patient ON medical_certificate.patient_id = patient.id 
              LEFT JOIN doctor ON medical_certificate.doctor_id = doctor.id    
              WHERE medical_certificate.id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $medical_certificate_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $medical_certificate = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "<p>Database query error.</p>";
        exit();
    }
}

if (!$medical_certificate) {
    echo "<p>Medical certificate not found.</p>";
    exit();
}

// Define a custom class that extends FPDF
class PDF extends FPDF
{
    // Footer method to add footer content
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-20);
        // Set font
        $this->SetFont('Arial', 'I', 8);
        // Page number and website name
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb} | Website: www.doctorhelp.com.au', 0, 0, 'C');
    }
}

// Generate PDF
$pdf = new PDF();
$pdf->AliasNbPages(); // For page numbering
$pdf->AddPage();

// Define padding and border margin
$padding = 10; // Padding inside the border
$border_margin = 10; // Margin from the edges of the page

// Draw page border
$pdf->Rect($border_margin, $border_margin, $pdf->GetPageWidth() - 2 * $border_margin, $pdf->GetPageHeight() - 2 * $border_margin);

// Calculate content area
$page_width = $pdf->GetPageWidth();
$page_height = $pdf->GetPageHeight();
$content_width = $page_width - 2 * ($border_margin + $padding);
$content_height = $page_height - 2 * ($border_margin + $padding);

// Add contact details before the image
$pdf->SetFont("Arial", "", 12); // Set font for contact details
$pdf->SetXY($border_margin + $padding, $border_margin + $padding); // Position for the contact details (X, Y)
$pdf->MultiCell($content_width, 8, 
    "doctorhelp.com.au\n" .
    "info@doctorhelp.com.au\n" .
    "805/220 Collins St,\n" .
    "Melbourne, VIC 3000"
);

// Calculate the X position to center the image within the content area
$image_width = 35; // Width of the image
$image_height = 20; // Height of the image
$x_position = ($content_width - $image_width) / 2 + $border_margin + $padding; // Centering with border margins and padding

// Add PNG image
$pdf->Image("pdf-logo.png", $x_position, $border_margin + $padding + 30, $image_width, $image_height, "png", "https://multiplepromosolutions.com/dh"); // Adjust size as needed

// Set font for heading
$pdf->SetFont("Arial", "B", 18); // Set font family, style (B for bold), and size

// Add the heading below the image
$heading_y = $border_margin + $padding + 30 + $image_height + 10; // Position below the image with some spacing
$pdf->SetXY($border_margin + $padding, $heading_y); // Set position for the heading (X, Y)
$pdf->Cell($content_width, 10, "Medical Certificate", 0, 1, 'C'); // Add heading centered

// Set font for "Patient Name" text with custom size and weight
$pdf->SetFont("Arial", "", 10); // Set font size to 18 and bold weight

// Add a cell with dynamic text
$pdf->SetXY($border_margin + $padding, $heading_y + 10); // Set position for the text (X, Y)
$pdf->Cell($content_width, 10, "Date: " . $medical_certificate['created_date_2']); // Add patient name

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 12); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 20); // Position for the next text
$pdf->MultiCell($content_width, 10, "This is to certify that " . $medical_certificate['name'] ." is suffering from a medical condition and is advised to refrain from Work from " . $medical_certificate['from_date'] ." to " . $medical_certificate['to_date'] ." inclusive.");

// Add padding for the bottom part
$bottom_padding = 30; // Padding at the bottom of the page
$vertical_space_between_lines = 8; // Space between lines of text in the footer
$signature_width = 20; // Width of the signature image
$signature_height = 10; // Height of the signature image

// Margin for text elements in the footer
$footer_margin_left = 10; // Left margin for text
$footer_margin_top = 10; // Top margin for text

// Position for the bottom content, including padding
$footer_y = $pdf->GetPageHeight() - $border_margin - $bottom_padding - 3 * $vertical_space_between_lines; 
$pdf->SetY($footer_y); // Position for the bottom content

$pdf->SetFont("Arial", "", 12); // Set font for the additional text

// Add "Yours Sincerely," with margin
$pdf->SetX($border_margin  + $footer_margin_left); // Set X position with left margin
$pdf->Cell($content_width - $footer_margin_left, 10, "Yours Sincerely,", 0, 1, 'L');

// Add doctor's username with margin
$pdf->SetX($border_margin  + $footer_margin_left); // Set X position with left margin
$pdf->Cell($content_width - $footer_margin_left, 10, htmlspecialchars($medical_certificate['doctor_name']), 0, 1, 'L');

// Add APHRA registration number with margin
$pdf->SetX($border_margin + $footer_margin_left); // Set X position with left margin
$pdf->Cell($content_width - $footer_margin_left, 10, "APHRA registration number: " . $medical_certificate['doctor_aphra'], 0, 1, 'L');

// Base path for the signature images
$base_path = 'doctor/assets/img/';

// Construct the full path to the signature image
$signature_image_path = $base_path . htmlspecialchars($medical_certificate['doctor_signature']);

// Add the signature image with padding and adjusted position
$pdf->Image($signature_image_path, $border_margin + $footer_margin_left, $pdf->GetY() + 5, $signature_width, $signature_height, "PNG"); // Adjust size and position as needed

// Output the PDF with a specific filename and force download
$pdf->Output('D', 'Medical Certificate.pdf');
// $pdf->Output('I', 'Medical Certificate.pdf');
?>
