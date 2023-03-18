<?php
include 'connect.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
$location = GetLocationData();

$adminIpList = array("85.146.83.87");

if (!in_array($location["IP"], $adminIpList)) {
    $sql = "INSERT INTO `Visits`(`Website`, `Page`, `Country`, `City`, `IP`, `Device`) VALUES (?, ?, ?, ?, ?, ?)";
    if (Query($conn, $sql, "ssssss", "en", $data->Page, $location["Country"], $location["City"], $location["IP"], $data->Device) == 1) {
        echo "Success";
    } else {
        echo "Failed";
    }
}
