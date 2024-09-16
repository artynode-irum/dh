<?php
session_start();
include '../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM admin WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}
?>

<?php include("include/sidebar.php"); ?>

<div class="second-section">
    <section>
        <div class="navbar">
            <div class="page-title">
                <span>Profile</span>
            </div>
            <?php include("include/navbar.php"); ?>
        </div>

        <h2 class="page-header">Update Profile</h2>
        <div class="profile-container">
            <form action="include/update_profile.php" method="POST" enctype="multipart/form-data">
                <label for="profile_picture"><strong>Profile Picture</strong></label> <br>
                <span>
                    <?php if (!empty($user['profile_picture'])): ?>
                        <img src="assets/img/<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
                    <?php endif; ?>
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
                </span>

                <label for="username"><strong>Username</strong></label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" required
                    readonly>

                <label for="email"><strong>Email</strong></label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required readonly>

                <label for="new_password"><strong>New Password</strong></label>
                <input type="password" id="new_password" name="new_password">

                <button type="submit" name="update_profile" class="button-class">Update Profile</button>
            </form>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>

<script src="assets/script.js"></script>
</body>

</html>