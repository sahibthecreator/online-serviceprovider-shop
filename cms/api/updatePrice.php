<?php
include '../../api/connect.php';

$data = file_get_contents('php://input');
$data = json_decode($data);

if (isset($_GET["ru"])) {
    if ($_GET["ru"] == "yes") {
        $sql = "UPDATE Packages SET PriceRub = ? WHERE Id = ?";
    }
} else {
    $sql = "UPDATE Packages SET Price = ? WHERE Id = ?";
}


if (Query($conn, $sql, "si", $data->Price, $data->Id) == 1) {
    echo json_encode("Success");
} else {
    echo json_encode("Failed");
}
