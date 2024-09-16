<?php
session_start();
include '../include/config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT * FROM patient WHERE token = ? AND email_verify = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Token is valid, verify the user
        $stmt = $conn->prepare("UPDATE patient SET email_verify = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        echo 'Email verified successfully. You can now log in.';
    } else {
        echo 'Invalid or expired token.';
    }

    // Close the statement
    $stmt->close();
}

?>