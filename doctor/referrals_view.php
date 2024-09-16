<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$referrals_id = $_GET['id'] ?? null;

if ($referrals_id) {
    $query = "SELECT patient.username, patient.email, referrals.description, referrals.prescription, referrals.created_date, referrals.request_date_time, doctor.signature AS signature
              FROM referrals 
              JOIN patient ON referrals.patient_id = patient.id
              JOIN doctor ON referrals.doctor_id = doctor.id
              WHERE referrals.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $referrals_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
}

if (!$patient) {
    echo "<p>Patient not found.</p>";
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

        <!-- <div class="content"> -->
            <div class="detail-page mt-5">
                <span>
                    <h2>Patient Details</h2>
                    <span>
                        <a href="referrals.php" class="button-class" style="color: white; text-decoration: none;"><i
                                class="fa-solid fa-caret-left"></i> Back</a>
                        <a href="../referrals_pdf.php?id=<?php echo htmlspecialchars($referrals_id); ?>"
                            class="button-class" style="color: white; text-decoration: none;">Download PDF <i
                                class="fa-solid fa-angles-down"></i></a>
                    </span>
                </span>
                <p><strong>Patient Name:</strong> <span><?php echo htmlspecialchars($patient['username']); ?></span></p>
                <p><strong>Email:</strong> <span><?php echo htmlspecialchars($patient['email']); ?></span></p>
                <p><strong>Description:</strong> <span><?php echo htmlspecialchars($patient['description']); ?></span>
                </p>
                <p><strong>Prescription:</strong> <span><?php echo htmlspecialchars($patient['prescription']); ?></span>
                </p>
                <p><strong>Request Date Time:</strong>
                    <span><?php echo htmlspecialchars($patient['request_date_time']); ?></span>
                </p>
                <p><strong>Created At:</strong> <span><?php echo htmlspecialchars($patient['created_date']); ?></span>
                </p>

                <?php if (!empty($patient['signature'])): ?>
                    <p><strong>Doctor's Signature:</strong></p>
                    <img src="../assets/img/<?php echo htmlspecialchars($patient['signature']); ?>" alt="Doctor's Signature"
                        width="150">
                <?php endif; ?>
            </div>
        <!-- </div> -->
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>