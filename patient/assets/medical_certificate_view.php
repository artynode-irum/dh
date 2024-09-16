<?php
session_start();
include '../include/config.php';
// include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

$medical_certificate_id = $_GET['id'] ?? null;

if ($medical_certificate_id) {
    $query = "SELECT patient.username AS patient_username, doctor.username AS doctor_username, doctor.signature AS signature, medical_certificate.description, medical_certificate.certificate_type, medical_certificate.reason, medical_certificate.illness_description, medical_certificate.prescription, medical_certificate.created_date, medical_certificate.from_date, medical_certificate.to_date, medical_certificate.payment, medical_certificate.request_date_time, medical_certificate.name, medical_certificate.email, medical_certificate.phone, medical_certificate.dob, medical_certificate.card_number, medical_certificate.security_code, medical_certificate.expiration_date, medical_certificate.gender, medical_certificate.title, medical_certificate.country, medical_certificate.document_path
              FROM medical_certificate 
              LEFT JOIN patient ON medical_certificate.patient_id = patient.id 
              LEFT JOIN doctor ON medical_certificate.doctor_id = doctor.id 
              WHERE medical_certificate.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $medical_certificate_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $medical_certificate = $result->fetch_assoc();
}

if (!$medical_certificate) {
    echo "<p>No certficate not found.</p>";
    exit();
}
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Medical Certificate</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <h2 class="page-header">Medical Certificate Details</h2>

        <div class="action-buttons">
            <div>
                <a href="medical_certificate.php">
                    <i class="fa-solid fa-caret-left"></i> Back
                </a>
                <a href="../medical_certificate_pdf.php?id=<?php echo htmlspecialchars($medical_certificate_id); ?>">
                    Download PDF <i class="fa-solid fa-angles-down"></i>
                </a>
            </div>
        </div>

        <div class="details-section">
            <!-- Patient Details Section -->
            <div class="details-box">
                <h3>Patient Details</h3>
                <p><strong>Username:</strong> <?php echo htmlspecialchars($medical_certificate['patient_username']); ?>
                </p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($medical_certificate['title']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($medical_certificate['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($medical_certificate['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($medical_certificate['phone']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($medical_certificate['gender']); ?></p>
                <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($medical_certificate['dob']); ?></p>
                <p><strong>Country:</strong> <?php echo htmlspecialchars($medical_certificate['country']); ?></p>
                <p><strong>Created At:</strong> <?php echo htmlspecialchars($medical_certificate['created_date']); ?>
                </p>
            </div>

            <!-- Certificate Details Section -->
            <div class="details-box">
                <h3>Certificate Details</h3>
                <p><strong>Doctor Name:</strong>
                    <?php echo htmlspecialchars($medical_certificate['doctor_username']); ?>
                </p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($medical_certificate['description']); ?>
                </p>
                <p><strong>Certificate Type:</strong>
                    <?php echo htmlspecialchars($medical_certificate['certificate_type']); ?></p>
                <p><strong>Date:</strong> From: <?php echo htmlspecialchars($medical_certificate['from_date']); ?> To:
                    <?php echo htmlspecialchars($medical_certificate['to_date']); ?>
                </p>
                <p><strong>Reason:</strong> <?php echo htmlspecialchars($medical_certificate['reason']); ?></p>
                <p><strong>Illness Description:</strong>
                    <?php echo htmlspecialchars($medical_certificate['illness_description']); ?></p>
                <p><strong>Cetificate Detail:</strong>
                    <?php echo htmlspecialchars($medical_certificate['prescription']); ?>
                </p>
                <p><strong>Attachment:</strong> <a href="<?php echo $medical_certificate['document_path']; ?>">View
                        Document</a></p>
                <p class="signature-container">
                    <strong>Signature:</strong>
                    <span class="signature-btn" onclick="openModal('signature')">View Signature</span>
                </p>
                <div id="signature" class='modal'>
                    <div class='modal-content'>
                        <span class='close' onclick="closeModal('signature')">&times;</span>
                        <img src="../assets/img/<?php echo htmlspecialchars($medical_certificate['signature']); ?>"
                            alt="Doctor's Signature" width="150">
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>