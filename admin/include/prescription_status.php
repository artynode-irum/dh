<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}
?>


<?php
if (isset($_GET['id']) && isset($_GET['status'])) {
    $prescription_id = $_GET['id'];
    $status = $_GET['status'];

    // Validate status value
    if ($status === 'approved' || $status === 'disapproved') {
        $query = "UPDATE prescription SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $prescription_id);
        $stmt->execute();

        // Redirect back to the prescription page
        // header("Location: ../prescription.php");
        echo " <script type='text/javascript'>
            setTimeout(function() {
                alert('Status Updated Successfully!');
                window.location.href = '../prescription.php';
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
    echo "Missing prescription ID or status.";
    exit();
}

?>