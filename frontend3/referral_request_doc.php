<?php
session_start();
include '../include/config.php';

$appointment_id = $_GET['id'] ?? null;

if ($appointment_id) {
    $query = "SELECT patient.username AS patient_username, doctor.username AS doctor_username, doctor.signature AS signature, patient.email, appointments.description, appointments.video_link,appointments.address, appointments.prescription, appointments.created_date, appointments.request_date_time, appointments.payment, appointments.name, appointments.email, appointments.phone, appointments.dob, appointments.card_number, appointments.security_code, appointments.country, appointments.gender, appointments.title, appointments.appointment_type, appointments.appointment_day, appointments.appointment_time, appointments.medicare_number, appointments.medicare_expiration_date, appointments.notes 
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

// Split the 'name' into first name and last name
$name_parts = explode(' ', $appointment['name']);
$fname = $name_parts[0] ?? ''; // First name
$lname = isset($name_parts[1]) ? implode(' ', array_slice($name_parts, 1)) : ''; // Last name



// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tests_required = $_POST['tests_required'] ?? '';
    $clinic_notes = $_POST['clinic_notes'] ?? '';
    $recipient_email = $_POST['email'] ?? '';  // Capture the email address from the form submission

    // Sanitize input
    $tests_required = htmlspecialchars($tests_required);
    $clinic_notes = htmlspecialchars($clinic_notes);
    $recipient_email = htmlspecialchars($recipient_email);  // Sanitize the email address


    // Update the database
    $update_query = "UPDATE appointments SET tests_required = ?, clinic_notes = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssi", $tests_required, $clinic_notes, $appointment_id);
    // $update_stmt->execute();

    // // Redirect to telehealth.php
    // header("Location: ../doctor/telehealth.php");
    // exit();
    if ($update_stmt->execute()) {
        // Require the email sending script
        require '../emails/patient_referral_form.php';
        // Pass the email address to the script
        sendSpecialistEmail($recipient_email);  // Call the function to send the email
        // header("Location: ../doctor/telehealth.php");
        // Redirect to telehealth.php
        header("Location: ../doctor/telehealth.php");
        exit();
    }
}

?>

<?php include("assets/navbar.php"); ?>

<div class="container-fluid m-0 p-0">
    <div>
        <div>
            <div class="doc-referral-top">
                <hr>
                <span>
                    <div>
                        <img src="assets/img/pdf-logo.png" alt="">
                    </div>
                    <div>
                        <h2>Patient Referral Form</h2>
                    </div>
                </span>
                <hr>
            </div>
            <h2 class="medicare-number-heading">Medicare Number</h2>
            <form action="" method="POST">
                <div class="doctor_referral">
                    <section>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="fname" id="fname" class="width-hundred" required
                                        placeholder="First Name" value="<?php echo htmlspecialchars($fname); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fas fa-user"></i>
                                    <input type="text" name="lname" id="lname" class="width-hundred" required
                                        placeholder="Last Name" value="<?php echo htmlspecialchars($lname); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-cake-candles"></i>
                                    <input type="text" name="dob" id="dob" class="width-hundred"
                                        placeholder="Date of Birth"
                                        value="<?php echo htmlspecialchars($appointment['dob']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-envelope"></i>
                                    <input type="email" class="width-hundred" name="email" id="email" required
                                        placeholder="Write Email"
                                        value="<?php echo htmlspecialchars($appointment['email']); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-phone-flip"></i>
                                    <input type="number" class="width-hundred" name="phone" id="phone" required
                                        placeholder="Phone Number"
                                        value="<?php echo htmlspecialchars($appointment['phone']); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <input name="address" id="address" class="width-hundred" placeholder="Your address"
                                        value="<?php echo htmlspecialchars($appointment['address']); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fas fa-venus-mars"></i>
                                    <input type="text" name="gender" id="gender" class="width-hundred" required
                                        placeholder="Select Gender"
                                        value="<?php echo htmlspecialchars($appointment['gender']); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-hashtag"></i>
                                    <input type="text" class="width-hundred" name="medicare_number" id="medicare_number"
                                        maxlength="12" placeholder="Enter medicare number"
                                        oninput="formatMedicareNumber(this)"
                                        value="<?php echo htmlspecialchars($appointment['medicare_number']); ?>">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <i class="fa-solid fa-hashtag"></i>
                                    <input type="text" class="width-hundred" name="reference" id="reference"
                                        placeholder="Reference">
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="doctor-referral-second-section">
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group doc-second-section-divs">
                                    <label for="tests_required">Tests Required</label>
                                    <input type="text" name="tests_required" id="tests_required" class="width-hundred"
                                      placeholder="Tests Required"  required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="input-group doc-second-section-divs">
                                    <label for="clinic_notes">Clinic Notes</label>
                                    <input type="text" name="clinic_notes" id="clinic_notes" class="width-hundred" placeholder="Clinical Notes"
                                        required>
                                        
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group doc-second-section-divs">
                                    <label for="requesting-doctor">Requesting Doctor (Provider Number, Surname,
                                        Initials, Address)</label>
                                    <input type="text" name="requesting-doctor" id="requesting-doctor"
                                        class="width-hundred"
                                        value="<?php echo htmlspecialchars($appointment['doctor_username']); ?>"
                                        required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group doc-second-section-divs">
                                    <label for="doctors-signature">Doctor's Signature and Request Date</label>
                                    <img name="doctors-signature" id="doctors-signature" class="width-hundred"
                                        src="../doctor/assets/img/<?php echo htmlspecialchars($appointment['signature']); ?>" style="height: 50px; width: 100px"
                                        alt="Doctor's Signature" required>
                                        <input type="date" name="date" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-3">
                                <div class="input-group doc-second-section-divs">
                                    <label for="lab-use-only">Laboratory Use Only</label>
                                    <input type="text" name="lab-use-only" id="lab-use-only" class="width-hundred"
                                        placeholder="Time:" >
                                    <input type="text" name="lab-use-only" id="lab-use-only" class="width-hundred"
                                        placeholder="Date:" >
                                    <!-- <input type="date" name="appointment_day" id="appointment-day" class="width-hundred"
                                        required min="<?php 
                                        // echo date('Y-m-d'); ?>"> -->
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group doc-second-section-divs">
                                    <label for="specimen-collected">Specimen Collected</label>
                                    <input type="text" name="specimen-collected" id="specimen-collected"
                                        class="width-hundred" placeholder="Collected by:" >
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group doc-second-section-divs">
                                    <label for="specimen-received">Specimen Received</label>
                                    <input type="text" name="specimen-received" id="specimen-received"
                                        class="width-hundred" placeholder="Received by:" >
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <div class="input-group doc-second-section-divs">
                                    <label for="doctor-help">Doctor Help</label>
                                    <input type="text" name="doctor-help" id="doctor-help" class="width-hundred" row="5"
                                        >
                                </div>
                            </div>
                        </div>
                        <div class="doc-referral-bottom">
                            <div class="">
                                <h6><b>Notes to patient:</b></h6>
                                <small>This form is valid for use with any service provider in Australia. Please make
                                    arrangements to forward the results to your usual General Practitioner.</small>
                            </div>
                            <div class="">
                                <h6><b>Notes to the provider:</b></h6>
                                <small>We are an Online Tele-Health service provider. Please forward the results/reports
                                    to the Patient's usual General Practitioner along with a copy to us. Our health link
                                    ID is ------ and fax number is ------.</small>
                            </div>
                        </div>
                        <button type="submit" name="register" class="button-class my-4">
                            Submit
                        </button>
                    </section>
                </div>
            </form>
        </div>
        <?php include("../include/footer.php"); ?>
    </div>
</div>

<script src="assets/script.js"></script>

</body>
</html>