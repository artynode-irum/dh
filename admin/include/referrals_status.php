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
    $referrals_id = $_GET['id'];
    $status = $_GET['status'];

    // Validate status value
    if ($status === 'approved' || $status === 'disapproved') {
        $query = "UPDATE referrals SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $status, $referrals_id);
        $stmt->execute();

        // Redirect back to the referrals page
        // header("Location: ../referrals.php");
        echo " <script type='text/javascript'>
            setTimeout(function() {
                alert('Status Updated Successfully!');
                window.location.href = '../referrals.php';
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
    echo "Missing referrals ID or status.";
    exit();
}

?>