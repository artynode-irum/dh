<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$prescription_id = $_GET['id'] ?? null;

if ($prescription_id) {
    $query = "SELECT patient.username AS patient_username, patient.email, doctor.username AS doctor_username, doctor.signature AS signature, prescription.description, prescription.prescribe, prescription.created_date, prescription.request_date_time, prescription.payment, prescription.title, prescription.name, prescription.gender, prescription.email, prescription.phone, prescription.dob, prescription.card_number, prescription.security_code, prescription.expiration_date, prescription.country, prescription.treatment, prescription.prescriptionafter, prescription.dosage, prescription.previously_taken_medi, prescription.currentlyppb, prescription.health_condition, prescription.known_allergies, prescription.reason_known_allergies_yes, prescription.over_the_counter_drugs, prescription.healthcare_provider_person_recently, prescription.specific_medication_seeking, prescription.known_nill_allergies, prescription.medication_used_previously, prescription.plan_schedule, prescription.appointment_type, prescription.appointment_day, prescription.appointment_time , prescription.document_path
              FROM prescription 
              JOIN patient ON prescription.patient_id = patient.id 
              JOIN doctor ON prescription.doctor_id = doctor.id 
              WHERE prescription.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $prescription_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $prescription = $result->fetch_assoc();
}

if (!$prescription) {
    echo "<p>Patient not found.</p>";
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

        <h2 class="page-header">Prescription Details</h2>

        <div class="action-buttons">
            <div>
                <a href="prescription.php">
                    <i class="fa-solid fa-caret-left"></i> Back
                </a>
                <a href="../prescription_pdf.php?id=<?php echo htmlspecialchars($prescription_id); ?>">
                    Download PDF <i class="fa-solid fa-angles-down"></i>
                </a>
            </div>
        </div>

        <div class="details-section">
            <!-- Patient Details Section -->
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($prescription['patient_username']); ?></p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($prescription['title']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($prescription['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($prescription['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($prescription['phone']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($prescription['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($prescription['dob']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($prescription['country']); ?></p>
                <p><strong>Document:</strong> <a href="<?php echo $prescription['document_path']; ?>">View Document</a>
                </p>
            </div>

            <!-- Doctor and Prescription Details Section -->
            <div class="details-box">
                <h3>Prescription Details</h3>
                <p><strong>Doctor Name:</strong> <?php echo htmlspecialchars($prescription['doctor_username']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($prescription['description']); ?></p>
                <p><strong>Treatment:</strong> <?php echo htmlspecialchars($prescription['treatment']); ?></p>
                <p><strong>Prescription:</strong> <?php echo htmlspecialchars($prescription['prescriptionafter']); ?>
                </p>
                <p><strong>Dosage:</strong> <?php echo htmlspecialchars($prescription['dosage']); ?></p>
                <p><strong>Appointment Type:</strong> <?php echo htmlspecialchars($prescription['appointment_type']); ?>
                </p>
                <p><strong>Appointment Date-Time:</strong>
                    <?php echo htmlspecialchars($prescription['appointment_day']); ?>
                    at <?php echo htmlspecialchars($prescription['appointment_time']); ?></p>
                <p><strong>Future Plan Schedule:</strong>
                    <?php echo htmlspecialchars($prescription['plan_schedule']); ?>
                    <?php if (!empty($prescription['signature'])): ?>
                    <p class="signature-container">
                        <strong>Signature:</strong>
                        <span class="signature-btn" onclick="openModal('signature')">View Signature</span>
                    </p>
                    <div id="signature" class='modal'>
                        <div class='modal-content'>
                            <span class='close' onclick="closeModal('signature')">&times;</span>
                            <img src="../assets/img/<?php echo htmlspecialchars($prescription['signature']); ?>"
                                alt="Doctor's Signature" width="150">
                        </div>
                    </div>
                <?php endif; ?>
                </p>
                <p><strong>Created At:</strong>
                    <span><?php echo htmlspecialchars($prescription['created_date']); ?></span>
            </div>

            <!-- Medical History Section -->
            <div class="details-box">
                <h3>Medical History</h3>
                <p><strong>Previously Taken Any Medicine:</strong>
                    <?php echo htmlspecialchars($prescription['previously_taken_medi']); ?></p>
                <p><strong>Pregnancy Information:</strong>
                    <?php echo htmlspecialchars($prescription['currentlyppb']); ?>
                </p>
                <p><strong>Health Condition:</strong> <?php echo htmlspecialchars($prescription['health_condition']); ?>
                </p>
                <p><strong>Allergies:</strong> <?php echo htmlspecialchars($prescription['known_allergies']); ?></p>
                <p><strong>Reason for Allergies:</strong>
                    <?php echo htmlspecialchars($prescription['reason_known_allergies_yes']); ?></p>
                <p><strong>Healthcare Provider:</strong>
                    <?php echo htmlspecialchars($prescription['healthcare_provider_person_recently']); ?></p>
                <p><strong>Specific Medication You Are Seeking:</strong>
                    <?php echo htmlspecialchars($prescription['specific_medication_seeking']); ?></p>
                <p><strong>Known Allegies:</strong>
                    <?php echo htmlspecialchars($prescription['known_nill_allergies']); ?></p>
                <p><strong>Medication Used Previously:</strong>
                    <?php echo htmlspecialchars($prescription['medication_used_previously']); ?></p>
            </div>

        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>


<script src="assets/script.js"></script>
</body>

</html>