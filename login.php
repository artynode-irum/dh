<?php
session_start();
?>
<title>Login</title>

<?php include("include/navbar.php"); ?>

<div class="content">
    <div class="request-form">
        <h2 class="text-center">Login</h2>
        <form action="login_handler.php" method="POST">
            <div class="row">
                <div class="col-12">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="text" id="username" placeholder="Enter Email" name="username" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="input-group">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" id="password" placeholder="Enter Password" name="password" required>
                    </div>
                </div>
            </div>

            <small style="float: inline-end; padding: 10px 0;">
                <a href="include/forgot_password.php" style="text-decoration: none;">Forgot Password? </a>
            </small>

            <button type="submit" name="login" class="button-class">Login</button>

            <div class="mt-3 mb-3">
                Don't have an account? <a href="register.php" style="text-decoration: none;">Register Now!</a>
            </div>

            <div class="footer-text">
                <small> © 2024 MA Health House Pty Ltd <a href="terms_conditions.php">Terms</a> | <a
                        href="privacy.php">Privacy Policy</a></small>
            </div>
        </form>
    </div>
    <?php include('include/footer.php'); ?>
</div>

<?php
// Display alert if login error is set
if (isset($_SESSION['login_error'])) {
    echo '<script>alert("' . $_SESSION['login_error'] . '");</script>';
    unset($_SESSION['login_error']); // Clear the error after displaying
}
?>
</body>

</html>