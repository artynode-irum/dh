<?php
require 'vendor/autoload.php';
include 'include/config.php'; // Ensure this file sets up the $conn variable

use Dompdf\Dompdf;
use Dompdf\Options;

session_start();

if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['admin', 'doctor', 'patient'])) {
    header("Location: index.php");
    exit();
}

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    // Prepare and execute the query
    $query = "SELECT patient.username AS patient_username, doctor.username AS doctor_username, doctor.doctor_name AS doctor_name, doctor.signature AS signature, doctor.aphra AS doctor_aphra, patient.email, appointments.description, appointments.video_link, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date 
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

// Initialize Dompdf
$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

// Generate HTML content for the PDF
$html = "
<!DOCTYPE html>
<html>
<head>
    <title>Telehealth Details</title>
</head>
<style>
.center-text {
  text-align: center;
}
</style>
<body>
<h4>doctorhelp.com.au</h4>
<h4>info@doctorhelp.com.au</h4>
<h4>805/220 Collins St,</h4>
<h4>Melbourne, VIC 3000</h4>


<br><br>
<h1 class='center-text'>Telehealth Details</h1> <br>
<p><strong>Patient Name:</strong> " . htmlspecialchars($appointment['title']) . " " . htmlspecialchars($appointment['name']) . "</p>
<p><strong>Doctor Name:</strong> " . htmlspecialchars($appointment['doctor_username']) . "</p>
<p><strong>Appointment Type:</strong> " . htmlspecialchars($appointment['appointment_type']) . "</p>
<p><strong>Appointment Date-Time:</strong> " . htmlspecialchars($appointment['appointment_day']) . " at " . htmlspecialchars($appointment['appointment_time']) . "</p>
<p><strong>Gender:</strong> " . htmlspecialchars($appointment['gender']) . "</p>
<p><strong>Email:</strong> " . htmlspecialchars($appointment['email']) . "</p>
<p><strong>Phone:</strong> " . htmlspecialchars($appointment['phone']) . "</p>
<p><strong>Date of Birth:</strong> " . htmlspecialchars($appointment['dob']) . "</p>
<p><strong>Country:</strong> " . htmlspecialchars($appointment['country']) . "</p>
<p><strong>Description:</strong> " . htmlspecialchars($appointment['description']) . "</p>
<p><strong>Date:</strong> " . htmlspecialchars($appointment['created_date']) . "</p>";

// if (!empty($appointment['prescription'])) {
//     $html .= "<hr><p><strong>" . htmlspecialchars($appointment['prescription']) . "</strong></p>";
// }

$html .= "
    <br><br><br>
    <p><strong> Service Acknowledgment and Guidelines</strong></p>
<p>At Doctor Help, we are committed to providing safe and responsible healthcare services through
telehealth. It is important to note that there are certain medications that we will never issue
a prescription for from an online request. This policy is in place to ensure the well-being and
safety of our patients. The following is a list of medications that fall under this category:
</p>
<br>
<ol>

<li>Anaesthetic agents (e.g., propofol, midazolam, ketamine).</li> <br>

<li>Prescription stimulants (such as amphetamine, dexamphetamine, etc).</li> <br>

<li>Barbiturates.</li> <br>

<li>Methadone (when prescribed for the treatment of drug dependency).</li> <br>

<li>Chemotherapy and most anti-cancer treatments.</li> <br>

<li>Prohibited substances.</li> <br>

<li>Medicines that a patient is not currently taking, without a consultation with a Doctor Help
    provider via telehealth.</li> <br>

<li>Opiate painkillers (including but not limited to: Codeine, Fentanyl, morphine, oxycodone,
    hydromorphone, tramadol, and tapentadol).</li> <br>

<li>Benzodiazepines (including but not limited to diazepam [Valium], lorazepam [Ativan]).</li>
<br>

<li>Drugs that are susceptible to abuse, such as pregabalin (Lyrica) (unless prescribed for
    proven epilepsy), dexamphetamine, or gabapentin (unless prescribed for proven epilepsy).
</li> <br>

<li>Any medication for which the prescriber is not satisfied that it will be used for the stated
    purpose or is not the most appropriate treatment for the described medical condition.</li>
<br>

</ol>
<p>We appreciate your understanding and cooperation in adhering to these guidelines as we prioritize
your health and safety in every consultation. If you have any questions or concerns, please do
not hesitate to reach out to our team.</p>
";


$html .= "<br><br><br><br><br><br><br><br><br><br>";

$html .= "<p><strong>Yours Sincerely,</strong></p>
          <p><strong>" . htmlspecialchars($appointment['doctor_name']) . "</strong></p>
          <p>APHRA registration number:<strong>" . htmlspecialchars($appointment['doctor_aphra']) . "</strong></p>";

if (!empty($appointment['signature'])) {
    $html .= "<p><strong>Signature:</strong></p>
              <img src='" . htmlspecialchars($appointment['signature']) . "' alt='Signature' style='max-width: 200px; max-height: 200px;'/>";
} else {
    $html .= "<p><strong>Signature:</strong> No signature available</p>";
}

$html .= "</body></html>";

$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("Telehealth.pdf", array("Attachment" => 0));
// $dompdf->stream("Telehealth.pdf", array("Attachment" => 1));

?>