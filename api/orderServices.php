<?php
session_start();
include 'connect.php';
require "mrpopularAPI.php";
$api = new Api();

$order = Query($conn, "SELECT Id FROM Orders WHERE PaymentId = ?", "s", $_SESSION['id']);

$orderItems = Query($conn, "SELECT * FROM OrderItems WHERE OrderId = ?", "i", $order[0]['Id']);

foreach ($orderItems as $orderItem) {
    $package = Query($conn, "SELECT * FROM Packages WHERE Id = ?", "i", $orderItem['PackageId']);
    $quantity = $orderItem['Quantity'] * $package[0]["Amount"];
    $order = "";
    if ($orderItem['Comments'] != NULL) {
        $order = $api->order(array(
            'service' => $package[0]["API_Id"],
            'quantity' => $quantity,
            'comment' => $orderItem['Comments'],
            'link' => $orderItem['Link']
        ));
    } else if ($orderItem['Language'] != NULL) {
        $order = $api->order(array(
            'service' => $package[0]["API_Id"],
            'quantity' => $quantity,
            'option' => $orderItem['Language'],
            'link' => $orderItem['Link']
        ));
    } else {
        $order = $api->order(array(
            'service' => $package[0]["API_Id"],
            'quantity' => $quantity,
            'link' => $orderItem['Link']
        ));
    }
    Query($conn, "UPDATE `OrderItems` SET `API_OrderNumber` = ? WHERE Id = ?", "si", $order, $orderItem['Id']);
}
echo json_encode("success");
