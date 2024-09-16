<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}


$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>


<?php
include("include/sidebar.php")
    ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Profile</span>
            </div>
            <?php
            include("include/navbar.php")
                ?>

        </div>

        <div class="contact-form-container table-responsive">

            <h2 class="page-header">Contact Messages</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Received At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['message']}</td>
                                <td>{$row['created_at']}</td>
                                <td><a href='include/delete_contact_message.php?id={$row['id']}' class='delete-link'><i class='fa-solid fa-trash-can' style=' color:#0099fb; transition: color 0.3s;' onmouseover='this.style.color=`red`' onmouseout='this.style.color=`#0099fb`'></i></a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No messages found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
    <?php
    include("include/footer.php");
    $conn->close();
    ?>
</div>
</div>

<script src="assets/script.js"></script>
</body>

</html>