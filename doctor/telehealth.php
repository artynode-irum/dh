<?php
session_start();
include '../include/config.php';

// Check if the user is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT appointments.id, 
                 patient.username AS patient_name, 
                 appointments.name, 
                 appointments.description, 
                 appointments.request_date_time, 
                 appointments.status, 
                 appointments.created_date, 
                 appointments.video_link, 
                 appointments.prescription 
          FROM appointments 
          LEFT JOIN patient ON appointments.patient_id = patient.id 
          WHERE appointments.doctor_id = (SELECT id FROM doctor WHERE username = ?)
          ORDER BY appointments.created_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor's Telehealth Appointments</title>
    <link rel="stylesheet" href="assets/styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body> -->
<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Telehealth</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div class="table-responsive">
            <h2 class="page-header">All Appointments</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th class='hide-on-small'>Description</th>
                        <th class='hide-on-small'>Date</th>
                        <th class="hide-on-small">Video Link</th>
                        <th class="hide-on-small">Prescription</th>
                        <th>Notes</th>
                        <th class="hide-on-small">View</th>
                        <th class="show-on-small">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['id']);
                        $patient_name = htmlspecialchars($row['name']);
                        $description = htmlspecialchars($row['description']);
                        $request_date_time = htmlspecialchars($row['request_date_time']);
                        $status = htmlspecialchars($row['status']);
                        $video_link = htmlspecialchars($row['video_link']);
                        $prescription = htmlspecialchars($row['prescription']);

                        echo "<tr>";
                        echo "<td>{$patient_name}</td>";
                        echo "<td class='hide-on-small'>{$description}</td>";
                        echo "<td class='hide-on-small'>{$request_date_time}</td>";
                        echo "<td class='hide-on-small'><a href='{$video_link}' target='_blank'><i class='fa-solid fa-video'></i></a></td>";
                        echo "<td class='hide-on-small'>";
                        echo "<a href='telehealth_prescription.php?id={$id}'><i class='fa-solid fa-clipboard-list add-detail'></i></a>
                         <a href='../frontend/referral_request_doc.php?id={$id}'><i class='fa-solid fa-paste'></i> </a>
                         <a href='../frontend/specialization.php?id={$id}'><i class='fa-solid fa-retweet'></i></a>
                        ";
                        echo "</td>";

                        echo "<td><a href='telehealth_notes.php?id={$id}'><i class='fa-solid fa-pen-to-square'></i></a></td>";

                        echo "<td class='hide-on-small'><a href='telehealth_view.php?id={$id}' class='btn text-primary bg-primary-subtle' style='color: white; text-decoration: none;'>View</a></td>";
                        echo "<td class='show-on-small'><button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";
                        echo "</tr>";

                        echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Telehealth Appointment Details</h2>
                                    <p><strong>Patient Name:</strong> {$patient_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>
                                    <p><strong>Video Session:</strong> <a href='{$video_link}'> Join Session </a></p>
                                    <p><strong>Actions:</strong>
                                    <a href='telehealth_prescription.php?id={$id}'><i class='fa-solid fa-clipboard-list add-detail'></i></a> &nbsp;&nbsp;
                                    <a href='../frontend/referral_request_doc.php?id={$id}'><i class='fa-solid fa-paste'></i> </a> &nbsp;&nbsp;
                                    <a href='../frontend/specialization.php?id={$id}'><i class='fa-solid fa-retweet'></i></a>   
                                    </p>
                                    <p><strong>Status:</strong> {$status}</p>
                                    <a href='telehealth_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
                                </div>
                            </div>
                            ";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>