<?php
require 'vendor/autoload.php';

use Zxing\QrReader;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error code " . $file['error']);
    }

    // Check if file is an image
    $fileType = mime_content_type($file['tmp_name']);
    if (strpos($fileType, 'image/') !== 0) {
        die("Uploaded file is not an image.");
    }

    // Move the uploaded file to a permanent location
    $uploadDir = 'uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    if (!move_uploaded_file($file['tmp_name'], $uploadFile)) {
        die("Failed to move uploaded file.");
    }

    // Create an instance of the QR code reader
    $qrReader = new QrReader($uploadFile);

    // Decode the QR code from the image
    $text = $qrReader->text();

    if ($text) {
        echo "<h2>QR Code Data:</h2>";
        echo "<p>" . htmlspecialchars($text) . "</p>";
    } else {
        echo "<p>No QR code found in the uploaded image.</p>";
    }

    // Optionally, delete the uploaded file
    unlink($uploadFile);
} else {
    echo "<p>Invalid request.</p>";
}
?>
