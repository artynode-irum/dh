<?php

include("fpdf/fpdf.php");
include 'include/config.php'; // Ensure this file sets up the $conn variable

session_start();

// Check user authentication and authorization
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'doctor', 'patient'])) {
    header("Location: index.php");
    exit();
}

$prescription_id = $_GET['id'] ?? null;

if ($prescription_id) {
    // Prepare and execute the query
    $query = "SELECT patient.username AS patient_username, patient.email, doctor.username AS doctor_username, doctor.doctor_name AS doctor_name, doctor.aphra AS doctor_aphra, doctor.signature AS doctor_signature, 
    prescription.description, prescription.prescribe, prescription.created_date, prescription.request_date_time, prescription.payment, 
    prescription.title, prescription.name, prescription.gender, prescription.email, prescription.phone, prescription.dob, 
    prescription.card_number, prescription.security_code, prescription.expiration_date, prescription.country, prescription.treatment, 
    prescription.prescriptionafter, prescription.dosage, prescription.previously_taken_medi, prescription.currentlyppb, 
    prescription.health_condition, prescription.known_allergies, prescription.reason_known_allergies_yes,
    prescription.over_the_counter_drugs, prescription.healthcare_provider_person_recently, prescription.specific_medication_seeking, 
    prescription.known_nill_allergies, prescription.medication_used_previously, prescription.plan_schedule, prescription.appointment_type, 
    prescription.appointment_day, prescription.appointment_time, prescription.created_date_2, prescription.adverse_reactions, prescription.address, prescription.document_path 
              FROM prescription 
              LEFT JOIN patient ON prescription.patient_id = patient.id 
              LEFT JOIN doctor ON prescription.doctor_id = doctor.id    
              WHERE prescription.id = ?";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $prescription_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $prescription = $result->fetch_assoc();
        $stmt->close();
    } else {
        echo "<p>Database query error.</p>";
        exit();
    }
} else {
    echo "<p>No prescription ID provided.</p>";
    exit();
}

if (!$prescription) {
    echo "<p>Prescription not found.</p>";
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
$heading_width = $pdf->GetStringWidth("Prescription"); // Calculate width of heading text
$x_center = ($pdf->GetPageWidth() - $heading_width) / 2; // Centering with border margins and padding
$pdf->SetXY($x_center, $heading_y); // Set position for the heading (X, Y)
$pdf->Cell($heading_width, 10, "Prescription", 0, 1, 'C'); // Add heading centered

// Set font for data
$pdf->SetFont("Arial", "", 12);

// Define data
$data = array(
    'ASSIGN A DOCTOR:' => $prescription['doctor_name'],
    'DOCTOR APHRA NUMBER:' => $prescription['doctor_aphra'],
    'CHOOSE THE TREATMENT:' => $prescription['treatment'],
    'HAVE YOU PREVIOUSLY TAKEN THIS MEDICATION?' => $prescription['previously_taken_medi'],
    'DOSAGE OF THE MEDICINE?' => $prescription['dosage'],
    'FOR WHAT HEALTH CONDITION(S) IS THIS MEDICATION BEING PRESCRIBED?' => $prescription['health_condition'],
    'DO YOU HAVE ANY KNOWN ALLERGIES TO MEDICATIONS OR ANY OTHER RELEVANT ALLERGIES?' => $prescription['known_allergies'],
    'HAVE YOU EXPERIENCED ANY SIDE EFFECTS OR ADVERSE REACTIONS WHILE TAKING THIS MEDICATION?' => $prescription['adverse_reactions'],
    'Please provide more details for above.' => $prescription['adverse_reactions'],
    'PRESCRIPTION YOU ARE AFTER:' => $prescription['prescriptionafter'],
    'ARE YOU CURRENTLY PREGNANT, PLANNING TO BECOME PREGNANT, OR BREASTFEEDING?' => $prescription['currentlyppb'],
    'ARE YOU CURRENTLY TAKING ANY OTHER MEDICATIONS, INCLUDING OVER-THE-COUNTER DRUGS OR SUPPLEMENTS? (REPLY `NIL` IF NOT TAKING ANY)' => $prescription['over_the_counter_drugs'],
    'HAVE YOU SEEN A HEALTHCARE PROVIDER IN PERSON RECENTLY REGARDING THIS MEDICAL CONDITION, OR PLANNING TO FOLLOW UP' => $prescription['healthcare_provider_person_recently'],
    'Are you aware of the specific medication you are seeking?' => $prescription['specific_medication_seeking'],
    'Do you have any known allergies to medications or any other relevant allergies?(Reply `Nil` if none)' => $prescription['known_nill_allergies'],
    'Have you ever used this medication previously?' => $prescription['medication_used_previously'],
    'Do you plan to schedule a follow-up appointment with your GP in the near future?' => $prescription['plan_schedule'],
    'Attached Document:' => $prescription['document_path'],
    'NAME' => $prescription['name'],
    'DATE OF BIRTH' => $prescription['dob'],
    // 'AGE' => $prescription['age'],
    'GENDER' => $prescription['gender'],
    'ADDRESS' => $prescription['address'],
    'COUNTRY' => $prescription['country'],
    'SERVICE NAME' => $prescription['appointment_type'],
    'APPOINTMENT DAY' => $prescription['appointment_day'],
    'APPOINTMENT TIME' => $prescription['appointment_time'],
    // 'PAYMENT' => '$' . number_format($prescription['payment'], 2) // Add $ and format amount
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
// $signature_image_path = htmlspecialchars($prescription['doctor_signature']);
// $pdf->Image($signature_image_path, $border_margin + $footer_margin_left, $pdf->GetY() + 5, $signature_width, $signature_height, "PNG"); // Adjust size and position as needed

// Output the PDF with a specific filename and force download
$pdf->Output('D', 'Prescription.pdf');
?>
