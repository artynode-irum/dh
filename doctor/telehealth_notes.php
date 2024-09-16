<?php
session_start();
include '../include/config.php';

// Check if the user is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

// Get appointments ID
$appointments_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $symptoms = $_POST['symptoms'];
    $clicnical_findings = $_POST['clicnical_findings'];
    $diagnosis = $_POST['diagnosis'];
    $plan = $_POST['plan'];

    // Collect all fields into an associative array
    $data = [
        'Symptoms' => $symptoms,
        'Clinical Findings' => $clinical_findings,
        'Diagnosis' => $diagnosis,
        'Plan' => $plan
    ];

    // Initialize file upload variables
    $file_paths = [];
    if (isset($_FILES['upload_files']) && $_FILES['upload_files']['error'][0] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';

        // Ensure the upload directory exists
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Loop through each uploaded file
        foreach ($_FILES['upload_files']['tmp_name'] as $key => $tmp_name) {
            $file_name = basename($_FILES['upload_files']['name'][$key]);
            $target_file = $upload_dir . $file_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($tmp_name, $target_file)) {
                $file_paths[] = $target_file;
            }
        }
    }

    // Get existing data for the appointments
    $query = "SELECT notes FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $appointments_id);
    $stmt->execute();
    $stmt->bind_result($notes);
    $stmt->fetch();
    $stmt->close();

    // Decode the JSON data to an associative array
    $existing_data = json_decode($notes, true);
    if (!is_array($existing_data)) {
        $existing_data = [];
    }

    // Add/update fields with new data
    $existing_data['Symptoms'] = $symptoms;
    $existing_data['Clinical Findings'] = $clicnical_findings;
    $existing_data['Diagnosis'] = $diagnosis;
    $existing_data['Plan'] = $plan;

    // Add file paths if they exist
    if (!empty($file_paths)) {
        $existing_data['File Paths'] = $file_paths;
    }

    // Convert the array to a JSON string
    $json_data = json_encode($existing_data);

    // Update the database with the JSON data
    $query = "UPDATE appointments SET notes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $json_data, $appointments_id);
    $stmt->execute();
    $stmt->close();

    header("Location: telehealth.php");
    exit();
}

// Get existing data for the appointments
$query = "SELECT notes FROM appointments WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointments_id);
$stmt->execute();
$stmt->bind_result($notes);
$stmt->fetch();
$stmt->close();

// Decode the JSON data to an associative array
$existing_data = json_decode($notes, true);

// Provide default values if JSON decoding fails or the fields don't exist
$symptoms = isset($existing_data['Symptoms']) ? htmlspecialchars($existing_data['Symptoms']) : '';
$clicnical_findings = isset($existing_data['Clinical Findings']) ? htmlspecialchars($existing_data['Clinical Findings']) : '';
$diagnosis = isset($existing_data['Diagnosis']) ? htmlspecialchars($existing_data['Diagnosis']) : '';
$plan = isset($existing_data['Plan']) ? htmlspecialchars($existing_data['Plan']) : '';
$file_paths = isset($existing_data['File Paths']) ? $existing_data['File Paths'] : [];
?>

<?php include("include/sidebar.php"); ?>
<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Edit Notes</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div class="container mt-5">
            <h2 class="page-header">Edit Notes for Telehealth ID <?php echo htmlspecialchars($appointments_id); ?></h2>
            <form method="POST" action="" enctype="multipart/form-data">

                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="form-group mt-5 me-md-3" style="width: 100%;">
                        <label for="symptoms"><strong>Symptoms:</strong></label>
                        <textarea name="symptoms" id="symptoms" class="form-control" rows="3"
                            required><?php echo htmlspecialchars($symptoms); ?></textarea>
                    </div>

                    <div class="form-group mt-5" style="width: 100%;">
                        <label for="clinical_findings"><strong>Clinical Findings:</strong></label>
                        <textarea name="clinical_findings" id="clinical_findings" class="form-control"
                            rows="3"><?php echo htmlspecialchars($clicnical_findings); ?></textarea>
                    </div>
                </div>

                <div class="d-flex flex-column flex-md-row justify-content-between">
                    <div class="form-group mt-5 me-md-3" style="width: 100%;">
                        <label for="diagnosis"><strong>Diagnosis:</strong></label>
                        <textarea name="diagnosis" id="diagnosis" class="form-control"
                            rows="3"><?php echo htmlspecialchars($diagnosis); ?></textarea>
                    </div>

                    <div class="form-group mt-5" style="width: 100%;">
                        <label for="plan"><strong>Plan:</strong></label>
                        <textarea name="plan" id="plan" class="form-control"
                            rows="3"><?php echo htmlspecialchars($plan); ?></textarea>
                    </div>
                </div>

                <div class="form-group mt-5" style="width: 100%;">
                    <label for="upload_files"><strong>Upload Documents:</strong></label>
                    <input type="file" name="upload_files[]" id="upload_files" class="form-control" multiple />
                </div>

                <!-- Display uploaded files if exists -->
                <?php if (!empty($file_paths)): ?>
                    <div class="mt-4">
                        <strong>Uploaded Files:</strong>
                        <?php foreach ($file_paths as $file_path): ?>
                            <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file_path)): ?>
                                <img src="<?php echo htmlspecialchars($file_path); ?>" alt="Uploaded Image"
                                    style="max-width: 100%;" />
                            <?php elseif (preg_match('/\.(pdf)$/i', $file_path)): ?>
                                <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank">View PDF</a>
                            <?php else: ?>
                                <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank">Download File</a>
                            <?php endif; ?>
                            <br>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <button type="submit" class="button-class mt-5">Save Notes</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>