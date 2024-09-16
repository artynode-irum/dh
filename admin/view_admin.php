<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $admin_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
    } else {
        echo "No admin found.";
        exit();
    }
} else {
    echo "Invalid request.";
    exit();
}
?>

<?php include("include/sidebar.php"); ?>
<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Admin Details</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>
        <!-- <div class="content"> -->
            <div>
                <h2  class="page-header">Admin Details</h2>
         
                <div class="patient-details">

                    <span>
                        <label for="profile_picture">Profile Picture</label>
                        <!-- <input type="file" id="profile_picture" name="profile_picture"> -->
                        <img src="assets/img/<?php echo htmlspecialchars($admin['profile_picture']); ?>"
                            alt="Profile Picture" width="100">
                    </span>

                    <label for="username">Username</label>
                    <input type="text" id="username" name="username"
                        value="<?php echo htmlspecialchars($admin['username']); ?>" required readonly>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        value="<?php echo htmlspecialchars($admin['email']); ?>" required readonly>

                    <!-- <label for="new_password">New Password</label> -->
                    <!-- <input type="password" id="new_password" name="new_password"> -->


                    <!-- <button type="submit" name="update_profile">Update Profile</button> -->
                    <!-- </form> -->
                </div>
            </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
</div>
<script src="assets/script.js"></script>
</body>

</html>