<?php
$timezoneApiKey = 'W9AH96V54EO6'; // Replace with your API key

// Get parameters from the query string
$lat = $_GET['lat'];
$lng = $_GET['lng'];

// Build the API URL
$url = "https://api.timezonedb.com/v2.1/get-time-zone?key={$timezoneApiKey}&format=json&by=position&lat={$lat}&lng={$lng}";

// Make the request to the TimeZoneDB API
$response = @file_get_contents($url);

if ($response === FALSE) {
    // Handle the error
    $error = error_get_last();
    echo json_encode(array('status' => 'ERROR', 'message' => $error['message']));
} else {
    // Return the response to the client
    header('Content-Type: application/json');
    echo $response;
}
?>
