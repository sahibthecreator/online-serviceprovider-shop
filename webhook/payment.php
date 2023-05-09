<?php

require '../vendor/autoload.php';
$env = parse_ini_file('../.env');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$stripe = new \Stripe\StripeClient($env["STRIPE_SECRET_KEY"]);

$endpoint_secret = 'whsec_f6KijEW6Pc9PAeDjtDJMeJL2irfkK75t';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
$paymentIntent = $event->data->object;

switch ($event->type) {
    case 'payment_intent.payment_failed':
        UpdatePaymentStatus("Failed", $paymentIntent->id);
        break;
    case 'payment_intent.processing':
        UpdatePaymentStatus("Processing", $paymentIntent->id);
        break;
    case 'payment_intent.requires_action':
        UpdatePaymentStatus("Requires Action", $paymentIntent->id);
        break;
    case 'payment_intent.succeeded':
        UpdatePaymentStatus("Succeeded", $paymentIntent->id);
        SendOrderConfirmation($paymentIntent->metadata->name, $paymentIntent->metadata->email, $paymentIntent->metadata->order_number);
        break;
    default:
        echo 'Received unknown event type ' . $event->type;
}

http_response_code(200);



function UpdatePaymentStatus($status, $paymentIntent)
{
    include '../api/connect.php';
    $sql = "UPDATE `Orders` SET `Status` = ? WHERE `PaymentId` = ?";
    if (Query($conn, $sql, "ss", $status, $paymentIntent) == 1) {
        NotifyAdmin($status, $paymentIntent);
    } else {
        ReportUpdateFailure($status, $paymentIntent);
    }
}

function SendOrderConfirmation($customerName, $customerEmail, $orderNumber)
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

        $mail->addAddress($customerEmail, $customerName);     //Add a recipient

        //Content

        $mail->Subject = 'Order Confirmation';
        $mail->Body    = '
    <html>
    <head>
    <title></title>
    <style>
    body{color: black;
    width: 100%;}
    h1{color: red}
    </style>
    </head>
    <body>
    <h2>Thank you for your order!</h2>
    <p>Dear '.$customerName.',<br>
    Thank you for choosing BOOSTYOURACCOUNT for your purchase, <br>
    Your order number is  <b>' . $orderNumber . '</b>.<br><br>
    We will start Boosting your account shortly, usually it takes a few minutes, however sometimes we need more time in order to process your order safely.
    <br><br>
    Our customer support team is available to help you with any questions or concerns anytime.
    <br><br>
    Kind regards, <br>
    BOOSTYOURACCOUNT - Social Media Growth
    </p>

    </body>
    </html>
    ';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function NotifyAdmin($status, $paymentIntent)
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

        $mail->Subject = 'Order Status Report';
        $mail->Body    = '
    <html>
    <head>
    <title></title>
    <style>
    body{color: black;
    width: 100%;}
    h1{color: red}
    </style>
    </head>
    <body>
    <h2>Order Status changed to ' . $status . '</h2>
    <p>Payment Id: <b>' . $paymentIntent . '</b></p>
    </body>
    </html>
    ';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function ReportUpdateFailure($status, $paymentIntent)
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

        $mail->Subject = 'Status Update Failed';
        $mail->Body    = '
    <html>
    <head>
    <title></title>
    <style>
    body{color: black;
    width: 100%;}
    h1{color: red}
    </style>
    </head>
    <body>
    
    <h2>Failed to update payment status in database from open to ' . $status . '</h2>
    <p>Payment Id: <b>' . $paymentIntent . '</b></p>
    </body>
    </html>
    ';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
