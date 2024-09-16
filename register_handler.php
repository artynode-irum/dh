<?php
session_start();
include 'include/config.php';

require 'vendor/autoload.php';
use GuzzleHttp\Client;

if (isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
   
    $token = bin2hex(random_bytes(16));

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // $hashed_ password = $password;

    $user_check = "SELECT * FROM patient WHERE email='$email'";
    $result = $conn->query($user_check);

    if ($result->num_rows > 0) {
        echo "Username already exists.";
        exit();
    }

    $sql = "INSERT INTO patient (email, email_verify, token, password, hpassword, username) 
    VALUES ('$email', '0', '$token', '$hashed_password', '$password', '$email')";
    // $sql = "INSERT INTO patient (username, password) VALUES ('$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        require 'emails/register_email.php';
    } else if ($conn->query($sql) === FALSE) {
        echo "User already exists.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>