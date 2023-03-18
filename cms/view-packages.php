<?php
session_start();
include "../api/connect.php";
if (!isset($_SESSION["logedin"])) {
    header("Location: auth.php");
}
require "../api/mrpopularAPI.php";
$api = new Api();
$services;
if (isset($_SESSION['ApiServices'])) {
    $services = $_SESSION['ApiServices'];
}else{
    $services = $api->service()->service;
    $_SESSION['ApiServices'] = $api->service()->service;
}

$currency = "&#163;";
if (isset($_GET["ru"])) {
    $packages = Query($conn, "SELECT `Id`,`API_Id`,`Pack`,`Type`,`Amount`,`Details`,`Name`,`PriceRub` AS Price FROM Packages WHERE ?", "i", 1);
    $currency = "&#8381;";
} else
    $packages = Query($conn, "SELECT * FROM Packages WHERE ?", "i", 1);


$groupedPackages = Query($conn, "SELECT Pack, Name FROM Packages WHERE ? GROUP BY Pack  ORDER BY CASE
WHEN Pack = 'Instagram' then 1
WHEN Pack = 'TikTok' then 2 
WHEN Pack = 'Facebook' then 3
WHEN Pack = 'Youtube' then 4
WHEN Pack = 'Telegram' then 5
WHEN Pack = 'Linkedin' then 6
WHEN Pack = 'App Store' then 7
WHEN Pack = 'Google Play' then 8
END ASC", "i", 1);
$packageTypes = Query($conn, "SELECT Pack, Type FROM Packages WHERE ? GROUP BY Pack, Type", "i", 1);
$socials = array();

foreach ($groupedPackages as $socialmedia) {
    $socialmedia = $socialmedia["Pack"];
    $socials[$socialmedia] = array();
    foreach ($packageTypes as $type) {
        if ($socialmedia == $type["Pack"]) {
            array_push($socials[$socialmedia], $type["Type"]);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator | BoostYourAccount</title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <link rel="stylesheet" href="../css/styleCMS.css?v=<?= time() ?>">
    <script defer src="js/view-packages.js?v=<?= time() ?>"></script>
    <link rel="shortcut icon" href="https://ru.boostyouraccount.com/icon.ico">

</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="center-text">
        <p>Packages</p>
    </div>
    <div class="center-text webChoice">
        <h3>Website:</h3>
        <div>
            <a href="?">EN</a>
            <a href="?ru=1">RU</a>
        </div>
    </div>

    <div class="btn-container">
        <p>*Double click on price to start typing new price and then confirm.<br>*Use Dot(.) as a decimal seperator eg: 1.5</p>
        <a href="packages.php">Add new package</a>
    </div>
    <div class="view-packages-container">
        <?php
        foreach ($groupedPackages as $socialmedia) {
            $pack = $socialmedia["Pack"];
            $socialmedia = $socialmedia["Name"];
            $socialmedia = $string = preg_replace("/[^a-zA-Z]+/", "", $socialmedia);

            echo '<div class="pack-container">
            <img src="../img/' . $socialmedia . '-icon.webp" alt="' . $socialmedia . ' icon" class="social-icon" />
            <div class="data-container">';
            foreach ($socials[$pack] as $social) {
                echo '<div class="col">
                        <p class="heading">' . $social . '</p>';
                foreach ($packages as $package) {
                    if ($package["Pack"] == $pack && $package["Type"] == $social) {
                        $cost = $services->{$package['API_Id']}->price;
                        $cost *= $package["Amount"];
                        if (!isset($_GET["ru"]))
                            $cost = $cost * 0.011154889;
                        $cost = round($cost, 2);
                        $profit = $package["Price"] - $cost;
                        $profitMargin = ($profit / $cost) * 100;
                        echo '<div class="item">';
                        echo '<p>' . $package['Amount'] . ' ' . $package['Type'] .
                            ' - ' . $currency . '</p> <p class="price-field" id="' . $package['Id'] . '">' . $package['Price'] .
                            '</p><p> | API Price: ' . $currency . $cost . ' | Profit: ' . $currency . $profit . ' - ' . round($profitMargin, 2) . '%</p>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            }
            echo '</div></div>';
        }

        ?>
    </div>
</body>

</html>