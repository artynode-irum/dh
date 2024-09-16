<?php
session_start();
include '../../include/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: ../index.php");
    exit();
}

$username = $_SESSION['username'];
$new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
$profile_picture = isset($_FILES['profile_picture']) ? $_FILES['profile_picture'] : null;
$signature = isset($_FILES['signature']) ? $_FILES['signature'] : null;

// Process password update
if (!empty($new_password)) {
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    $sql = "UPDATE doctor SET password=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $hashed_password, $username);
    if (!$stmt->execute()) {
        $_SESSION['message'] = "Error updating password: " . $stmt->error;
        $stmt->close();
        header("Location: ../profile.php");
        exit();
    }
    $stmt->close();
}

// Function to handle file uploads
function handleFileUpload($file, $target_dir) {
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            $_SESSION['message'] = "File is not an image.";
            return false;
        }

        // Check file size (limit to 5MB)
        if ($file["size"] > 5000000) {
            $_SESSION['message'] = "Sorry, your file is too large.";
            return false;
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            $_SESSION['message'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            return false;
        }

        // Check if file already exists and append timestamp if it does
        if (file_exists($target_file)) {
            $target_file = $target_dir . time() . "_" . basename($file["name"]);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($target_file);
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            return false;
        }
    } else {
        $_SESSION['message'] = "No file uploaded or there was an error with the upload.";
        return false;
    }
}

// Process profile picture update
if ($profile_picture) {
    $profile_picture_name = handleFileUpload($profile_picture, "../assets/img/");
    if ($profile_picture_name) {
        $stmt = $conn->prepare("UPDATE doctor SET profile_picture=? WHERE username=?");
        $stmt->bind_param("ss", $profile_picture_name, $username);
        if (!$stmt->execute()) {
            $_SESSION['message'] = "Error updating profile picture: " . $stmt->error;
        }
        $stmt->close();
        $_SESSION['message'] = "Profile picture updated successfully.";
    }
}

// Process signature image update
if ($signature) {
    $signature_name = handleFileUpload($signature, "../assets/img/");
    if ($signature_name) {
        $stmt = $conn->prepare("UPDATE doctor SET signature=? WHERE username=?");
        $stmt->bind_param("ss", $signature_name, $username);
        if (!$stmt->execute()) {
            $_SESSION['message'] = "Error updating signature image: " . $stmt->error;
        }
        $stmt->close();
        $_SESSION['message'] = "Signature image updated successfully.";
    }
}

header("Location: ../profile.php");
exit();
