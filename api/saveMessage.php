<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include 'connect.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
$location = GetLocationData();
$sql = "INSERT INTO `Messages`(`FullName`, `Email`, `OrderNumber` ,`Message`, `Country`, `City`) VALUES (?, ?, ?, ?, ?, ?)";
if (Query($conn, $sql, "ssssss", $data->FullName, $data->Email, $data->OrderNumber, $data->Message, $location["Country"], $location["City"])) {
    echo json_encode("Success");
} else {
    echo json_encode("Failed");
}

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;                                         //Enable SMTP authentication
    $mail->Username   = 'noreply@boostyouraccount.com';                     //SMTP username
    $mail->Password   = 'Creator2232708$';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

    //Recipients
    $mail->setFrom('noreply@boostyouraccount.com', 'BoostYourAccount');
    $mail->addReplyTo('support@boostyouraccount.com', 'BoostYourAccount');

    $mail->addAddress('support@boostyouraccount.com', 'BoostYourAccount');     //Add a recipient

    $mail->Subject = 'New Message';
    $mail->Body    = '<p>From: ' . $data->FullName . ' - ' . $data->Email . '<br><br>' . $data->Message . '</p>';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
$mail = new PHPMailer(true);
try {
    //Server settings
    $mail->isSMTP();
    $mail->SMTPDebug = 2;
    $mail->Host = 'smtp.hostinger.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;                                         //Enable SMTP authentication
    $mail->Username   = 'noreply@boostyouraccount.com';                     //SMTP username
    $mail->Password   = 'Creator2232708$';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption

    //Recipients
    $mail->setFrom('noreply@boostyouraccount.com', 'BoostYourAccount');
    $mail->addReplyTo('support@boostyouraccount.com', 'BoostYourAccount');

    $mail->addAddress($data->Email, $data->FullName);     //Add a recipient

    $mail->Subject = 'Support Confirmation';
    $mail->Body    = '<p>Dear ' . $data->FullName . ', <br><br>Thank you for your message, it was successfully received by our support team. We will try to help you as soon as possible, usually it takes 1-2 days. <br>Your satisfaction is our priority! <br><br>Have a great rest of the day. <br><br>Kind Regards,<br>BoostYourAccount</p>';
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
