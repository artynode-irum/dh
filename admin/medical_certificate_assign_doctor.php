<?php
session_start();
include '../include/config.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ./index.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['medical_certificate_id']) && isset($_POST['doctor_id'])) {
    $medical_certificate_id = $_POST['medical_certificate_id'];
    $doctor_id = $_POST['doctor_id'];
    $signature = $_POST['signature'];

    // Validate inputs
    if (!is_numeric($medical_certificate_id) || !is_numeric($doctor_id)) {
        die("Invalid input.");
    }

    // Prepare SQL statement to update the medical_certificate
    $query = "UPDATE medical_certificate SET doctor_id = ?, status = 'assigned', signature = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("isi", $doctor_id, $signature, $medical_certificate_id);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Error updating record: " . $stmt->error);
    }

    // Redirect to medical_certificate.php
    header("Location: medical_certificate.php");
    exit();
}
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Assign Doctor</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div class="assign-doctor">
            <h2 class="page-header">Assign Doctor to Medical Certificate</h2>
            <form action="medical_certificate_assign_doctor.php" method="POST">
                <input type="hidden" name="medical_certificate_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
                <label for="doctor_id">Select Doctor:</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="">Select Doctor</option>
                    <?php
                    // Fetch all doctors
                    $query = "SELECT id, username FROM doctor";
                    $result = $conn->query($query);

                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['username']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No doctors available</option>";
                    }
                    ?>
                </select>
                <!-- <label for="signature">Signature:</label>
                <input type="text" id="signature" name="signature" > -->
                <button type="submit" class="button-class">Assign Doctor</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>