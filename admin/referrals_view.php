<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$referrals_id = $_GET['id'] ?? null;

if ($referrals_id) {
    $query = "SELECT patient.username AS patient_username, patient.email, doctor.username AS doctor_username, doctor.signature AS signature, referrals.description, referrals.prescription, referrals.created_date, referrals.request_date_time, referrals.payment, referrals.name, referrals.email, referrals.phone, referrals.phone, referrals.dob, referrals.card_number, referrals.security_code, referrals.country, referrals.address, referrals.gender, referrals.title, referrals.appointment_type, referrals.appointment_day, referrals.appointment_time, referrals.medicare_number, referrals.other_details, referrals.referral_provider, referrals.referral_type 
              FROM referrals 
              LEFT JOIN patient ON referrals.patient_id = patient.id 
              LEFT JOIN doctor ON referrals.doctor_id = doctor.id 
              WHERE referrals.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $referrals_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $referrals = $result->fetch_assoc();
}

if (!$referrals) {
    echo "<p>referrals not found.</p>";
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

        <h2 class="page-header">Referrals Details</h2>

        <div class="action-buttons">
            <div>
                <a href="referrals.php">
                    <i class="fa-solid fa-caret-left"></i> Back
                </a>
                <a href="../referrals_pdf.php?id=<?php echo htmlspecialchars($referrals_id); ?>">
                    Download PDF <i class="fa-solid fa-angles-down"></i>
                </a>
            </div>
        </div>

        <div class="details-section">
            <!-- Patient Details Section -->
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Patient Username:</strong> <?php echo htmlspecialchars($referrals['patient_username']); ?>
                </p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($referrals['title']); ?></p>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($referrals['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($referrals['email']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($referrals['dob']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($referrals['gender']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($referrals['address']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($referrals['country']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($referrals['phone']); ?></p>
                <p><strong>Medicare Number:</strong> <?php echo htmlspecialchars($referrals['medicare_number']); ?></p>
            </div>

            <!-- Doctor and Referral Details Section -->
            <div class="details-box">
                <h3>Referral Details</h3>
                <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($referrals['doctor_username']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($referrals['description']); ?></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($referrals['prescription']); ?></p>
                <p><strong>Referral Type:</strong> <?php echo htmlspecialchars($referrals['referral_type']); ?></p>
                <p><strong>Referral Provider Name/Number:</strong>
                    <?php echo htmlspecialchars($referrals['referral_provider']); ?></p>
                <p><strong>Other Details:</strong> <?php echo htmlspecialchars($referrals['other_details']); ?></p>
                <p class="signature-container">
                    <strong>Signature:</strong>
                    <span class="signature-btn" onclick="openModal('signature')">View Signature</span>
                </p>

                <div id="signature" class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick="closeModal('signature')">&times;</span>
                        <img src="../doctor/assets/img/<?php echo htmlspecialchars($referrals['signature']); ?>"
                            alt="Doctor's Signature" width="150">
                    </div>
                </div>
            </div>

            <!-- Appointment Details Section -->
            <div class="details-box">
                <h3>Appointment Details</h3>
                <p><strong>Appointment Type:</strong> <?php echo htmlspecialchars($referrals['appointment_type']); ?>
                </p>
                <p><strong>Appointment Date:</strong> <?php echo htmlspecialchars($referrals['appointment_day']); ?></p>
                <p><strong>Appointment Time:</strong> <?php echo htmlspecialchars($referrals['appointment_time']); ?>
                </p>
                <p><strong>Request Date Time:</strong> <?php echo htmlspecialchars($referrals['request_date_time']); ?>
                </p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($referrals['created_date']); ?></p>
            </div>

        </div>
    </section>

    <?php include("include/footer.php"); ?>
</div>


<script src="assets/script.js"></script>
</body>

</html>