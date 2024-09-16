<?php
session_start();
include '../include/config.php';

// Check if the user is a doctor
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT referrals.id, 
                 COALESCE(patient.username, 'No Patient') as patient_name, 
                 referrals.name, 
                 referrals.description, 
                 referrals.request_date_time, 
                 referrals.status, 
                 referrals.prescription 
          FROM referrals 
          LEFT JOIN patient ON referrals.patient_id = patient.id 
          WHERE referrals.doctor_id = (SELECT id FROM doctor WHERE username = ?)
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

        <div class="table-responsive">
            <h2 class="page-header">All Referrals</h2>
            <table>
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th class="hide-on-small">Description</th>
                        <th class="hide-on-small">Date</th>
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
                        $prescription = htmlspecialchars($row['prescription']);

                        echo "<tr>";
                        echo "<td>{$patient_name}</td>";
                        echo "<td class='hide-on-small'>{$description}</td>";
                        echo "<td class='hide-on-small'>{$request_date_time}</td>";
                        echo "<td class='hide-on-small'>";
                        echo "<a href='referrals_prescription.php?id={$id}'><i class='fa-solid fa-clipboard-list add-detail'></i></a>";
                        echo "</td>";
                        echo "<td><a href='referrals_notes.php?id={$id}'><i class='fa-solid fa-pen-to-square'></i></a></td>";
                        echo "<td class='hide-on-small'><a href='referrals_view.php?id={$id}' class='btn text-primary bg-primary-subtle'>View</a></td>";
                        echo "<td class='show-on-small'><button class='button-class show-on-small' onclick='openModal(\"modal{$id}\")'>Details</button></td>";

                        echo "</tr>";

                        echo "
                        <div id='modal{$id}' class='modal'>
                            <div class='modal-content'>
                                <span class='close' onclick='closeModal(\"modal{$id}\")'>&times;</span>
                                <h2>Referral Details</h2>
                                <p><strong>Patient Name:</strong> {$patient_name}</p>
                                <p><strong>Description:</strong> {$description}</p>
                                <p><strong>Date:</strong> {$request_date_time}</p>
                                <p><strong>Add Prescription:</strong> <a href='referrals_prescription.php?id={$id}'> <i class='fa-solid fa-clipboard-list add-detail'></i></a> </p>
                                
                                <p><strong>Status:</strong> {$status}</p>
                                <a href='referrals_view.php?id={$id}' style='color: white; text-decoration: none; text-align: center;' class='button-class'>View</a>
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