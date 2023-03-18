<?php
include 'connect.php';
session_start();
require_once('../vendor/autoload.php');
\Stripe\Stripe::setApiKey('');
header('Content-Type: application/json');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

try {
    // retrieve JSON from POST body
    $data = file_get_contents('php://input');
    $data = json_decode($data);
    $priceInGB = ConvertPrice($data->Price, false)->price;

    // Create a PaymentIntent with amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $priceInGB * 100,
        'currency' => 'gbp',
        'automatic_payment_methods' => [
            'enabled' => true,
        ],
        'receipt_email' => $data->Email
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret
    ];

    $_SESSION["id"] = $paymentIntent->id;

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
