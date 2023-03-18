<?php
session_start();
include 'connect.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
$location = GetLocationData();

$name = $data->Name;
$email = $data->Email;
$price = ConvertPrice($data->Price, false)->price;
$discount = $data->Discount;
$country = $location['Country'];
$city = $location['City'];
$ip = $location['IP'];

// $country = "Netherlands";
// $city = "Amsterdam";
// $ip = "127.0.0.1";

$paymentId = $_SESSION['id'];

$device = $data->Device;
$orderNumber = uniqid();

$sql = "INSERT INTO `Orders`(`OrderNumber`, `PaymentId`, `FullName`, `Email`, `Price`, `Discount`, `Country`, `City`, `IP`, `Device`) VALUES (?,?,?,?,?,?,?,?,?,?)";
if (Query($conn, $sql, "ssssssssss", $orderNumber, $paymentId, $name, $email, $price, $discount, $country, $city, $ip, $device) == 1) {
    $sql = "SELECT Id FROM `Orders` WHERE `OrderNumber` = ?";
    $orderId = Query($conn, $sql, "s", $orderNumber);
    for ($i = 0; $i < sizeof($_SESSION['cart']); $i++) {
        $language = isset($_SESSION["cart"][$i]->language) ? $_SESSION["cart"][$i]->language : NULL;
        $comments = isset($_SESSION["cart"][$i]->comments) ? $_SESSION["cart"][$i]->comments : NULL;
        $sql = "INSERT INTO `OrderItems` (`OrderId`, `PackageId`, `Link`, `Comments`, `Language`, `Quantity`) VALUES  (?, ?, ?, ?, ?, ?)";
        Query($conn, $sql, "iisssi", $orderId[0]["Id"], $_SESSION["cart"][$i]->id, $_SESSION["cart"][$i]->link, $comments, $language,  $_SESSION["cart"][$i]->quantity);
    }
} else {
    echo "Failed";
}

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';

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

    $mail->addAddress('sahibzed23@gmail.com', 'Sahib');     //Add a recipient
    //$mail->addAddress('alikhan-z@hotmail.com', 'Ali');               //Name is optional

    //Content

    $mail->Subject = 'New Order #' . $orderNumber;
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
    <h1>New Order!</h1>
    <p>Name: <b>' . $name . '</b></p>
    <br>
    <p>Email: ' . $email . '<br>Price: £' . $price . ' <br> Country: ' . $country . '</p>
    </body>
    </html>
    ';
    $mail->AltBody = 'Fullname: ' . $name . ' \n Email: ' . $email . ' \n Price: £' . $price . ' \n Country: ' . $country;
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
