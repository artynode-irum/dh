<?php

require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51PqenA04aDQdDBaSZBgD7zUD6RfHkU6QAvTO43viyGkppwtLxdL6dWiXfQ9wYcn2XMs8w9bVYsO0Q4QD3FXvgXy500VQ6Dfc9C');

// Token is created using Stripe.js or Elements
// Get the payment token ID submitted by the form:
$token = $_POST['stripeToken'];
$total = $_POST['total'];
$total = (int)$total*100;

// Create a charge:
try {

    $charge = \Stripe\Charge::create([
        'amount' => $total, // Amount in cents
        'currency' => 'usd',
        'description' => 'Example charge',
        'source' => $token,
    ]);
    // echo 'Payment successful!';
    // header("index.php");
    
} catch (\Stripe\Exception\CardException $e) {
    echo 'Payment failed: ' . $e->getMessage();
}

?>