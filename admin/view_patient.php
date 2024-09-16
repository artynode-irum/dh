<?php
session_start();
include '../include/config.php';

// Check if user is logged in and has admin role
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch patient details
if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Prepare and execute query to get patient details
    $stmt = $conn->prepare("SELECT * FROM patient WHERE id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "No patient found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $email_verify = $_POST['email_verify'];

    // Prepare and execute update query
    $update_stmt = $conn->prepare("UPDATE patient SET email_verify = ? WHERE id = ?");
    $update_stmt->bind_param("si", $email_verify, $patient_id); // Bind as string for email_verify

    if ($update_stmt->execute()) {
        // Redirect to the same page with updated information
        header("Location: view_patient.php?id=" . urlencode($patient_id));
        exit();
    } else {
        echo "Failed to update email verification status.";
    }

    $update_stmt->close();
}
?>

<?php include("include/sidebar.php"); ?>
<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Patient Details</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div>
            <h2 class="page-header">Patient Details</h2>

            <div class="patient-details">
                <span>
                    <label for="profile_picture">Profile Picture</label>
                    <!-- <input type="file" id="profile_picture" name="profile_picture"> -->
                    <img src="../patient/assets/img/<?php echo htmlspecialchars($patient['profile_picture']); ?>"
                        alt="Profile Picture" width="100">
                </span>

                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                    value="<?php echo htmlspecialchars($patient['username']); ?>" readonly>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>"
                    readonly>

                <form action="" method="POST">
                    <label for="email_verify">Verification Status</label> <br> <br>
                    <?php
                    // Check if email_verify is not empty or NULL and set the value accordingly
                    $email_verify_value = !empty($patient['email_verify']) ? htmlspecialchars($patient['email_verify']) : '';
                    ?>
                    <input type="text" id="email_verify" name="email_verify" value="<?php echo $email_verify_value; ?>"
                        pattern="[01]" title="Please enter 0 or 1 only" required> <br><br>
                    <input type="submit" class="button-class" name="update" value="Save">
                </form>

            </div>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
<script src="assets/script.js"></script>
</body>

</html>