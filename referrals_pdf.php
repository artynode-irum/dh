<?php

include("fpdf/fpdf.php");
include 'include/config.php'; // Ensure this file sets up the $conn variable

session_start();

// Check user authentication and authorization
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'doctor', 'patient'])) {
    header("Location: index.php");
    exit();
}

$referral_id = $_GET['id'] ?? null;

if ($referral_id) {
    // Prepare and execute the query
    $query = "SELECT patient.username AS patient_username, patient.email, doctor.doctor_name AS doctor_name, doctor.aphra AS doctor_aphra, doctor.username AS doctor_username, doctor.signature AS doctor_signature, 
    referrals.description, referrals.prescription, referrals.created_date, referrals.payment, 
    referrals.title, referrals.name, referrals.gender, referrals.email, referrals.phone, referrals.dob, 
    referrals.card_number, referrals.security_code, referrals.medicare_expiration_date, referrals.country, referrals.appointment_type, 
    referrals.appointment_day, referrals.appointment_time, referrals.address, referrals.referral_type, referrals.referral_provider
              FROM referrals 
              LEFT JOIN patient ON referrals.patient_id = patient.id 
              LEFT JOIN doctor ON referrals.doctor_id = doctor.id    
              WHERE referrals.id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $referral_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $referral = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "<p>Database query error.</p>";
        exit();
    }
} else {
    echo "<p>No referral ID provided.</p>";
    exit();
}

if (!$referral) {
    echo "<p>Referral not found.</p>";
    exit();
}

// Define a custom class that extends FPDF
class PDF extends FPDF
{
    // Header method to add header content
    function Header()
    {
        // Draw page border
        $border_margin = 10; // Margin from the edges of the page
        $this->Rect($border_margin, $border_margin, $this->GetPageWidth() - 2 * $border_margin, $this->GetPageHeight() - 2 * $border_margin);
        
        // Add header content
        // $this->SetFont('Arial', 'B', 8);
        // $this->Cell(0, 10, 'Doctor Help', 0, 1, 'C');
        // $this->Ln(10); // Add a line break
    }

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
$left_margin = 20; // Margin on the left side of the content
$right_margin = 20; // Margin on the right side of the content

// Calculate the X position to center the image within the content area
$image_width = 50; // Width of the image
$image_height = 30; // Height of the image
$x_position = ($pdf->GetPageWidth() - $image_width) / 2; // Centering with border margins and padding

// Add PNG image
$pdf->Image("pdf-logo.png", $x_position, $border_margin + $padding, $image_width, $image_height, "png", "https://multiplepromosolutions.com/dh"); // Adjust size as needed

// Set font for heading
$pdf->SetFont("Arial", "B", 18); // Set font family, style (B for bold), and size

// Add the heading below the image
$heading_y = $border_margin + $padding + $image_height + 10; // Position below the image with some spacing
$pdf->SetXY($border_margin + $padding, $heading_y); // Set position for the heading (X, Y)

// Calculate the X position to center the heading
$heading_width = $pdf->GetStringWidth("Referral"); // Calculate width of heading text
$x_center = ($pdf->GetPageWidth() - $heading_width) / 2; // Centering with border margins and padding
$pdf->SetXY($x_center, $heading_y); // Set position for the heading (X, Y)
$pdf->Cell($heading_width, 10, "Referral", 0, 1, 'C'); // Add heading centered

// Set font for data
$pdf->SetFont("Arial", "", 12);

// Define data
$data = array(
    'WHAT TYPE OF REFERRAL ARE YOU AFTER?' => $referral['referral_type'],
    'REFERRAL PROVIDER NAME OR NUMBER' => $referral['referral_provider'],
    'NAME' => $referral['name'],
    'DATE OF BIRTH' => $referral['dob'],
    // 'AGE' => $referral['age'],
    'GENDER' => $referral['gender'],
    'ADDRESS' => $referral['address'],
    'COUNTRY' => $referral['country'],
    'SERVICE NAME' => $referral['appointment_type'],
    'APPOINTMENT DAY' => $referral['appointment_day'],
    'APPOINTMENT TIME' => $referral['appointment_time'],
    'ASSIGN A DOCTOR:' => $referral['doctor_name'],
    'APHRA REGISTRATION NUMBER:' => $referral['doctor_aphra'],
    // 'PAYMENT' => '$' . number_format($referral['payment'], 2) // Add $ and format amount
);

// Add the data to the PDF
$x = $border_margin + $padding;
$y = $heading_y + 20; // Starting Y position after heading

foreach ($data as $label => $value) {
    $pdf->SetXY($x, $y);
    $pdf->SetFont("Arial", "B", 12); // Set font for labels
    $pdf->MultiCell(0, 10, $label, 0, 'L'); // Label with wrapping
    $y = $pdf->GetY(); // Update Y position to the end of MultiCell for label
    $pdf->SetXY($x, $y);
    $pdf->SetFont("Arial", "", 12); // Set font for values
    $pdf->MultiCell(0, 10, $value, 0, 'L'); // Value with wrapping
    $y = $pdf->GetY(); // Update Y position to the end of MultiCell for value
    $y += 5; // Add a bit of space between entries
}

// Optionally add signature if needed
// $signature_image_path = htmlspecialchars($referral['doctor_signature']);
// $pdf->Image($signature_image_path, $border_margin + $footer_margin_left, $pdf->GetY() + 5, $signature_width, $signature_height, "PNG"); // Adjust size and position as needed

// Output the PDF with a specific filename and force download
$pdf->Output('D', 'Referral.pdf');
// $pdf->Output('I', 'Referral.pdf');
?>
