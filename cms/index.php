<?php
session_start();
include "../api/connect.php";
if (!isset($_SESSION["logedin"])) {
    header("Location: auth.php");
}
$today = date('Y-m-d');
$todayVisits = sizeof(Query($conn, "SELECT * FROM Visits WHERE Date >= ?", "s", $today));

$yesterday = date('Y-m-d', strtotime("-1 days"));
$yesterdayVisits = sizeof(Query($conn, "SELECT Id FROM Visits WHERE Date = ?", "s", $yesterday));
$totalVisits = sizeof(Query($conn, "SELECT Id FROM Visits WHERE ?", "i", 1));

$last4Visits = Query($conn, "SELECT `Website`, `Page`, `Country`, `Date` FROM Visits ORDER BY Date DESC Limit ?", "i", 4);
$last4Orders = Query($conn, "SELECT `PaymentId`, `FullName`, `Price`, `Date` FROM Orders ORDER BY Date DESC Limit ?", "i", 4);
$todayOrders = sizeof(Query($conn, "SELECT Id FROM Orders WHERE Date >= ?", "s", $today));


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator | BoostYourAccount</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/styleCMS.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="shortcut icon" href="https://ru.boostyouraccount.com/icon.ico">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="center-text">
        <p>BoostYourAccount CMS</p>
    </div>

    <div class="row justify-content-between">
        <div class="col-md-6">
            <div class="center-text mb-2">
                <p>VISITS</p>
            </div>
            <div class="row justify-content-space">
                <div class="col-md-4">
                    <h3 class="font-weight-bold">Today: <?= $todayVisits ?></h3>
                </div>
                <div class="col-md-4">
                    <h3 class="font-weight-bold">Yesterday: <?= $yesterdayVisits ?></h3>
                </div>
                <div class="col-md-4">
                    <h3 class="font-weight-bold">Total: <?= $totalVisits ?></h3>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table  table-dark table-striped table-hover">
                    <thead>
                        <?php foreach (array_keys($last4Visits[0]) as $colName) {
                            echo '<th>' . $colName . '</th>';
                        } ?>
                    </thead>
                    <?php
                    foreach ($last4Visits as $visit) {
                        echo '<tr>';
                        foreach (array_keys($visit) as $colName) {

                            $value = str_contains($visit[$colName], "payment_intent") ? "Payment Confirmation" : $visit[$colName];
                            $value = $visit[$colName] == "shop.php" ? "Shop" : $value;
                            $value = $visit[$colName] == "cart.php" ? "Cart" : $value;
                            $value = $visit[$colName] == "about-us.php" ? "About Us" : $value;
                            $value = $visit[$colName] == "contact-us.php" ? "Contact Us" : $value;
                            if (str_contains($visit[$colName], "shop.php?")) {
                                parse_str($visit[$colName], $query);
                                $value = "Shop - ";
                                foreach ($query as $param) {
                                    $paramName = substr($param, strpos($param, "="));
                                    $value = $value . $paramName . " ";
                                }
                            } else if ($colName == "Date") {
                                $today = new DateTime("now");
                                $time = explode(" ", $visit[$colName])[1];
                                $date = DateTime::createFromFormat("Y-m-d H:i:s", $visit[$colName]);

                                $today->setTime(0, 0, 0);
                                $date->setTime(0, 0, 0);
                                switch ($today->diff($date)->days) {
                                    case (0):
                                        $value = "Today" . " at " . $time;
                                        break;
                                    case (1):
                                        $value = "Yesterday" . " at " . $time;
                                        break;
                                }
                            }
                            echo '<td>' . $value . '</td>';
                        }
                        echo '</tr>';
                    } ?>
                </table>
                <a href="visits.php" class="btn btn-danger">See all</a>
            </div>

        </div>

        <div class="col-md-6">
            <div class="center-text mb-2">
                <p>Orders</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <h3 class="font-weight-bold">Today: <?= $todayOrders ?></h3>
                </div>
                <div class="col-md-4">
                    <h3 class="font-weight-bold">Total: <?= sizeof($last4Orders) ?></h3>
                </div>
            </div>
            <?php if (sizeof($last4Orders) > 1) { ?>
                <div class="table-responsive">
                    <table class="table  table-dark table-striped table-hover">
                        <thead>
                            <?php foreach (array_keys($last4Orders[0]) as $colName) {
                                echo '<th>' . $colName . '</th>';
                            } ?>
                        </thead>
                        <?php
                        foreach ($last4Orders as $visit) {
                            echo '<tr>';
                            foreach (array_keys($visit) as $colName) {

                                $value = str_contains($visit[$colName], "payment_intent") ? "Payment Confirmation" : $visit[$colName];
                                $value = $visit[$colName] == "shop.php" ? "Shop" : $value;
                                $value = $visit[$colName] == "cart.php" ? "Cart" : $value;
                                $value = $visit[$colName] == "about-us.php" ? "About Us" : $value;
                                $value = $visit[$colName] == "contact-us.php" ? "Contact Us" : $value;
                                if ($colName == "Date") {
                                    $today = new DateTime("now");
                                    $time = explode(" ", $visit[$colName])[1];
                                    $date = DateTime::createFromFormat("Y-m-d H:i:s", $visit[$colName]);

                                    $today->setTime(0, 0, 0);
                                    $date->setTime(0, 0, 0);
                                    switch ($today->diff($date)->days) {
                                        case (0):
                                            $value = "Today" . " at " . $time;
                                            break;
                                        case (1):
                                            $value = "Yesterday" . " at " . $time;
                                            break;
                                    }
                                }
                                echo '<td>' . $value . '</td>';
                            }
                            echo '</tr>';
                        } ?>
                    </table>
                    <a href="orders.php" class="btn btn-danger">See all</a>
                </div>
            <?php } ?>

        </div>




    </div>

</body>

</html>