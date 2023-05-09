<?php
session_start();
require_once('../vendor/autoload.php');
include 'connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$env = parse_ini_file('../.env');
\Stripe\Stripe::setApiKey($env["STRIPE_SECRET_KEY"]);
header('Content-Type: application/json');
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure");

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);


try {
    // retrieve JSON from POST body
    $data = file_get_contents('php://input');
    $data = json_decode($data);

    $priceInGB = ConvertPrice($data->Price, false)->price;
    $name = $data->Name;
    $email = $data->Email;
    $discount = $data->Discount;
    $device = $data->Device;
    $orderNumber = uniqid();


    // Create a PaymentIntent with amount and currency
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $priceInGB * 100,
        'currency' => 'gbp',
        'automatic_payment_methods' => [
            'enabled' => true,
        ],
        'metadata' => [
            'order_number' => $orderNumber,
            'email' => $email,
            'name' => $name
        ],
    ]);

    $output = [
        'clientSecret' => $paymentIntent->client_secret
    ];

    SaveOrder($name, $email, $priceInGB, $discount, $device, $paymentIntent->id, $conn, $orderNumber);

    $_SESSION["id"] = $paymentIntent->id;

    echo json_encode($output);
} catch (Error $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function SaveOrder($name, $email, $price, $discount, $device, $paymentId, $conn, $orderNumber)
{

    $location = GetLocationData();
    $country = $location['Country'];
    $city = $location['City'];
    $ip = $location['IP'];


    $sql = "INSERT INTO `Orders`(`OrderNumber`, `PaymentId`, `FullName`, `Email`, `Price`, `Discount`, `Country`, `City`, `IP`, `Device`, `Status`) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    if (Query($conn, $sql, "sssssssssss", $orderNumber, $paymentId, $name, $email, $price, $discount, $country, $city, $ip, $device, "Open") == 1) {
        $orderId = $conn->insert_id;
        for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
            $language = isset($_SESSION["cart"][$i]->language) ? $_SESSION["cart"][$i]->language : NULL;
            $comments = isset($_SESSION["cart"][$i]->comments) ? $_SESSION["cart"][$i]->comments : NULL;
            $sql = "INSERT INTO `OrderItems` (`OrderId`, `PackageId`, `Link`, `Comments`, `Language`, `Quantity`) VALUES  (?, ?, ?, ?, ?, ?)";
            Query($conn, $sql, "iisssi", $orderId, $_SESSION["cart"][$i]->id, $_SESSION["cart"][$i]->link, $comments, $language,  $_SESSION["cart"][$i]->quantity);
        }
    } else {
        ReportFailure($paymentId, $name, $email, $price, $discount);
    }
}

function ReportFailure($paymentIntent, $name, $email, $price, $discount)
{
    $mail = new PHPMailer(false);
    $env = parse_ini_file('../.env');

    try {
        //Server settings
        $mail->isSMTP();
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.hostinger.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;                                         //Enable SMTP authentication
        $mail->Username   = $env["EMAIL"];                     //SMTP username
        $mail->Password   = $env["EMAIL_PASSWORD"];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

        //Recipients
        $mail->setFrom($env["EMAIL"], 'BOOSTYOURACCOUNT');

        $mail->addAddress('support@boostyouraccount.com', 'CEO');     //Add a recipient

        //Content

        $mail->Subject = 'Order Creation Failed';
        $mail->Body    = '
    <html>
    <head>
    <title></title>
    <style>
    body{color: black;
    width: 100%;
    text-align: center;}
    h1{color: red}
    </style>
    </head>
    <body>
    
    <h2>Failed to save order in database</h2>
    <p>Payment Id:' . $paymentIntent . '</p>
    <p>Name:' . $name . '</p>
    <p>Email:' . $email . '</p>
    <p>Discount:' . $discount . '</p>
    <p>Price:' . $price . '</p>
    ';
        for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
            $mail->Body += '<p>' . $_SESSION["cart"][$i]->id . '</p>';
            $mail->Body += '<p>' . $_SESSION["cart"][$i]->link . '</p>';
            $mail->Body += '<p>' . $_SESSION["cart"][$i]->quantity . '</p>';
        }
        $mail->Body += '</body>
    </html>
    ';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
