<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

// Check for success or error messages
$success = isset($_GET['success']) ? $_GET['success'] : null;

// Fetch patient's certificate requests
$username = $_SESSION['username'];
$query = "SELECT medical_certificate.id, doctor.username as doctor_name, doctor.email as doctor_email, medical_certificate.description, medical_certificate.request_date_time, medical_certificate.status, medical_certificate.payment, medical_certificate.prescription, medical_certificate.disapproval_reason
          FROM medical_certificate 
          LEFT JOIN doctor ON medical_certificate.doctor_id = doctor.id 
          LEFT JOIN patient ON medical_certificate.patient_id = patient.id 
          WHERE medical_certificate.patient_id = (SELECT id FROM patient WHERE username = ?)
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
                <span>Medical Certificate Requests</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
            <h2 class="page-header">All Requests</h2>
            <a href="../frontend/medical_certificate_request.php" class="button-class"
                style="text-decoration: none;"><small> New Request </small> <i class="fa-solid fa-file-circle-plus"></i></a>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th class="hide-on-small">Description</th>
                        <th class="hide-on-small">Date</th>
                        <th class="hide-on-small">Certificate Type</th>
                        <th class="hide-on-small">Status</th>
                        <th class="show-on-small">Details</th>
                        <th class="hide-on-small">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row['id']);
                            $doctor_name = htmlspecialchars($row['doctor_name']);
                            $description = htmlspecialchars($row['description']);
                            $request_date_time = htmlspecialchars($row['request_date_time']);
                            $status = htmlspecialchars($row['status']);
                            $payment = htmlspecialchars($row['payment']);
                            $email = htmlspecialchars($row['doctor_email']);
                            $disapproval_reason = htmlspecialchars($row['disapproval_reason']) ?: '';
                            $statusClasses = [
                                'pending' => 'text-warning bg-warning-subtle',
                                'assigned' => 'text-primary bg-primary-subtle ',
                                'approved' => 'text-success bg-success-subtle ',
                                'disapproved' => 'text-danger bg-danger-subtle'
                            ];

                            $defaultClass = 'bg-secondary text-white';

                            $statusClass = isset($statusClasses[strtolower($status)]) ? $statusClasses[strtolower($status)] : $defaultClass;

                            echo "<tr>";
                            echo "<td>" . ($doctor_name ? $doctor_name : ' - ') . "</td>";
                            echo "<td class='hide-on-small'>{$description}</td>";
                            echo "<td class='hide-on-small'>{$request_date_time}</td>";
                            echo "<td class='hide-on-small'>" .
                                ($payment == 10 ? "Regular Certificate - \$10" :
                                    ($payment == 20 ? "Priority Certificate - \$20" : "Not Paid")) .
                                "</td>";

                            // Display status and any disapproval reason if applicable
                            echo "<td  class='hide-on-small'><span class='px-2 py-1 rounded-1 {$statusClass}'>";
                            if ($status === 'disapproved' && $disapproval_reason) {
                                echo "{$status}</span> <i class='fa-solid fa-circle-exclamation' style='cursor: pointer; color: #ff0000;' onclick='showReason(\"{$disapproval_reason}\")' title='Click to see reason'></i>";
                            } else {
                                echo "{$status}</span>";
                            }
                            echo "</td>";

                            // Show details button for mobile view
                            if ($status === 'approved' && !empty($doctor_name) ) {
                                echo "<td class='hide-on-small'>  <a href='chat.php?certificate_id={$id}&doctor_name={$doctor_name}&email={$email}'><i class='fa-solid fa-comment'></i></a>  <a href='medical_certificate_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='btn text-primary bg-primary-subtle  hide-on-small '>View</a> </td>";
                            }
                            elseif ($status === 'approved' && empty($doctor_name) ) {
                                echo "<td class='hide-on-small'>  <a href='chat.php?certificate_id={$id}&doctor_name={$doctor_name}&email={$email}'><i class='fa-solid fa-comment'></i></a>  <a href='medical_certificate_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='btn text-primary bg-primary-subtle  hide-on-small '>View</a> </td>";
                            }  
                            elseif ($status === "assigned" ) {
                                
                                echo "<td class='hide-on-small'>  <a href='chat.php?certificate_id={$id}&doctor_name={$doctor_name}&email={$email}'><i class='fa-solid fa-comment'></i></a>  </td>";
                            } 
                            elseif ($status === "disapproved" && empty($doctor_name) ){
                                
                                echo "<td class='hide-on-small'>  <a href='chat.php?certificate_id={$id}&doctor_name={$doctor_name}&email={$email}'><i class='fa-solid fa-comment'></i></a>  - </td>";
                            }                        
                         
                            else {
                                
                                echo "<td class='hide-on-small'> <a href='chat.php?certificate_id={$id}&doctor_name={$doctor_name}&email={$email}'><i class='fa-solid fa-comment'></i></a> </td>";

                            }




                            echo "<td class='show-on-small'><a href='chat.php?certificate_id={$id}'><i class='fa-solid fa-comment '></i></a> <button class='button-class' onclick='openModal(\"modal{$id}\")'>Details</button></td>";
                            echo "</tr>";

                            // Modal for detailed view
                            echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Certificate Details</h2>
                                    <p><strong>Doctor:</strong> {$doctor_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>
                                    <p><strong>Certificate Type:</strong> " .
                                ($payment == 10 ? "Regular Certificate - \$10" :
                                    ($payment == 20 ? "Priority Certificate - \$20" : "Not Paid")) .
                                "</p>
                                    <p><strong>Status:</strong> <span class='px-2 py-1 rounded-1 {$statusClass}'>{$status}</span></p>
                                    " . ($status === 'disapproved' && $disapproval_reason ? "<p><strong>Reason:</strong> {$disapproval_reason}</p>" : "") . "
                                    " . ($status === 'approved' ? "<a href='medical_certificate_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>" : "") . "
                                </div>
                            </div>
                        ";

                        }
                    } else {
                        echo "<tr><td colspan='6'>No requests found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
<?php if ($success == 1): ?>
    <script type="text/javascript">
        alert("Medical Certificate request submitted successfully!");
    </script>
<?php endif; ?>

<script type="text/javascript">
    function showReason(reason) {
        alert("Disapproval Reason: " + reason);
    }
</script>

<script src="assets/script.js"></script>
</body>

</html>