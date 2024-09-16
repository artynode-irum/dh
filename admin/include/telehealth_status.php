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
    $appointment_id = $_GET['id'];
    $status = $_GET['status'];

    // Validate status value
    if ($status === 'approved' || $status === 'disapproved') {
        $query = "UPDATE appointments SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $appointment_id);
        $stmt->execute();
        $stmt->close();

        // Redirect back to the appointments page
        // header("Location: ../telehealth.php");
        // header("Location: ../telehealth.php");
        echo " <script type='text/javascript'>
            setTimeout(function() {
                alert('Redirecting to the appointments page...');
                window.location.href = '../telehealth.php';
            }, 100);
        </script>";
        // exit()
        exit();
    } else {
        // Invalid status value
        echo "Invalid status.";
        exit();
    }
} else {
    // Missing parameters
    echo "Missing appointment ID or status.";
    exit();
}

?>