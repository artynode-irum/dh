<?php
session_start();
include 'include/config.php';

// Check if the login form was submitted
if (isset($_POST['login'])) {
    $usernameOrEmail = $_POST['username'];
    $password = $_POST['password'];

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM patient WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['email_verify'] == 1 && (($password == $row['password']) || $password == $row['hpassword'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'patient';
            header("Location: patient/index.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Wrong credentials';
            header('Location: login.php');
            exit();
        }
    }

    // Check for doctor
    $stmt = $conn->prepare("SELECT * FROM doctor WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['password'] || $password == $row['hpassword']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'doctor';
            header("Location: doctor/index.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Wrong credentials';
            header('Location: login.php');
            exit();
        }
    }

    // Check for admin
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password == $row['password'] || $password == $row['hpassword']) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'admin';
            header("Location: admin/index.php");
            exit();
        } else {
            $_SESSION['login_error'] = 'Wrong credentials';
            header('Location: login.php');
            exit();
        }
    }

    // If no user was found
    $_SESSION['login_error'] = 'Wrong credentials';
    header('Location: login.php');
    exit();
}
?>
