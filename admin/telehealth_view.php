<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    $query = "SELECT patient.username AS patient_username, doctor.username AS doctor_username, doctor.signature AS signature, patient.email, appointments.description, appointments.video_link, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date 
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
                <a href="<?php echo htmlspecialchars($appointment['video_link']); ?>" target='_blank'>
                    Join Session <i class="fa-solid fa-video"></i>
                </a>
            </div>
        </div>

        <div class="details-section">
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($appointment['title']); ?>
                    <?php echo htmlspecialchars($appointment['name']); ?>
                </p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($appointment['phone']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($appointment['gender']); ?></p>
                <p><strong>DOB:</strong> <?php echo htmlspecialchars($appointment['dob']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($appointment['country']); ?></p>
            </div>

            <div class="details-box">
                <h3>Appointment Details</h3>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($appointment['appointment_type']); ?></p>
                <p><strong>Date-Time:</strong> <?php echo htmlspecialchars($appointment['appointment_day']); ?> at
                    <?php echo htmlspecialchars($appointment['appointment_time']); ?>
                </p>
                <p><strong>Request Date:</strong> <?php echo htmlspecialchars($appointment['request_date_time']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($appointment['created_date']); ?></p>
                <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($appointment['doctor_username']); ?></p>
                <p class="signature-container">
                    <strong>Signature:</strong>
                    <span class="signature-btn" onclick="openModal('signature')">Signature</span>
                </p>

                <div id="signature" class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick="closeModal('signature')">&times;</span>
                        <img src="../doctor/assets/img/<?php echo htmlspecialchars($appointment['signature']); ?>"
                            alt="Doctor's Signature">
                    </div>
                </div>

            </div>

            <div class="details-box">
                <h3>Medical Info</h3>
                <p><strong>Medicare Number:</strong> <?php echo htmlspecialchars($appointment['medicare_number']); ?>
                </p>
                <p><strong>Expiry Date:</strong>
                    <?php echo htmlspecialchars($appointment['medicare_expiration_date']); ?></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($appointment['prescription']); ?></p>
            </div>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>