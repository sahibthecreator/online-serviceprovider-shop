<?php
session_start();
include 'connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");

$orderNumber = Query($conn, "SELECT `OrderNumber` FROM Orders WHERE PaymentId = ?", "s", $_SESSION['id']);
echo json_encode($orderNumber[0]["OrderNumber"]);
