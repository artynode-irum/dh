<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM doctor WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $doctor_id = $user['id']; // Get the doctor's ID
} else {
    echo "User not found.";
    exit();
}
?>

<?php
// Count total appointments for the logged-in doctor
$sql_total_appointments = "SELECT COUNT(*) AS total FROM appointments WHERE doctor_id='$doctor_id'";
$result_total_appointments = $conn->query($sql_total_appointments);
$row_total_appointments = $result_total_appointments->fetch_assoc();
$total_appointments = $row_total_appointments['total'];

// Assume you have a connection to your database in $conn
$prescription = "SELECT COUNT(*) as prescription FROM prescription WHERE doctor_id='$doctor_id'";
$prescription_result = $conn->query($prescription);
$prescription_row = $prescription_result->fetch_assoc();
$totalPrescription = $prescription_row['prescription'];

// Count today's appointments for the logged-in doctor
$today = date('Y-m-d'); // Format for comparison
$sql_today_appointments = "SELECT COUNT(*) AS today FROM appointments WHERE doctor_id='$doctor_id' AND DATE(created_date)='$today'";
$result_today_appointments = $conn->query($sql_today_appointments);
$row_today_appointments = $result_today_appointments->fetch_assoc();
$todays_appointments = $row_today_appointments['today'];

// Total medical certificates for the logged-in doctor
$medical_certificate = "SELECT COUNT(*) as certificates FROM medical_certificate WHERE doctor_id='$doctor_id'";
$medical_certificate_result = $conn->query($medical_certificate);
$medical_certificate_row = $medical_certificate_result->fetch_assoc();
$totalCertificates = $medical_certificate_row['certificates'];


// Assume you have a connection to your database in $conn
$referrals = "SELECT COUNT(*) as referrals FROM referrals WHERE doctor_id='$doctor_id'";
$referrals_result = $conn->query($referrals);
$referrals_row = $referrals_result->fetch_assoc();
$totalReferrals = $referrals_row['referrals'];

?>

<?php
include("include/sidebar.php");
?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Dashboard</span>
            </div>
            <?php
            include("include/navbar.php");
            ?>
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
                <div class="total-section">
                    <div>
                        <span>Today's Appointments
                            <b><?php echo $total_appointments; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-plus"></i></span>
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
                        <span>Referrals
                            <b><?php echo $totalReferrals; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-plus"></i></span>
                    </div>
                    <div>
                        <span>Prescriptions
                            <b><?php echo $totalPrescription; ?></b>
                        </span>
                        <span><i class="fa-solid fa-calendar-plus"></i></span>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    include("include/footer.php");
    ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>