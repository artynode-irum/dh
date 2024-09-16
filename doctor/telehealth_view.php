<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    $query = "SELECT patient.username AS patient_username, patient.id AS patient_id, appointments.doctor_id AS doctor_id , doctor.username AS doctor_username, doctor.signature AS signature, patient.email, appointments.description, appointments.video_link, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date 
              FROM appointments 
              LEFT JOIN patient ON appointments.patient_id = patient.id 
              LEFT JOIN doctor ON appointments.doctor_id = doctor.id 
              WHERE appointments.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
}

if (!$appointment) {
    echo "<p>Appointment not found.</p>";
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

        <h2 class="page-header">Appointment Details</h2>

        <div class="action-buttons">
            <div>
                <a href="telehealth.php">
                    <i class="fa-solid fa-caret-left"></i> Back
                </a>
                <a href="../telehealth_pdf.php?id=<?php echo htmlspecialchars($appointment_id); ?>">
                    Download PDF <i class="fa-solid fa-angles-down"></i>
                </a>
            </div>
            <div>
                <a href="<?php echo htmlspecialchars($appointment['video_link']); ?>" target='_blank'>Join Session <i
                        class="fa-solid fa-video"></i></a>
            </div>
        </div>

        <div class="details-section">
            <!-- Patient Details Section -->
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($appointment['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($appointment['phone']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($appointment['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($appointment['dob']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($appointment['country']); ?></p>
                <p><strong>Medicare Number:</strong> <?php echo htmlspecialchars($appointment['medicare_number']); ?>
                </p>
                <p><strong>Medicare Expiry:</strong>
                    <?php echo htmlspecialchars($appointment['medicare_expiration_date']); ?></p>
            </div>

            <!-- Doctor and Appointment Details Section -->
            <div class="details-box">
                <h3>Appointment Details</h3>
                <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($appointment['doctor_username']); ?></p>
                <p><strong>Appointment Type:</strong> <?php echo htmlspecialchars($appointment['appointment_type']); ?>
                </p>
                <p><strong>Appointment Date-Time:</strong>
                    <?php echo htmlspecialchars($appointment['appointment_day']); ?>
                    at <?php echo htmlspecialchars($appointment['appointment_time']); ?></p>
                <p><strong>Request Date-Time:</strong>
                    <?php echo htmlspecialchars($appointment['request_date_time']); ?>
                </p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($appointment['created_date']); ?></p>
                <p class="signature-container">
                    <strong>Signature:</strong>
                    <span class="signature-btn" onclick="openModal('signature')">View Signature</span>
                </p>
                <div id="signature" class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick="closeModal('signature')">&times;</span>
                        <img src="../assets/img/<?php echo htmlspecialchars($appointment['signature']); ?>"
                            alt="Doctor's Signature" width="150">
                    </div>
                </div>
            </div>

            <!-- Appointment Timing Section -->
            <div class="details-box">
                <h3>Details</h3>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($appointment['description']); ?></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($appointment['prescription']); ?></p>
                <div class="action-buttons">
                    <a href="../frontend/medical_certificate_request.php?id=<?php echo htmlspecialchars($appointment_id); ?>&doctor_id=<?php echo htmlspecialchars($appointment['doctor_id']) ?>&patient_id=<?php echo htmlspecialchars($appointment['patient_id']) ?>"
                        target='_blank'>Issue Medical certificate
                        <i class="fa-solid fa-video"></i></a>
                </div>
                <div class="action-buttons">
                    <a href="../frontend/prescription_request.php?id=<?php echo htmlspecialchars($appointment_id); ?>&doctor_id=<?php echo htmlspecialchars($appointment['doctor_id']) ?>&patient_id=<?php echo htmlspecialchars($appointment['patient_id']) ?>"
                        target='_blank'>Issue prescription
                        <i class="fa-solid fa-video"></i></a>
                </div>
                <div class="action-buttons">
                    <a href="../frontend/referral_request.php?id=<?php echo htmlspecialchars($appointment_id); ?>&doctor_id=<?php echo htmlspecialchars($appointment['doctor_id']) ?>&patient_id=<?php echo htmlspecialchars($appointment['patient_id']) ?>"
                        target='_blank'>Issue referral
                        <i class="fa-solid fa-video"></i></a>
                </div>
            </div>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>


<script src="assets/script.js"></script>
</body>

</html>