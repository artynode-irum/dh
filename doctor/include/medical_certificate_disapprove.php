<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['id']) && isset($_POST['reason'])) {
    $medical_certificate_id = $_POST['id'];
    $disapproval_reason = $_POST['reason'];
    $patient_name = $_POST['patient_name'];
    $email = $_POST['email'];

    // Validate the disapproval reason
    $disapproval_reason = trim($disapproval_reason);

    $query = "UPDATE medical_certificate SET status = 'disapproved', disapproval_reason = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $disapproval_reason, $medical_certificate_id);
    $stmt->execute();
    $stmt->close();
    require '../../emails/approve_disapprove_mail.php';

    // Redirect back to the medical certificate page
    header("Location: ../medical_certificate.php");
    exit();
} else {
    echo "Invalid input.";
}
?>
