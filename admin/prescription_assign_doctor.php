<?php
session_start();
include '../include/config.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ./index.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prescription_id']) && isset($_POST['doctor_id'])) {
    $prescription_id = $_POST['prescription_id'];
    $doctor_id = $_POST['doctor_id'];
    $signature = $_POST['signature'];

    // Validate inputs
    if (!is_numeric($prescription_id) || !is_numeric($doctor_id)) {
        die("Invalid input.");
    }

    // Prepare SQL statement to update the prescription
    $query = "UPDATE prescription SET doctor_id = ?, status = 'assigned', signature = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("isi", $doctor_id, $signature, $prescription_id);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Error updating record: " . $stmt->error);
    }

    header("Location: prescription.php");
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
            <h2 class="page-header">Assign Doctor to Prescription</h2>
            <form action="prescription_assign_doctor.php" method="POST">
                <input type="hidden" name="prescription_id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
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
                <button type="submit" class="button-class">Assign Doctor</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>