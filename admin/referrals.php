<?php
session_start();
include '../include/config.php';

// Check if the user is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Query referrals with ordering by request_date_time in descending order
$query = "SELECT referrals.id, 
                 COALESCE(patient.username, 'No Patient') as patient_name, 
                 referrals.description, 
                 referrals.name, 
                 referrals.status, 
                 referrals.request_date_time, 
                 referrals.created_date, 
                 referrals.prescription, 
                 referrals.name, 
                 doctor.username as doctor_name 
          FROM referrals 
          LEFT JOIN patient ON referrals.patient_id = patient.id 
          LEFT JOIN doctor ON referrals.doctor_id = doctor.id 
          ORDER BY referrals.created_date DESC";
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
                <span>Referrals</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <div class="table-responsive">
            <h2 class="page-header">All Referrals</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th class='hide-on-small'>Last Updated</th>
                        <th class='hide-on-small'>Description</th>
                        <th class='hide-on-small'>Status</th>
                        <th class='hide-on-small' style='width:100px'>Action</th>
                        <th class='hide-on-small'>Assigned Doctor</th>
                        <th class='hide-on-small'>Created At</th>
                        <th class="show-on-small">Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id = htmlspecialchars($row['id']);
                            $patient_name = htmlspecialchars($row['name']);
                            $request_date_time = htmlspecialchars($row['request_date_time']);
                            $description = htmlspecialchars($row['description']);
                            $status = htmlspecialchars($row['status']);
                            $doctor_name = htmlspecialchars($row['doctor_name']);
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
                                echo "<a href='include/referrals_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a> ";
                                echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                            } elseif ($status == 'approved') {
                                echo "<a href='referrals_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a>";
                            } elseif ($status == 'assigned') {
                                echo "<a href='referrals_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a>";
                            } else {
                                echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                echo "<a href='include/referrals_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                            }

                            echo "</td>";
                            echo "<td class='hide-on-small'>{$doctor_name}</td>";
                            echo "<td class='hide-on-small'>{$created_date}</td>";
                            echo "<td class='show-on-small'><button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";

                            echo "</tr>";

                            echo "
                            <div id='modal{$id}' class='modal'>
                                <div class='modal-content'>
                                    <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                    <h2>Referral Details</h2>
                                    <p><strong>Patient Name:</strong> {$patient_name}</p>
                                    <p><strong>Description:</strong> {$description}</p>
                                    <p><strong>Assigned Doctor:</strong> {$doctor_name}</p>
                                    <p><strong>Date:</strong> {$request_date_time}</p>
                                    <p><strong>Status:</strong> <span class='px-2 py-1 rounded-1 {$statusClass}'>{$status}</span></p>
                                    
                                    <p><strong>Actions:</strong> ";
                                    if ($status == 'pending') {
                                        echo "<a href='include/referrals_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                        echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a> ";
                                        // echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                    } elseif ($status == 'approved') {
                                        echo "<a href='referrals_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a> ";
                                        // echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                        echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a> ";
                                    } elseif ($status == 'assigned') {
                                        echo "<a href='referrals_assign_doctor.php?id={$id}'><i class='fa-solid fa-user-doctor'></i></a>";
                                        // echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                        echo "<a href='include/referrals_status.php?id={$id}&status=disapproved'><i class='fa-solid fa-ban'></i></a> ";
                                    } else {
                                        // echo "<a href='referrals_view.php?id={$id}'><i class='fa-solid fa-eye'></i></a>";
                                        echo "<a href='include/referrals_status.php?id={$id}&status=approved'><i class='fa-regular fa-circle-check'></i></a> ";
                                    }
                                    echo "</p> 

                                    <a href='referrals_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
                                </div>
                            </div>
                            ";
                        }
                    } else {
                        echo "<tr><td colspan='8'>No referrals requests found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php include("include/footer.php"); ?>

    <?php
    $conn->close();
    ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>