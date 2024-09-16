<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
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

        <!-- <div class="content"> -->
            <div class="detail-page mt-5">
                <span>
                    <h2 class="page-header">Referrals Details</h2>
                    <span>
                        <a href="referrals.php" class="button-class" style="color: white; text-decoration: none;"> <i
                                class="fa-solid fa-caret-left"></i> Back</a>
                        <a href="../referrals_pdf.php?id=<?php echo htmlspecialchars($referrals_id); ?>"
                            class="button-class" style="color: white; text-decoration: none;">Download PDF <i
                                class="fa-solid fa-angles-down"></i> </a>
                        <!-- <a href="../specialist_letter.php?id=<?php echo htmlspecialchars($referrals_id); ?>"
                            class="button-class" style="color: white; text-decoration: none;">Download specialist letter PDF <i
                                class="fa-solid fa-angles-down"></i> </a> -->
                    </span>
                </span>
                <p><strong>Patient Username:</strong>
                    <span><?php echo htmlspecialchars($referrals['patient_username']); ?></span>
                </p>
                <p><strong>Title:</strong>
                    <span><?php echo htmlspecialchars($referrals['title']); ?></span>
                </p>
                <p><strong>Patient Name:</strong>
                    <span><?php echo htmlspecialchars($referrals['name']); ?></span>
                </p>
                <p><strong>Email:</strong> <span><?php echo htmlspecialchars($referrals['email']); ?></span></p>

                <p><strong>Date of Birth:</strong> <span><?php echo htmlspecialchars($referrals['dob']); ?></span></p>

                <p><strong>Gender:</strong> <span><?php echo htmlspecialchars($referrals['gender']); ?></span></p>

                <p><strong>Address:</strong> <span><?php echo htmlspecialchars($referrals['address']); ?></span></p>

                <p><strong>Country:</strong> <span><?php echo htmlspecialchars($referrals['country']); ?></span></p>

                <p><strong>Phone:</strong> <span><?php echo htmlspecialchars($referrals['phone']); ?></span></p>

                <p><strong>Doctor Name:</strong>
                    <span><?php echo htmlspecialchars($referrals['doctor_username']); ?></span>
                </p>
                <p><strong>Description:</strong> <span><?php echo htmlspecialchars($referrals['description']); ?></span>
                </p>


                <p><strong>Prescription:</strong>
                    <span><?php echo htmlspecialchars($referrals['prescription']); ?></span>
                </p>

                <p><strong>What type of referral are you after?</strong>
                    <span><?php echo htmlspecialchars($referrals['referral_type']); ?></span>
                </p>

                <p><strong>Referral Provider Name or Number:</strong>
                    <span><?php echo htmlspecialchars($referrals['referral_provider']); ?></span>
                </p>

                <p><strong>Details:</strong>
                    <span><?php echo htmlspecialchars($referrals['other_details']); ?></span>
                </p>

                <p><strong>Appointment Type:</strong>
                    <span><?php echo htmlspecialchars($referrals['appointment_type']); ?></span>
                </p>

                <p><strong>Appointment Date:</strong>
                    <span><?php echo htmlspecialchars($referrals['appointment_day']); ?></span>
                </p>

                <p><strong>Appointment Time:</strong>
                    <span><?php echo htmlspecialchars($referrals['appointment_time']); ?></span>
                </p>

                <p><strong>Medicare Number:</strong>
                    <span><?php echo htmlspecialchars($referrals['medicare_number']); ?></span>
                </p>


                <p><strong>Request Date Time:</strong>
                    <span><?php echo htmlspecialchars($referrals['request_date_time']); ?></span>
                </p>

                <p><strong>Created At:</strong> <span><?php echo htmlspecialchars($referrals['created_date']); ?></span>
                </p>

                <p><strong>Doctor's Signature:</strong> <span>
                        <img src="../doctor/assets/img/<?php echo htmlspecialchars($referrals['signature']); ?>"
                            alt="Doctor's Signature" width="150"></span></p>

            <!-- </div> -->

        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>