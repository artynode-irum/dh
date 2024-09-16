<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['id']) && isset($_POST['reason'])) {
    $medical_certificate_id = $_POST['id'];
    $disapproval_reason = $_POST['reason'];

    // Validate the disapproval reason
    $disapproval_reason = trim($disapproval_reason);

    $query = "UPDATE medical_certificate SET status = 'disapproved', disapproval_reason = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $disapproval_reason, $medical_certificate_id);
    $stmt->execute();

    // Redirect back to the medical certificate page
    header("Location: ../medical_certificate.php");
    exit();
} else {
    echo "Invalid input.";
}
?>
