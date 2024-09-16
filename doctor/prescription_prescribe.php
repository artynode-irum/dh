<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['prescription_id']) && isset($_POST['prescription'])) {
    $prescription_id = $_POST['prescription_id'];
    $prescription = $_POST['prescription'];

    $query = "UPDATE prescription SET prescribe = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $prescription, $prescription_id);
    $stmt->execute();

    header("Location: prescription.php");
    exit();
}

$prescription_id = $_GET['id'] ?? null;

if (!$prescription_id) {
    echo "Invalid prescription ID.";
    exit();
}
?>


<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Prescription</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div>
            <h2 class="page-header">Add Prescription</h2>
            <form action="prescription_prescribe.php" method="POST">
                <input type="hidden" name="prescription_id" value="<?php echo htmlspecialchars($prescription_id); ?>">
                <!-- <label for="prescription"><b>Prescription:</b></label> -->
                <textarea name="prescription" id="prescription" rows="4" cols="50" required></textarea> <br><br>
                <button type="submit" class="button-class">Submit Prescription</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>