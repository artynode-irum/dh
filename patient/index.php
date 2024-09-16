<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM patient WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $patient_id = $user['id']; // Get the patient's ID
} else {
    echo "User not found.";
    exit();
}
?>

<?php
// Count total appointments for the logged-in patient
$sql_total_appointments = "SELECT COUNT(*) AS total FROM appointments WHERE patient_id='$patient_id'";
$result_total_appointments = $conn->query($sql_total_appointments);
$row_total_appointments = $result_total_appointments->fetch_assoc();
$total_appointments = $row_total_appointments['total'];

// Count today's appointments for the logged-in patient
$today = date('Y-m-d'); // Format for comparison
$sql_today_appointments = "SELECT COUNT(*) AS today FROM appointments WHERE patient_id='$patient_id' AND DATE(created_date)='$today'";
$result_today_appointments = $conn->query($sql_today_appointments);
$row_today_appointments = $result_today_appointments->fetch_assoc();
$todays_appointments = $row_today_appointments['today'];

// Total medical certificates for the logged-in patient
$medical_certificate = "SELECT COUNT(*) as certificates FROM medical_certificate WHERE patient_id='$patient_id'";
$medical_certificate_result = $conn->query($medical_certificate);
$medical_certificate_row = $medical_certificate_result->fetch_assoc();
$totalCertificates = $medical_certificate_row['certificates'];

// Total Prescriptions for the logged-in patient
$prescription = "SELECT COUNT(*) as prescription FROM prescription WHERE patient_id='$patient_id'";
$prescription_result = $conn->query($prescription);
$prescription_row = $prescription_result->fetch_assoc();
$totalprescription = $prescription_row['prescription'];
?>

<?php
include("include/sidebar.php")
    ?>
<!-- HTML Code for Displaying Data -->
<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Dashboard</span>
            </div>
            <?php include("include/navbar.php") ?>
        </div>
        <div class="dashboard-content-section">
            <div class="left-section">
                <div class="card">
                    <div class="card-header">
                        <span style="font-size: 22px;">Welcome Back !</span>
                        <span style="font-size: 16px;">Dashboard </span>
                    </div>
                    <div class="card-footer">
                        <div class="card-footer-left">
                            <img class="profile-image" src="assets/img/<?php echo $user['profile_picture']; ?>"
                                alt="Profile Picture" width="100">
                            <h5><b><?php echo $username; ?></b></h5>
                            <span><?php echo $user['email']; ?></span>
                        </div>
                        <div class="card-footer-right">
                            <span><b>Edit Profile</b></span>
                            <a href="profile.php"><button class="button-class" style="text-wrap: nowrap;">Profile <i
                                        class="fa-solid fa-arrow-right-long"></i></button></a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="right-section">
                <div class="total-section">
                    <div>
                        <span>Total Appointments
                            <b><?php echo $total_appointments; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-plus"></i></span>
                    </div>

                    <div>
                        <span>Medical Certificates
                            <b><?php echo $totalCertificates; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-check"></i></span>
                    </div>
                </div>
                <div class="total-section">
                    <div>
                        <span>Telehealth
                            <b><?php echo $total_appointments; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-plus"></i></span>
                    </div>

                    <div>
                        <span>Prescriptions
                            <b><?php echo $totalprescription; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-check"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php include("include/footer.php") ?>
</div>
<script src="assets/script.js"></script>
</body>

</html>