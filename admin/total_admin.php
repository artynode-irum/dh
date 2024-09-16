<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Fetch all doctors
$db = "SELECT * FROM admin";
$result_db = $conn->query($db);
?>

<?php
include("include/sidebar.php")
    ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Admins</span>
            </div>
            <?php
            include("include/navbar.php")
                ?>

        </div>
        <div class="table-responsive">
            <h2 class="page-header">All Admins</h2>
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Profile Picture</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result_db->num_rows > 0) {
                        while ($row = $result_db->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td><img src='assets/img/" . htmlspecialchars($row['profile_picture']) . "' alt='Profile Picture' width='50'></td>";
                            echo "<td>
                                <a href='view_admin.php?id=" . htmlspecialchars($row['id']) . "' class='btn text-primary bg-primary-subtle'>View</a>
                                
                              </td>";
                            echo "</tr>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No Doctor found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <!-- <a href="../includes/logout.php">Logout</a> -->
        </div>
    </section>
    <?php
    include("include/footer.php")
        ?>
</div>
</div>
</div>

<script src="assets/script.js"></script>
</body>

</html>
</body>

</html>