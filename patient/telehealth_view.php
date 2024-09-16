<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    $query = "SELECT patient.username AS patient_username, doctor.username AS doctor_username, doctor.signature AS signature, patient.email, appointments.description, appointments.video_link, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date, appointments.addressee_fname, appointments.tests_required
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

                <?php
                if (!empty($appointment['addressee_fname'])) {
                    echo " 
                    <a href='../specialist_letter.php?id={$appointment_id}' class='button-class' style='color: white; text-decoration: none;'>Download Specialist letter <i class='fa-solid fa-angles-down'></i> </a>
                    ";
                }
                ?>

                <?php
                if (!empty($appointment['tests_required'])) {
                    echo " 
                    <a href='../referral_patient_pdf.php?id={$appointment_id}' class='button-class' style='color: white; text-decoration: none;'>Download Referral <i class='fa-solid fa-angles-down'></i> </a>
                    ";
                }
                ?>

                <?php
                // echo " 
                // <a href='../referral_patient.php?id={$appointment_id}' class='button-class'
                // style='color: white; text-decoration: none;'>Download specialist letter <i
                // class='fa-solid fa-angles-down'></i> </a>
                //   ";
                ?>

                <!-- Uncomment if needed -->
                <!-- <a href="../telehealth_pdf.php?id=<?php
                // echo htmlspecialchars($appointment_id); ?>">
                    Download PDF <i class="fa-solid fa-angles-down"></i>
                </a> -->
            </div>
        </div>

        <div class="details-section">
            <!-- Patient Details Section -->
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($appointment['name']); ?></p>
                <p><strong>Patient Email:</strong> <?php echo htmlspecialchars($appointment['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($appointment['phone']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($appointment['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($appointment['dob']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($appointment['country']); ?></p>
            </div>

            <!-- Doctor and Appointment Info Section -->
            <div class="details-box">
                <h3>Appointment Information</h3>
                <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($appointment['doctor_username']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($appointment['description']); ?></p>
                <p><strong>Video Link:</strong> <a href="<?php echo htmlspecialchars($appointment['video_link']); ?>"
                        target='_blank'>Join Session</a></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($appointment['prescription']); ?></p>
            </div>

            <!-- Appointment Timing Section -->
            <div class="details-box">
                <h3>Appointment Timing</h3>
                <p><strong>Appointment Type:</strong> <?php echo htmlspecialchars($appointment['appointment_type']); ?>
                    at <?php echo htmlspecialchars($appointment['appointment_time']); ?></p>
                <p><strong>Appointment Date-Time:</strong>
                    <?php echo htmlspecialchars($appointment['appointment_day']); ?> at
                    <?php echo htmlspecialchars($appointment['appointment_time']); ?>
                </p>
            </div>

            <!-- Medicare and Request Info Section -->
            <div class="details-box">
                <h3>Medicare & Request Info</h3>
                <p><strong>Medicare Number:</strong> <?php echo htmlspecialchars($appointment['medicare_number']); ?>
                </p>
                <p><strong>Medicare Expiry:</strong>
                    <?php echo htmlspecialchars($appointment['medicare_expiration_date']); ?></p>
                <p><strong>Request Date-Time:</strong>
                    <?php echo htmlspecialchars($appointment['request_date_time']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($appointment['created_date']); ?></p>
            </div>

            <!-- Doctor's Signature Section -->
            <div class="details-box">
                <h3>Doctor's Signature</h3>
                <p><img src="../doctor/assets/img/<?php echo htmlspecialchars($appointment['signature']); ?>"
                        alt="Doctor's Signature" width="150"></p>
            </div>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>


<script src="assets/script.js"></script>
</body>

</html>