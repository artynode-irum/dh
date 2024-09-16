<?php
session_start();
include '../include/config.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Query medical_certificate with ordering by created_date in descending order
$query = "SELECT medical_certificate.id, patient.username as patient_name, medical_certificate.description, medical_certificate.name, medical_certificate.status, medical_certificate.request_date_time, medical_certificate.created_date, medical_certificate.prescription, doctor.username as doctor_name 
          FROM medical_certificate 
          LEFT JOIN patient ON medical_certificate.patient_id = patient.id 
          LEFT JOIN doctor ON medical_certificate.doctor_id = doctor.id 
          ORDER BY medical_certificate.created_date DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
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

        <div class="table-responsive">
            <h2 class="page-header">All Medical Certificates</h2>

            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th class="hide-on-small">Last Updated</th>
                        <th class="hide-on-small">Description</th>
                        <th class="hide-on-small">Status</th>
                        <th class="hide-on-small" style="width:160px;">Action</th>
                        <th class="hide-on-small">Assigned Doctor</th>
                        <th class="hide-on-small">Created At</th>
                        <th class="show-on-small">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row['id']);
                            $patient_name = htmlspecialchars($row['name']) ?: 'N/A';
                            $request_date_time = htmlspecialchars($row['request_date_time']);
                            $description = htmlspecialchars($row['description']);
                            $status = htmlspecialchars($row['status']);
                            $doctor_name = htmlspecialchars($row['doctor_name']) ?: 'N/A';
                            $created_date = htmlspecialchars($row['created_date']);
                            $statusClasses = [
                                'pending' => 'text-warning bg-warning-subtle',
                                'assigned' => 'text-primary bg-primary-subtle ',
                                'approved' => 'text-success bg-success-subtle ',
                                'disapproved' => 'text-danger bg-danger-subtle'
                            ];

                            $defaultClass = 'bg-secondary text-white';

                            $statusClass = isset($statusClasses[strtolower($status)]) ? $statusClasses[strtolower($status)] : $defaultClass;

                            echo "<tr>";
                            echo "<td>{$patient_name}</td>";
                            echo "<td class='hide-on-small'>{$request_date_time}</td>";
                            echo "<td class='hide-on-small'>{$description}</td>";
                            echo "<td class='hide-on-small'><span class='px-2 py-1 rounded-1 {$statusClass}'>{$status}</span></td>";
                            echo "<td class='hide-on-small'>";

                            if ($status == 'pending') {
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a> ";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                            } elseif ($status == 'approved') {
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a>";
                            } elseif ($status == 'assigned') {
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a>";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";

                            } else {
                                echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                            }
                            echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                            echo "<a href='chat.php?certificate_id={$id}'><i class='fa-solid fa-comment'></i></a>";
                            echo "</td>";

                            echo "<td class='hide-on-small'>{$doctor_name}</td>";
                            echo "<td class='hide-on-small'>{$created_date}</td>";
                            echo "<td class='show-on-small'><a href='chat.php?certificate_id={$id}'><i class='fa-solid fa-comment'></i></a> <button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";

                            echo "</tr>";

                            echo "
            <div id='modal{$id}' class='modal'>
                <div class='modal-content'>
                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                    <h2>Certificate Details</h2>
                    <p><strong>Patient Name:</strong> {$patient_name}</p>
                    <p><strong>Description:</strong> {$description}</p>
                    <p><strong>Assigned Doctor:</strong> {$doctor_name}</p>
                    <p><strong>Date:</strong> {$request_date_time}</p>
                    <p><strong>Status:</strong> <span class='px-2 py-1 rounded-1 {$statusClass}'> {$status} </span> </p>
                    <p><strong>Actions:</strong>
                    ";

                            if ($status == 'pending') {
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a> ";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                // echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                            } elseif ($status == 'approved') {
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                // echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a>";
                            } elseif ($status == 'assigned') {
                                echo "<a href='disapprove_certificate.php?id={$id}'><i class='fa-solid fa-ban'></i></a>";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                // echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                // echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";

                            } else {
                                // echo "<a href='medical_certificate_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                // echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                            }

                            echo "<a href='medical_certificate_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a> </p>
                    <a href='medical_certificate_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
                </div>
            </div>
            ";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No medical certificate requests found.</td></tr>";
                    }
                    ?>
                </tbody>

            </table>
    </section>
    <?php include("include/footer.php"); ?>

    <?php
    $conn->close();
    ?>
</div>
</div>

<script src="assets/script.js"></script>
</body>

</html>