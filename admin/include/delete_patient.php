<?php
include '../../include/config.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Update related appointments to set patient_id to NULL
        $updateQuery = "UPDATE appointments SET patient_id = NULL WHERE patient_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Update related chat_messages to set patient_id to NULL
        $updateQuery = "UPDATE chat_messages SET patient_id = NULL WHERE patient_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Update related medical_certificate to set patient_id to NULL
        $updateQuery = "UPDATE medical_certificate SET patient_id = NULL WHERE patient_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Update related prescription to set patient_id to NULL
        $updateQuery = "UPDATE prescription SET patient_id = NULL WHERE patient_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Update related referrals to set patient_id to NULL
        $updateQuery = "UPDATE referrals SET patient_id = NULL WHERE patient_id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $id);
        $updateStmt->execute();
        $updateStmt->close();

        // Delete the patient
        $deleteQuery = "DELETE FROM patient WHERE id = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $id);
        $deleteStmt->execute();
        $deleteStmt->close();

        // Commit the transaction
        $conn->commit();
        header("Location: ../total_patient.php?message=Patient deleted successfully");
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();
        header("Location: ../total_patient.php?message=Error deleting patient: " . $e->getMessage());
    }

    $conn->close();
} else {
    header("Location: ../total_patient.php");
}
?>
