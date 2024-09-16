<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

// Check for success or error messages
$success = isset($_GET['success']) ? $_GET['success'] : null;

// Fetch patient's appointments
$username = $_SESSION['username'];
$query = "SELECT appointments.id, 
                 doctor.username as doctor_name, 
                 appointments.description, 
                 appointments.request_date_time, 
                 appointments.status, 
                 appointments.video_link, 
                 appointments.prescription 
          FROM appointments 
          LEFT JOIN doctor ON appointments.doctor_id = doctor.id 
          WHERE appointments.patient_id = (SELECT id FROM patient WHERE username = ?)
          ORDER BY appointments.request_date_time DESC";
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
                <span>Telehealth</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center my-2">
            <h2 class="page-header">All Appointments</h2>
            <a href="../frontend/telehealth_request.php" id="requestButton" class="button-class" style="text-decoration: none;"> <small> New Request </small> <i class="fa-solid fa-file-circle-plus"></i></a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th class='hide-on-small'>Description</th>
                        <th class='hide-on-small'>Date</th>
                        <th class='hide-on-small'>Video Link</th>
                        <th class='hide-on-small'>Action</th>
                        <th class="show-on-small">Details</th>
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
                            $video_link = htmlspecialchars($row['video_link']);
                            $prescription = htmlspecialchars($row['prescription']);
                        
                            // Define status classes
                            $statusClasses = [
                                'pending' => 'text-warning bg-warning-subtle',
                                'assigned' => 'text-primary bg-primary-subtle ',
                                'approved' => 'text-success bg-success-subtle ',
                                'disapproved' => 'text-danger bg-danger-subtle'
                            ];
                            $defaultClass = 'bg-secondary text-white';
                        
                            // Determine the class for the current status
                            $statusClass = isset($statusClasses[strtolower($status)]) ? $statusClasses[strtolower($status)] : $defaultClass;
                        
                            echo "<tr>";
                            echo "<td>" . ($status !== 'disapproved' && !empty($doctor_name) ? $doctor_name : '-') . "</td>";
                            echo "<td class='hide-on-small'>{$description}</td>";
                            echo "<td class='hide-on-small'>{$request_date_time}</td>";
                        
                            if ($status == 'assigned') {
                                echo "<td class='hide-on-small'><a href='{$video_link}' target='_blank'>Join Session</a></td>";
                            } elseif ($status == 'disapproved') {
                                echo "<td class='hide-on-small'>-</td>";
                            } else {
                                echo "<td class='hide-on-small'>-</td>";
                            }
                        
                            // Add status class to status cell
                            echo "<td class='hide-on-small' style='text-transform:capitalize;'> <span class='px-2 py-1 rounded-1 {$statusClass}'>";
                            
                            if ($status == 'assigned') {
                                echo !$prescription ? $status : "<a href='telehealth_view.php?id={$id}' style='text-decoration:none;'>View</a>";
                            }
                            elseif ($status == 'approved') {
                                echo !$prescription ? $status : "<a href='telehealth_view.php?id={$id}' style='text-decoration:none;'>View</a>";
                            } 
                            elseif ($status == 'pending') {
                                echo $status;
                            }
                            elseif ($status == 'disapproved') {
                                echo $status;
                            }
                            else {
                                echo "Unknown";
                            }

                            
                            // if ($status == 'assigned' || $status == 'approved') {
                            //     echo !$prescription ? $status : "<a href='telehealth_view.php?id={$id}' style='text-decoration:none;'>View</a>";
                            // } elseif ($status == 'pending' || $status == 'disapproved') {
                            //     echo $status;
                            // } else {
                            //     echo "Unknown";
                            // }
                            echo "</span></td>";
                        
                            echo "<td class='show-on-small'><button class='button-class' onclick='openModal(\"modal{$id}\")'>Details</button></td>";
                            echo "</tr>";
                        
                            echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Telehealth Details</h2>
                                    <p><strong>Doctor:</strong> {$doctor_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>
                                    <p><strong>Video Link:</strong> <a href='{$video_link}'>Join Session</a></p>
                                    <p><strong>Status:</strong>  <span class='px-2 py-1 rounded-1 {$statusClass}'>{$status}</span></p>
                                    <a href='telehealth_view.php?id={$id}' class='button-class'>" . ($prescription ? "View" : "Please wait") . "</a>
                                </div>
                            </div>
                            ";
                        }
                        
                    } else {
                        echo "<tr><td colspan='6'>No data available</td></tr>";
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
