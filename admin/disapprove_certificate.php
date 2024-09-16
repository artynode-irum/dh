<?php
session_start();
include '../include/config.php';
// include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $medical_certificate_id = $_GET['id'];
    ?>

    <!-- <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Disapprove Certificate</title>
        <link rel="stylesheet" href="assets/style.css">
    </head>

    <body>
        <form method="post" action="include/medical_certificate_disapprove.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($medical_certificate_id); ?>">
            <label for="reason">Reason for Disapproval:</label><br>
            <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>
            <input type="submit" value="Submit">
        </form>
    </body>

    </html> -->

    <?php include("include/sidebar.php"); ?>
    <div class="second-section">
        <section>
            <div class="navbar">
                <div class="page-title">
                    <span>Disapprove Certificate</span>
                </div>
                <?php include("include/navbar.php"); ?>
            </div>
            <div>
                <h2 class="page-header">Reason for Medical Certificate Rejection <br>
                    ID: <?php echo $medical_certificate_id; ?></h2> <br>



                <form method="post" action="include/medical_certificate_disapprove.php">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($medical_certificate_id); ?>">
                    <!-- <label for="reason">Reason for Disapproval:</label><br> -->
                    <textarea id="reason" name="reason" rows="4" cols="50" required></textarea><br><br>
                    <input type="submit" class="button-class" value="Submit">
                </form>
                <?php
} else {
    echo "No certificate ID provided.";
}
?>
        </div>
    </section>
    <?php include("include/footer.php"); ?>
</div>
<script src="assets/script.js"></script>
</body>

</html>