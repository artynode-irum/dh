<?php
session_start();
include '../../include/config.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Check if 'id' is set in the query parameters
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepare and bind
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['alertMessage'] = "Message deleted successfully!";
    } else {
        $_SESSION['alertMessage'] = "Error deleting message: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['alertMessage'] = "Invalid request.";
}

// Redirect back to the contact messages page
header("Location: ../contact.php");
exit();
?>
