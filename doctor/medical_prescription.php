<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['medical_certificate_id']) && isset($_POST['prescription'])) {
    $medical_certificate_id = $_POST['medical_certificate_id'];
    $prescription = $_POST['prescription'];

    $query = "UPDATE medical_certificate SET prescription = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $prescription, $medical_certificate_id);
    $stmt->execute();

    header("Location: medical_certificate.php");
    exit();
}

$medical_certificate_id = $_GET['id'] ?? null;

if (!$medical_certificate_id) {
    echo "Invalid medical certificate ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Prescription</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <?php include("include/sidebar.php"); ?>

    <div class="second-section">
        <section>
            <div class="navbar">
                <div class="page-title">
                    <span>Medical Certificate</span>
                </div>
                <?php include("include/navbar.php"); ?>
            </div>

            <div>
                <h2 class="page-header">Add Certificate</h2>
                <form action="medical_prescription.php" method="POST">
                    <input type="hidden" name="medical_certificate_id"
                        value="<?php echo htmlspecialchars($medical_certificate_id); ?>">
                    <label for="prescription"><b>Prescription:</b></label>
                    <textarea name="prescription" id="prescription" required></textarea>
                    <button type="submit">Submit Prescription</button>
                </form>
            </div>
        </section>
        <?php include("include/footer.php"); ?>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>