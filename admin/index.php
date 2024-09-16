<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$sqli = "SELECT * FROM admin WHERE username='$username'";
$results = $conn->query($sqli);

if ($results->num_rows > 0) {
    $user = $results->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<?php
// Assume you have a connection to your database in $conn
$appointments = "SELECT COUNT(*) as total FROM appointments";
$appointments_result = $conn->query($appointments);
$appointments_row = $appointments_result->fetch_assoc();
$totalAppointments = $appointments_row['total'];
?>

<?php

// Assume you have a connection to your database in $conn
$medical_certificate = "SELECT COUNT(*) as certificates FROM medical_certificate";
$medical_certificate_result = $conn->query($medical_certificate);
$medical_certificate_row = $medical_certificate_result->fetch_assoc();
$totalCertificates = $medical_certificate_row['certificates'];
?>

<?php

// Assume you have a connection to your database in $conn
$prescription = "SELECT COUNT(*) as prescription FROM prescription";
$prescription_result = $conn->query($prescription);
$prescription_row = $prescription_result->fetch_assoc();
$totalPrescription = $prescription_row['prescription'];
?>

<?php

// Assume you have a connection to your database in $conn
$referrals = "SELECT COUNT(*) as referrals FROM referrals";
$referrals_result = $conn->query($referrals);
$referrals_row = $referrals_result->fetch_assoc();
$totalReferrals = $referrals_row['referrals'];
?>

<?php
include("include/sidebar.php")
    ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Dashboard</span>
            </div>
            <?php
            include("include/navbar.php")
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
                            <img class="profile-image" src="assets/img/<?php
                            echo $user['profile_picture'];
                            ?>" alt="Profile Picture" width="100">
                            <h5><b><?php
                            echo $username;
                            ?></b></h5>
                            <span><?php echo $user['email']; ?></span>
                        </div>
                        <div class="card-footer-right">
                            <span><b>Edit Profile</a></b></span>
                            <a href="profile.php"><button class="button-class" style="text-wrap: nowrap;">Profile <i
                                        class="fa-solid fa-arrow-right-long"></i></button></a>
                        </div>
                    </div>
                </div>

            </div>
            <div class="right-section">
                <div class="total-section">
                    <a href="telehealth.php">
                        <div>
                            <span>Total Appointments
                                <b><?php echo $totalAppointments; ?></b>
                            </span>
                            <span><i class="fa-solid fa-user-doctor"></i></span>
                        </div>
                    </a>

                    <a href="medical_certificate.php">
                        <div>
                            <span>Total Medical Certificates

                                <b><?php echo $totalCertificates; ?></b>
                            </span>

                            <span><i class="fa-solid fa-calendar-check"></i></span>
                        </div>
                    </a>
                </div>



                <div class="total-section">
                    <a href="prescription.php">
                        <div>
                            <span>Total Prescriptions
                                <b><?php echo $totalPrescription; ?></b>
                            </span>
                            <span><i class="fa-solid fa-user-doctor"></i></span>
                        </div>
                    </a>

                    <a href="referrals.php">
                        <div>
                            <span>Total Referrals
                                <b><?php echo $totalReferrals; ?></b>
                            </span>
                            <span><i class="fa-solid fa-calendar-check"></i></span>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>
    <?php
    include("include/footer.php")
        ?>
</div>
</div>

<script src="assets/script.js"></script>
</body>

</html>