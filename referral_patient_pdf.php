<?php
include("fpdf/fpdf.php");
include 'include/config.php';

// Start the session and check user authentication
// session_start();
// if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'doctor', 'patient'])) {
//     header("Location: index.php");
//     exit();
// }

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    // Prepare and execute the query
    $query = "SELECT patient.username AS patient_username, patient.patient_name AS patient_name, doctor.username AS doctor_username, doctor.signature AS doctor_signature, doctor.doctor_name AS doctor_name, patient.email, appointments.description, appointments.video_link, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date, appointments.addressee_fname, appointments.addressee_address, appointments.addressee_phone, appointments.addressee_phone, appointments.address, appointments.provider_number, appointments.tests_required, appointments.clinic_notes
              FROM appointments 
              LEFT JOIN patient ON appointments.patient_id = patient.id 
              LEFT JOIN doctor ON appointments.doctor_id = doctor.id    
              WHERE appointments.id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $appointment_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointment = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "<p>Database query error.</p>";
        exit();
    }
}

if (!$appointment) {
    echo "<p>Appointment not found.</p>";
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

// Set font for contact details
$pdf->SetFont("Arial", "", 12); // Set font for contact details

// Add the website and clickable email link using Write
$pdf->SetXY($border_margin + $padding, $border_margin + $padding); // Position for the contact details (X, Y)

// Add website
$pdf->MultiCell($content_width, 0, "doctorhelp.com.au");

// Add clickable email link
$pdf->SetXY($border_margin + $padding, $pdf->GetY() + 3); // Adjust Y position
$pdf->Write(8, "info@doctorhelp.com.au", "mailto:info@doctorhelp.com.au");

// Add remaining address
$pdf->SetXY($border_margin + $padding, $pdf->GetY() + 8); // Adjust Y position
$pdf->MultiCell($content_width, 8, "805/220 Collins St,\nMelbourne, VIC 3000");

// Calculate the X position to center the image within the content area
$image_width = 35; // Width of the image
$image_height = 20; // Height of the image
$x_offset = 70; // Adjust this value to move the image to the right
$x_position = ($content_width - $image_width) / 2 + $border_margin + $padding + $x_offset; // Centering with border margins and padding

// Add PNG image
$pdf->Image("pdf-logo.png", $x_position, $border_margin + $padding + 0, $image_width, $image_height, "png", "https://multiplepromosolutions.com/dh"); // Adjust size as needed

// Set font for heading
$pdf->SetFont("Arial", "B", 18); // Set font family, style (B for bold), and size

// Add the heading below the image
$heading_y = $border_margin + $padding + 0 + $image_height; // Position below the image with some spacing

// // Optionally, you can use MultiCell for more advanced text formatting
// $pdf->SetFont("Arial", "B", 14); // Change font style and size if needed
// $pdf->SetXY($border_margin + $padding, $heading_y + 5); // Position for the next text
// $pdf->MultiCell($content_width, 10, "Patient Referral Form ");


// Set font for heading
$pdf->SetFont("Arial", "B", 14); // Set font family, style (B for bold), and size

// Calculate the width of the text
$text = "Patient Referral Form";
$text_width = $pdf->GetStringWidth($text);

// Calculate the X position to center the text
$x_position =  $border_margin + $padding;

// Add the heading below the image
$heading_y = $border_margin + $padding + $image_height; // Position below the image with some spacing

// Set position for the heading
$pdf->SetXY($x_position, $heading_y + 5); // Position for the next text

// Add the centered heading
$pdf->MultiCell($content_width, 10, $text, 0, 'C'); // 'C' for center alignment



// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 9); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 10); // Position for the next text
$pdf->MultiCell($content_width, 10, "Date: " . date("d-m-Y"));

// Set normal font for "Regarding Patient"
$pdf->SetXY($border_margin + $padding, $heading_y + 20); // Position for the contact details (X, Y)
$pdf->SetFont("Arial", "", 12); // Normal
$pdf->MultiCell($content_width, 8, "Regarding Patient:");

// Set bold font for patient name
$pdf->SetXY($border_margin + $padding, $heading_y + 30); // Position for the contact details (X, Y)
$pdf->SetFont("Arial", "B", 10); // Bold
$pdf->MultiCell($content_width, 8, $appointment['name']);

// Set normal font for DOB text and bold for the DOB value
$pdf->SetXY($border_margin + $padding, $heading_y + 35); // Position for the contact details (X, Y)
$pdf->SetFont("Arial", "B", 10); // Normal
$pdf->MultiCell($content_width, 8, "DOB: " . $appointment['dob'] . "", 0, 'L', false);


// Set normal font for patient address
$pdf->SetXY($border_margin + $padding, $heading_y + 40); // Position for the contact details (X, Y)
$pdf->SetFont("Arial", "B", 10); // Normal
$pdf->MultiCell($content_width, 8, $appointment['address']);

// // Optionally, you can use MultiCell for more advanced text formatting
// $pdf->SetFont("Arial", "", 10); // Change font style and size if needed
// $pdf->SetXY($border_margin + $padding, $heading_y + 70); // Position for the next text
// $pdf->MultiCell($content_width, 10, "Dear " . $appointment['name'] . ",");

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 50); // Position for the next text
$pdf->MultiCell($content_width, 10, "Tests Required: " . $appointment['tests_required']);

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 60); // Position for the next text
$pdf->MultiCell($content_width, 10, "Clinic Notes: " . $appointment['clinic_notes']);

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "B", 14); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 70); // Position for the next text
$pdf->MultiCell($content_width, 10, "Laboratory Use Only: " );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 80); // Position for the next text
$pdf->MultiCell($content_width, 10, "Time: " );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 85); // Position for the next text
$pdf->MultiCell($content_width, 10, "Date: DD/MM/YYYY" );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "B", 12); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 95); // Position for the next text
$pdf->MultiCell($content_width, 10, "Specimen Collected: " );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 105); // Position for the next text
$pdf->MultiCell($content_width, 10, "Collected By: " );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "B", 12); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 115); // Position for the next text
$pdf->MultiCell($content_width, 10, "Specimen Received: " );

// Optionally, you can use MultiCell for more advanced text formatting
$pdf->SetFont("Arial", "", 10); // Change font style and size if needed
$pdf->SetXY($border_margin + $padding, $heading_y + 125); // Position for the next text
$pdf->MultiCell($content_width, 10, "Received By: " );



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

// $pdf->SetFont("Arial", "", 12); // Set font for the additional text

// // Add "Yours Sincerely," with margin
// $pdf->SetX($border_margin + $footer_margin_left); // Set X position with left margin
// $pdf->Cell($content_width - $footer_margin_left, 10, "Yours faithfully,", 0, 1, 'L');

// Add doctor's username with margin
$pdf->SetX($border_margin + $footer_margin_left); // Set X position with left margin
$pdf->Cell($content_width - $footer_margin_left, 10, htmlspecialchars($appointment['doctor_name']), 0, 1, 'L');

// Add APHRA registration number with margin
$pdf->SetX($border_margin + $footer_margin_left); // Set X position with left margin
$pdf->Cell($content_width - $footer_margin_left, 10, htmlspecialchars($appointment['provider_number']), 0, 1, 'L');

// Base path for the signature images
$base_path = 'doctor/assets/img/';

// Construct the full path to the signature image
$signature_image_path = $base_path . htmlspecialchars($appointment['doctor_signature']);

// Add the signature image with padding and adjusted position
$pdf->Image($signature_image_path, $border_margin + $footer_margin_left, $pdf->GetY() + 5, $signature_width, $signature_height, "PNG"); // Adjust size and position as needed

// Output the PDF with a specific filename and force download
// $pdf->Output('D', 'Medical Certificate.pdf');
$pdf->Output('I', 'Referral Form.pdf');
?>