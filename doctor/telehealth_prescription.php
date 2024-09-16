<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id']) && isset($_POST['prescription'])) {
    $appointment_id = $_POST['appointment_id'];
    $prescription = $_POST['prescription'];

    $query = "UPDATE appointments SET prescription = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $prescription, $appointment_id);
    $stmt->execute();

    header("Location: telehealth.php");
    exit();
}

$appointment_id = $_GET['id'] ?? null;

if (!$appointment_id) {
    echo "Invalid appointment ID.";
    exit();
}
?>


<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Telehealth</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div>
            <h2 class="page-header">Add Prescription</h2>
            <form action="telehealth_prescription.php" method="POST">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">
                <!-- <label for="prescription"><b>Prescription:</b></label> -->
                <textarea name="prescription" id="prescription" cols="50" rows="4" required></textarea> <br><br>
                <button type="submit" class="button-class">Submit Prescription</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
<script src="assets/script.js"></script>
</body>

</html>