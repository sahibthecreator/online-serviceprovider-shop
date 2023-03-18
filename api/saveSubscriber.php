<?php
include 'connect.php';

$data = file_get_contents('php://input');
$data = json_decode($data);
$location = GetLocationData();

if ($location["Country"] == null) {
    $sql = "INSERT INTO `Subscribers`(`Email`) VALUES (?)";
    if (Query($conn, $sql, "s", $data->Email) == 1)
        echo json_encode("Success");
    else
        echo json_encode("Fail");
} else {
    $sql = "INSERT INTO `Subscribers`(`Email`, `Country`, `City`) VALUES (?,?,?)";
    if (Query($conn, $sql, "sss", $data->Email, $location["Country"], $location["City"]) == 1)
        echo json_encode("Success");
    else
        echo json_encode("Fail");
}
