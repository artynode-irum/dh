<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['referrals_id']) && isset($_POST['prescription'])) {
    $referrals_id = $_POST['referrals_id'];
    $prescription = $_POST['prescription'];

    $query = "UPDATE referrals SET prescription = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $prescription, $referrals_id);
    $stmt->execute();

    header("Location: referrals.php");
    exit();
}

$referrals_id = $_GET['id'] ?? null;

if (!$referrals_id) {
    echo "Invalid referrals ID.";
    exit();
}
?>


<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Referrals</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div>
            <h2 class="page-header">Add Referral Comment</h2>
            <form action="referrals_prescription.php" method="POST">
                <input type="hidden" name="referrals_id" value="<?php echo htmlspecialchars($referrals_id); ?>">
                <!-- <label for="prescription"><b>Prescription:</b></label><br> -->
                <textarea cols="50" rows="4" name="prescription" id="prescription" required></textarea><br><br>
                <button type="submit" class="button-class">Submit Prescription</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>