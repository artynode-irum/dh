<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>

<body>
    <!-- <div class="login-container"> -->
    <?php 
    include("include/navbar.php")
    ?>
    <div class="login-container">
        <div class="login-page">
            <h2>Login</h2>
            <form action="login_handler.php" method="POST">
                <!-- <label for="email">Email</label> -->
                <!-- <input type="text" id="email" name="email" required> -->
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" name="login" class="button-class">Login</button>
                <h5>Don't have an account? <a href="register.php">Register Now</a></h5>
            </form>
        </div>
    </div>
    <?php
    include('include/footer.php')
        ?>
</body>

</html>