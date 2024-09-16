<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'patient') {
    header("Location: index.php");
    exit();
}

// Check for success or error messages
$success = isset($_GET['success']) ? $_GET['success'] : null;

// Fetch patient's referrals
$username = $_SESSION['username'];
$query = "SELECT referrals.id, doctor.username as doctor_name, referrals.description, referrals.request_date_time, referrals.status, referrals.prescription 
          FROM referrals 
          JOIN doctor ON referrals.doctor_id = doctor.id 
          WHERE referrals.patient_id = (SELECT id FROM patient WHERE username = ?)
          ORDER BY referrals.created_date DESC";
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
                <span>Referrals</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <div class="d-flex justify-content-between align-items-center py-2">
            <h2 class="page-header">All Referrals</h2>
            <a href="..\frontend\referral_request.php" class="button-class" style="text-decoration: none;">  <small> New Request </small> <i class="fa-solid fa-file-circle-plus"></i></a>
        </div>
        <div class="table-responsive">
            <!-- <button id="requestButton" class="button-class">Request Referrals</button> <br><br> -->
            <table>
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th class='hide-on-small'>Description</th>
                        <th class='hide-on-small'>Date</th>
                        <th class='hide-on-small'>Prescription</th>
                        <th class='hide-on-small'>Action</th> <!-- Added column for View button -->
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



                            // Skip disapproved appointments
                            if ($status != 'disapproved') {
                                echo "<tr>";
                                echo "<td>{$doctor_name}</td>";
                                echo "<td class='hide-on-small'>{$description}</td>";
                                echo "<td class='hide-on-small'>{$request_date_time}</td>";
                                // Display prescription if available
                                if ($prescription) {
                                    echo "<td class='hide-on-small'>{$prescription}</td>";
                                    echo "<td class='hide-on-small'><a href='referrals_view.php?id={$id}' style='color: white; text-decoration: none;' class='btn text-primary bg-primary-subtle'>View</a></td>"; // View button
                                } else {
                                    echo "<td class='hide-on-small'><span class='btn text-secondary bg-secondary-subtle'>Pending</span></td>";
                                    echo "<td class='hide-on-small'><span class='btn text-secondary bg-secondary-subtle'>Pending</span></td>";
                                }
                                echo "<td class=' show-on-small'><button class='button-class' onclick='openModal(\"modal{$id}\")'>Details</button></td>";

                                echo "</tr>";
                            } else {
                                echo "<tr>";
                                echo "<td> - </td>";
                                echo "<td class='hide-on-small'>{$description}</td>";
                                echo "<td class='hide-on-small'>{$request_date_time}</td>";
                                echo "<td class='hide-on-small'> -</td>";
                                // echo "<td>{$status}</td>";
                                echo "<td><button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";
                                echo "</tr>";
                            }
                            echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Referral Details</h2>
                                    <p><strong>Doctor:</strong> {$doctor_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>

                                    <p><strong>Status:</strong> <span class='px-2 py-1 rounded-1 {$statusClass}'> {$status} </span> </p>
                                    <a href='referrals_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
                                    </div>
                                    </div>
                                    ";
                        }
                    } else {
                        // Display a message when there are no rows
                        echo "<tr><td colspan='6'>No data available</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<?php if ($success = 1): ?>
    <script type="text/javascript">
        function referrals() {
            alert("Referrals request submitted successfully!");
        }
    </script>
<?php endif; ?>

<script src="assets/script.js"></script>
</body>

</html>