<?php
include '../../include/config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update related appointments to set doctor_id to NULL
        $updateQuery = "UPDATE appointments SET doctor_id = NULL WHERE doctor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt === false) throw new Exception($conn->error);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        if ($updateStmt->errno) throw new Exception($updateStmt->error);
        $updateStmt->close();

        // Update related chat_messages to set doctor_id to NULL
        $updateQuery = "UPDATE chat_messages SET doctor_id = NULL WHERE doctor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt === false) throw new Exception($conn->error);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        if ($updateStmt->errno) throw new Exception($updateStmt->error);
        $updateStmt->close();

        // Update related medical_certificate to set doctor_id to NULL
        $updateQuery = "UPDATE medical_certificate SET doctor_id = NULL WHERE doctor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt === false) throw new Exception($conn->error);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        if ($updateStmt->errno) throw new Exception($updateStmt->error);
        $updateStmt->close();

        // Update related prescription to set doctor_id to NULL
        $updateQuery = "UPDATE prescription SET doctor_id = NULL WHERE doctor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt === false) throw new Exception($conn->error);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        if ($updateStmt->errno) throw new Exception($updateStmt->error);
        $updateStmt->close();

        // Update related referrals to set doctor_id to NULL
        $updateQuery = "UPDATE referrals SET doctor_id = NULL WHERE doctor_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        if ($updateStmt === false) throw new Exception($conn->error);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        if ($updateStmt->errno) throw new Exception($updateStmt->error);
        $updateStmt->close();

        // Delete the doctor
        $deleteQuery = "DELETE FROM doctor WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        if ($deleteStmt === false) throw new Exception($conn->error);
        $deleteStmt->bind_param('i', $id);
        $deleteStmt->execute();
        if ($deleteStmt->errno) throw new Exception($deleteStmt->error);
        $deleteStmt->close();

        // Commit the transaction
        $conn->commit();
        header("Location: ../total_doctor.php?message=Doctor deleted successfully");
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        header("Location: ../total_doctor.php?message=Error deleting doctor: " . urlencode($e->getMessage()));
    }

    $conn->close();
} else {
    header("Location: ../total_doctor.php");
}
?>
