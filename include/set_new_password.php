<?php
session_start();
isset($_GET['token']) ? $token = $_GET['token'] : header('Location: ../login.php');

include 'config.php';

if (isset($_POST['reset_password'])) {
    $token = $_POST['token'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match');</script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Verify token and update password
    $stmt = $conn->prepare("SELECT * FROM patient WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update password and reset the token
        $stmt = $conn->prepare("UPDATE patient SET password = ?, hpassword = ?, token = NULL, token_expire = NULL WHERE token = ?");
        $stmt->bind_param("sss", $hashedPassword, $newPassword, $token);
        $stmt->execute();

        echo "<script>
                alert('Your password has been reset successfully');
                setTimeout(function() {
                   
                    window.location.href = 'http://localhost/dh/login.php';
                }, 100); // Redirect after 100 milliseconds
              </script>";
        exit();
    } else {
        echo "<script>alert('Invalid or expired token');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set New Password - DoctorHelp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .error-message {
            color: #fa6d66;
            background-color: #ffe6e6;
            border: 1px solid #fa6d66;
            padding: 10px;
            margin: 10px 0 20px;
            border-radius: 5px;
            font-size: 14px;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* width: 70%; */
            margin: auto;
        }

        .login-page {
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }

        .login-page img {
            margin-bottom: 20px;
            width: 150px;
        }

        .login-page h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333333;
            font-weight: normal;
        }

        .login-page label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #555555;
        }

        .login-page input[type="text"],
        .login-page input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        .login-page input[type="text"].error,
        .login-page input[type="password"].error {
            border-color: #fa6d66;
            background-color: #ffe6e6;
        }

        .login-page .button-class {
            width: 100%;
            padding: 12px;
            color: white;
            background-image: linear-gradient(270deg,
                    #c3263f 0%,
                    #c3263f 25%,
                    #9c2941 75%);
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-page h5 {
            margin-top: 20px;
            font-size: 14px;
            color: #777777;
        }

        .login-page h5 a {
            color: #fa6d66;
            text-decoration: none;
        }

        .login-page h5 a:hover {
            text-decoration: underline;
        }

        .login-page .reset-password {
            margin-bottom: 20px;
            text-align: right;
            font-size: 14px;
        }

        .login-page .reset-password a {
            color: #fa6d66;
            text-decoration: none;
        }

        .login-page .reset-password a:hover {
            text-decoration: underline;
        }

        .login-page .footer-text {
            font-size: 12px;
            color: #777777;
            margin-top: 30px;
        }

        .login-page .footer-text a {
            color: #c3263f;
            text-decoration: none;
        }

        .login-page .footer-text a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>
    <div class="login-container">
        <div class="login-page">
            <a href="https://www.doctorhelp.com.au/"> <img src="..\assets\img\doctorhelplogo.png" alt="Logo"></a>
            <form action="#" method="POST">
                <h2>Set New Password</h2>
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter New Password" required>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                <button type="submit" name="reset_password" class="button-class">Set Password</button>
            </form>
        </div>
    </div>
</body>
</html>