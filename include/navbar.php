<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Doctor</title>
    <link rel="stylesheet" href="assets/styles.css">
    <link rel="stylesheet" href="frontend/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<nav class="home-nav">
    <div  class="nav-brand">
        <a href="index.php"><img class="site-logo" src="assets/img/Doctor-Help-Logo.webp" alt="Logo"></a>
    </div>
    <!-- Conditional Navigation -->
    <?php if (!isset($_SESSION['username'])): ?>
        <!-- Not Logged In -->
        <button class="nav-toggle" aria-label="Toggle navigation">&#9776;</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="frontend/medical_certificate_request.php">Medical Certificate</a>
            <a href="frontend/prescription_request.php">Prescription</a>
            <a href="frontend/telehealth_request.php">Telehealth</a>
            <a href="frontend/referral_request.php">Referral</a>
            <!-- <a href="#">About Us</a> -->
            <!-- <a href="#">Contact Us</a> -->
            <a href="login.php">Login</a>
            <a href="register.php">Sign up</a>
            <a href="contact.php">Contact Us</a>
        </div>
    <?php else: ?>
        <!-- Logged In -->
        
        <button class="nav-toggle" aria-label="Toggle navigation">&#9776;</button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <?php
            if ($_SESSION['role'] === 'admin') {
                $dashboardLink = 'admin/index.php';
            } elseif ($_SESSION['role'] === 'doctor') {
                $dashboardLink = 'doctor/index.php';
            } elseif ($_SESSION['role'] === 'patient') {
                $dashboardLink = 'patient/index.php';
            } else {
                $dashboardLink = 'index.php'; // Default or fallback link
            }
            ?>
            <a href="frontend/medical_certificate_request.php">Medical Certificate</a>
            <a href="frontend/prescription_request.php">Prescription</a>
            <a href="frontend/telehealth_request.php">Telehealth</a>
            <a href="frontend/referral_request.php">Referral</a>
            <a href="<?php echo $dashboardLink; ?>">Dashboard</a>
            <a href="logout.php">Logout</a>
            <a href="contact.php">Contact Us</a>
        </div>
    <?php endif; ?>
</nav>





<script>
  document.addEventListener('DOMContentLoaded', function() {
    var navToggle = document.querySelector('.nav-toggle');
    var navLinks = document.querySelector('.nav-links');

    if (navToggle && navLinks) {
      navToggle.addEventListener('click', function() {
        navLinks.classList.toggle('show');
      });
    }
  });
</script>