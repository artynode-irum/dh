<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

// Check for success or error messages
$success = isset($_GET['success']) ? $_GET['success'] : null;

// Fetch patient's prescription
$username = $_SESSION['username'];
$query = "SELECT prescription.id, doctor.username as doctor_name, prescription.description, prescription.request_date_time, prescription.status, prescription.prescribe 
          FROM prescription 
          JOIN doctor ON prescription.doctor_id = doctor.id 
          WHERE prescription.patient_id = (SELECT id FROM patient WHERE username = ?)
          ORDER BY prescription.request_date_time DESC"; // Changed to request_date_time for clarity
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
                <span>Prescription</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
            <h2 class="page-header">All Prescriptions</h2>
            <a href="..\frontend\prescription_request.php" class="button-class" style="text-decoration: none;"> <small> New Request </small> <i class="fa-solid fa-file-circle-plus"></i></a>
        </div>
        <div class="table-responsive">

            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th class='hide-on-small'>Description</th>
                        <th class='hide-on-small'>Date</th>
                        <th class='hide-on-small'>Status</th> <!-- Column for Status -->
                        <th class='hide-on-small'>Action</th> <!-- Column for View button -->
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
                            $statusClasses = [
                                'pending' => 'text-warning bg-warning-subtle',
                                'assigned' => 'text-primary bg-primary-subtle ',
                                'approved' => 'text-success bg-success-subtle ',
                                'disapproved' => 'text-danger bg-danger-subtle'
                            ];

                            $defaultClass = 'bg-secondary text-white';

                            $statusClass = isset($statusClasses[strtolower($status)]) ? $statusClasses[strtolower($status)] : $defaultClass;

                            echo "<tr>";
                            echo "<td>{$doctor_name}</td>";
                            echo "<td class='hide-on-small'>{$description}</td>";
                            echo "<td class='hide-on-small'>{$request_date_time}</td>";
                            echo "<td class='hide-on-small'><span class='px-2 py-1 rounded-1 {$statusClass}'>{$status}</span></td>"; // Display status
                    
                            // Determine action based on status
                            if ($status == 'assigned' || $status == 'approved' || $status == 'pending' || $status == 'disapproved') {
                                echo "<td class='hide-on-small'><a href='prescription_view.php?id={$id}' style='color: white; text-decoration: none;' class='btn text-primary bg-primary-subtle'>View</a></td>";
                            } else {
                                echo "<td>-</td>"; // Placeholder for unknown status
                            }
                            echo "<td class='show-on-small'><button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";

                            echo "</tr>";

                            // Modal
                            echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Prescription Details</h2>
                                    <p><strong>Doctor:</strong> {$doctor_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>
                                    
                                    <p><strong>Status:</strong>  <span class='px-2 py-1 rounded-1 {$statusClass}'> {$status} </span> </p>
                                    <a href='prescription_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No data available</td></tr>"; // Adjusted colspan to match table columns
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