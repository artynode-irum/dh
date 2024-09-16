<?php
session_start();
include 'include/config.php';

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    $sql = "INSERT INTO contact_messages (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        $alertMessage = "Message sent successfully!";
    } else {
        $alertMessage = "Error: " . $sql . "\\n" . $conn->error;
    }
}
$conn->close();
?>