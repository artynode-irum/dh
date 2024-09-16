<?php
session_start();
include '../include/config.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ./index.php");
    exit();
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id']) && isset($_POST['doctor_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $doctor_id = intval($_POST['doctor_id']);
    $signature = $_POST['signature']; // Signature input is now optional

    // Validate inputs
    if (!is_numeric($appointment_id) || !is_numeric($doctor_id)) {
        die("Invalid input.");
    }

    // Check if the appointment exists
    $appointment_query = "SELECT * FROM appointments WHERE id = ?";
    $appointment_stmt = $conn->prepare($appointment_query);
    $appointment_stmt->bind_param("i", $appointment_id);
    $appointment_stmt->execute();
    $appointment_result = $appointment_stmt->get_result();

    if ($appointment_result->num_rows == 0) {
        die("Appointment not found.");
    }

    // Fetch the doctor's signature
    $doctor_query = "SELECT signature FROM doctor WHERE id = ?";
    $doctor_stmt = $conn->prepare($doctor_query);
    $doctor_stmt->bind_param("i", $doctor_id);
    $doctor_stmt->execute();
    $doctor_result = $doctor_stmt->get_result();
    $doctor = $doctor_result->fetch_assoc();

    if (!$doctor) {
        die("Doctor not found.");
    }

    $doctor_signature = $doctor['signature']; // Use the doctor's signature

    // Prepare SQL statement to update the appointment
    $query = "UPDATE appointments SET doctor_id = ?, signature = ?, status = 'assigned', video_link = CONCAT('https://meet.jit.si/room', ?) WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // $unique_video_link = uniqid(); // Generate unique video link
    $unique_video_link = "doctor-help-" . uniqid(); // Generate unique video link
    $stmt->bind_param("issi", $doctor_id, $doctor_signature, $unique_video_link, $appointment_id);

    // Execute the statement
    if (!$stmt->execute()) {
        die("Error updating record: " . $stmt->error);
    }

    // Redirect to telehealth.php with success message
    header("Location: telehealth.php?success=1");
    exit();
}

// Fetch appointment details
$appointment_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointment_id) {
    $appointment_query = "SELECT name FROM appointments WHERE id = ?";
    $appointment_stmt = $conn->prepare($appointment_query);
    $appointment_stmt->bind_param("i", $appointment_id);
    $appointment_stmt->execute();
    $appointment_result = $appointment_stmt->get_result();
    $appointment = $appointment_result->fetch_assoc();

    if (!$appointment) {
        die("Appointment not found.");
    }
} else {
    die("No appointment ID provided.");
}

// Fetch all doctors
$doctors_query = "SELECT id, username FROM doctor";
$doctors_result = $conn->query($doctors_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Doctor</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
    <?php include("include/sidebar.php"); ?>

    <div class="second-section">
        <section>
            <div class="navbar">
                <div class="page-title">
                    <span>Assign Doctor</span>
                </div>
                <?php include("include/navbar.php"); ?>
            </div>

            <div class="assign-doctor">
                <h2 class="page-header">Assign Doctor to Appointment</h2>
                <form action="" method="post">
                    <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">
                    <label for="doctor_id">Select Doctor:</label>
                    <select name="doctor_id" id="doctor_id" required>
                        <option value="">Select Doctor</option>
                        <?php
                        if ($doctors_result) {
                            while ($doctor = $doctors_result->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($doctor['id']) . "'>" . htmlspecialchars($doctor['username']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No doctors available</option>";
                        }
                        ?>
                    </select>
                    <!-- <label for="signature">Doctor Signature (Optional):</label>
                <input type="text" name="signature" id="signature" value=""> -->
                    <button type="submit" class="button-class">Assign Doctor</button>
                </form>
            </div>
        </section>
        <?php include("include/footer.php"); ?>
    </div>

    <script src="assets/script.js"></script>
</body>

</html>

<?php
$conn->close();
?>