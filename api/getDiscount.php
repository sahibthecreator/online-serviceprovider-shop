<?php
include 'connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");

if (!empty($_GET['discount'])) {
    $code = $_GET['discount'];
    $discount = Query($conn, "SELECT * FROM Discounts WHERE Code = ?", "s", $code);
}
echo json_encode($discount);
