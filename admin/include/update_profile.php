<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$profile_picture = isset($_FILES['profile_picture']) ? $_FILES['profile_picture'] : null;

// Process password update
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE admin SET password=?, hpassword=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $new_password, $hashed_password, $username);
    if (!$stmt->execute()) {
        $_SESSION['message'] = "Error updating password: " . $stmt->error;
        $stmt->close();
        header("Location: ../profile.php");
        exit();
    }
    $stmt->close();
}

// Process profile picture update
if ($profile_picture && $profile_picture['error'] === UPLOAD_ERR_OK) {
    $target_dir = "../assets/img/";
    $target_file = $target_dir . basename($profile_picture["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if the file is an image
    $check = getimagesize($profile_picture["tmp_name"]);
    if ($check === false) {
        $_SESSION['message'] = "File is not an image.";
        header("Location: ../profile.php");
        exit();
    }

    // Check file size (limit to 5MB)
    if ($profile_picture["size"] > 5000000) {
        $_SESSION['message'] = "Sorry, your file is too large.";
        header("Location: ../profile.php");
        exit();
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        header("Location: ../profile.php");
        exit();
    }

    // Check if file already exists and append timestamp if it does
    if (file_exists($target_file)) {
        $target_file = $target_dir . time() . "_" . basename($profile_picture["name"]);
    }

    // Move the uploaded file to the target directory
    if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
        $profile_picture_name = basename($target_file);

        // Update the database with the new profile picture name
        $stmt = $conn->prepare("UPDATE admin SET profile_picture=? WHERE username=?");
        $stmt->bind_param("ss", $profile_picture_name, $username);
        if (!$stmt->execute()) {
            $_SESSION['message'] = "Error updating profile picture: " . $stmt->error;
            $stmt->close();
            header("Location: ../profile.php");
            exit();
        }
        $stmt->close();

        $_SESSION['message'] = "Profile picture updated successfully.";
    } else {
        $_SESSION['message'] = "Sorry, there was an error uploading your file.";
    }

    header("Location: ../profile.php");
    exit();
} else {
    $_SESSION['message'] = "No file uploaded or there was an error with the upload.";
    header("Location: ../profile.php");
    exit();
}
