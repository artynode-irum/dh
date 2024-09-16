<?php
session_start();
include '../include/config.php';

// Check if the user is logged in and is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

// Initialize message variables
$error_message = '';
$success_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_dates'])) {
    $medical_certificate_id = $_POST['medical_certificate_id'] ?? null;
    $from_date = $_POST['from_date'] ?? null;
    $to_date = $_POST['to_date'] ?? null;

    // Get today's date
    $today = date('Y-m-d');

    if ($medical_certificate_id && $from_date && $to_date) {
        // Validate dates
        if ($from_date < $today || $to_date < $today) {
            $error_message = "Invalid date selection. You cannot select past dates.";
        } elseif ($from_date > $to_date) {
            $error_message = "The 'From Date' cannot be after the 'To Date'.";
        } else {
            // Update dates in the database
            $query = "UPDATE medical_certificate SET from_date = ?, to_date = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $from_date, $to_date, $medical_certificate_id);

            if ($stmt->execute()) {
                $success_message = "Dates updated successfully.";
            } else {
                $error_message = "Database update failed.";
            }
        }
    } else {
        $error_message = "Invalid input.";
    }
}

// Fetch the medical certificate details
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
    echo "<p>No certificate found.</p>";
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
                    <?php echo htmlspecialchars($medical_certificate['doctor_username']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($medical_certificate['description']); ?>
                </p>
                <p><strong>Certificate Type:</strong>
                    <?php echo htmlspecialchars($medical_certificate['certificate_type']); ?></p>
                <p><strong>Date:</strong> From: <?php echo htmlspecialchars($medical_certificate['from_date']); ?> To:
                    <?php echo htmlspecialchars($medical_certificate['to_date']); ?> &nbsp; &nbsp;
                    <i class="fa-solid fa-pen-to-square" onclick="openModal('edit-date-modal')"
                        style="cursor: pointer;"></i>
                </p>
                <p><strong>Reason:</strong> <?php echo htmlspecialchars($medical_certificate['reason']); ?></p>
                <p><strong>Illness Description:</strong>
                    <?php echo htmlspecialchars($medical_certificate['illness_description']); ?></p>
                <p><strong>Certificate Detail:</strong>
                    <?php echo htmlspecialchars($medical_certificate['prescription']); ?></p>
                <p><strong>Attachment:</strong> <a
                        href="<?php echo htmlspecialchars($medical_certificate['document_path']); ?>">View Document</a>
                </p>
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

<!-- Edit Date Modal -->
<div id="edit-date-modal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('edit-date-modal')">&times;</span>
        <h2>Edit Date</h2>
        <form method="POST" action="">
            <input type="hidden" name="medical_certificate_id"
                value="<?php echo htmlspecialchars($medical_certificate_id); ?>">
            <label for="from-date"><b>From Date:</b></label>

            <input type="text" name="from_date" id="fromDate" class="width-hundred" placeholder="Select From Date"
                value="<?php echo htmlspecialchars($medical_certificate['from_date']); ?>" required>
            <br>
            <label for="to-date"><b>To Date:</b></label>

            <input type="text" name="to_date" id="toDate" class="width-hundred" placeholder="Select To Date"
                value="<?php echo htmlspecialchars($medical_certificate['to_date']); ?>" required>
            <br>
            <input type="submit" class="button-class" name="update_dates" value="Save Changes">
        </form>
        <!-- Displaying messages -->
        <script>
            // Display error or success messages from PHP
            <?php if (!empty($error_message)) { ?>
                alert("<?php echo addslashes($error_message); ?>");
            <?php } ?>
            <?php if (!empty($success_message)) { ?>
                alert("<?php echo addslashes($success_message); ?>");
            <?php } ?>
        </script>
    </div>
</div>

<!-- <script>

    // Function to set minimum date for date inputs
    function setMinDate() {
        const today = new Date().toISOString().split('T')[0]; // Today's date
        document.getElementById('from-date').setAttribute('min', today);
        document.getElementById('to-date').setAttribute('min', today);
    }

    // Call the setMinDate function on page load
    document.addEventListener('DOMContentLoaded', setMinDate);
</script> -->

<script src="assets/script.js"></script>
</body>

</html>