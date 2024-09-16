<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT medical_certificate.id, patient.username as patient_name, medical_certificate.description, medical_certificate.name, medical_certificate.request_date_time, medical_certificate.status, medical_certificate.prescription, medical_certificate.email 
          FROM medical_certificate 
          LEFT JOIN patient ON medical_certificate.patient_id = patient.id 
          WHERE medical_certificate.doctor_id = (SELECT id FROM doctor WHERE username = ?)
          ORDER BY medical_certificate.created_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
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
                        <th class="hide-on-small">Description</th>
                        <th class="hide-on-small">Date</th>
                        <th class="hide-on-small">Add Detail</th>
                        <th>Notes</th>
                        <th class="hide-on-small" style="width:100px;">View</th>
                        <th class="show-on-small">Details</th>
                        <th class="hide-on-small">Approve/Disapprove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['id']);
                        $patient_name = htmlspecialchars($row['name']) ?: 'N/A';
                        $description = htmlspecialchars($row['description']);
                        $request_date_time = htmlspecialchars($row['request_date_time']);
                        $status = htmlspecialchars($row['status']);
                        $prescription = htmlspecialchars($row['prescription']);
                        $email = htmlspecialchars($row['email']);

                        echo "<tr>";
                        echo "<td>{$patient_name}</td>";



                        echo "<td class='hide-on-small'>{$description}</td>";
                        echo "<td class='hide-on-small'>{$request_date_time}</td>";

                        echo "<td class='hide-on-small'><a href='medical_description.php?id={$id}'><i class='fa-solid fa-clipboard-list add-detail'></i></a></td>";
                        echo "<td><a href='medical_certificate_notes.php?id={$id}'><i class='fa-solid fa-pen-to-square'></i></a></td> ";
                        echo "<td class='hide-on-small'><a href='chat.php?certificate_id={$id}&patient_name={$patient_name}&email={$email}'><i class='fa-solid fa-comment'></i></a> <a href='medical_certificate_view.php?id={$id}' ><i class='fa-solid fa-eye'></i></a></td>";

                        echo "<td class='show-on-small'><a href='chat.php?certificate_id={$id}'><i class='fa-solid fa-comment'></i></a> &nbsp; <button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";
                        echo "<td class='hide-on-small'>";
                        if ($status == "assigned") {
                            echo " <a href='disapprove_certificate.php?id={$id}&status=approved&email={$email}&patient_name={$patient_name}'><i class='fa-solid fa-ban'></i></a> ";
                            echo "<a href='include/medical_certificate_status.php?id={$id}&status=approved&email={$email}&patient_name={$patient_name}'><i class='fa-regular fa-circle-check'></i></a>";
                        } else if ($status == "disapproved") {
                            echo " <a href='include/medical_certificate_status.php?id={$id}&status=approved&email={$email}&patient_name={$patient_name}'><i class='fa-regular fa-circle-check'></i></a>";
                        } else if ($status == "approved") {
                            echo " <a href='disapprove_certificate.php?id={$id}&patient_name={$patient_name}&email={$email}'><i class='fa-solid fa-ban'></i></a> ";
                        } else {
                            echo "-";
                        }
                        echo "</td>";


                        echo "</tr>";

                        echo "
                        <div id='modal{$id}' class='modal'>
                            <div class='modal-content'>
                                <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                <h2>Certificate Details</h2>
                                <p><strong>Patient Name:</strong> {$patient_name}</p>
                                <p><strong>Description:</strong> {$description}</p>
                                <p><strong>Date:</strong> {$request_date_time}</p>
                                <p><strong>Add Details:</strong> <a href='medical_description.php?id={$id}'><i class='fa-solid fa-clipboard-list add-detail'></i></a> </p>
                                <p><strong>Notes:</strong> <a href='medical_certificate_notes.php?id={$id}'><i class='fa-solid fa-pen-to-square'></i></a> </p>
                                <p><strong>Status:</strong> {$status}</p>
                                <p><strong>Approve/ Disapprove :</strong> 
                                
                                ";

                                
                        if ($status == 'assigned') {
                            echo "
                                    <a href='disapprove_certificate.php?id={$id}&status=approved&email={$email}&patient_name={$patient_name}'><i class='fa-solid fa-ban'></i></a>
                                    <a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a>
                                    ";
                        } else if ($status == 'disapproved') {
                            echo "
                                    <a href='include/medical_certificate_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a>
                                    ";
                        } else if ($status == 'approved') {
                            echo "
                                    <a href='disapprove_certificate.php?id={$id}&patient_name={$patient_name}&email={$email}'><i class='fa-solid fa-ban'></i></a>
                                    ";
                        } else {
                            echo "-";
                        }

                        echo "</p>
                                <a href='medical_certificate_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
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