<?php
$servername = "mysql";
$username = "root";
$password = "qwerty";

//Create connection
$conn = new mysqli($servername, $username, $password, "Boost-Your-Account");

// $servername = "127.0.0.1:3306";
// $username = "u449982275_sahibroot";
// $password = "hM0&?XR7w";
// $conn = new mysqli($servername, $username, $password, "u449982275_BoostAccount");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
function Query($conn, $sql,  $datatype, ...$data)
{
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($datatype, ...$data);
    if ($stmt->execute()) {
        if (str_contains($sql, "SELECT") !== FALSE) {
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } else
            return 1;
    } else
        return -1;
}
function getUserIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function GetLocationData()
{
    $ip = getUserIpAddr();
    $url = 'https://ip-api.io/json/' . $ip;
    $json = file_get_contents($url);
    $obj = json_decode($json);
    $country = $obj->country_name;
    $city = $obj->city;
    $data = array("Country" => $country, "City" => $city, "IP" => $ip);
    return $data;
}

function ConvertPrice($price, $toGB)
{
    $symbol = "";
    if ($_SESSION['currency'] == "GBP") {
        $symbol = "&#163;";
    } else if ($_SESSION['currency'] == "EUR") {
        $toGB ? $price = $price * 1.14 : $price = $price / 1.14;
        $price = number_format($price, 2);
        $symbol = "&#8364;";
    } else if ($_SESSION['currency'] == "USD") {
        $toGB ?  $price = $price * 1.24 : $price = $price / 1.24;
        $price = number_format($price, 2);
        $symbol = "&#36;";
    }
    $output = new stdClass();
    $output->price = $price;
    $output->currency = $symbol;
    return $output;
}
