<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}
?>


<?php
if (isset($_GET['id']) && isset($_GET['status'])) {
    $medical_certificate_id = $_GET['id'];
    $status = $_GET['status'];
    $email = $_GET['email'];
    $patient_name = $_GET['patient_name'];

    // Validate status value
    if ($status === 'approved' || $status === 'disapproved') {
        $query = "UPDATE medical_certificate SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $medical_certificate_id);
        $stmt->execute();
        $stmt->close();
        require '../../emails/approve_disapprove_mail.php';

        // Redirect back to the medical certificate page
        // header("Location: ../medical_certificate.php");
        echo " <script type='text/javascript'>
            setTimeout(function() {
                alert('Status Updated Successfully!');
                window.location.href = '../medical_certificate.php';
            }, 100);
        </script>";
        exit();
    } else {
        // Invalid status value
        echo "Invalid status.";
        exit();
    }
} else {
    // Missing parameters
    echo "Missing edical certificate ID or status.";
    exit();
}

?>