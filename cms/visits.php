<?php
session_start();
include "../api/connect.php";
if (!isset($_SESSION["logedin"])) {
    header("Location: auth.php");
}
$visits = Query($conn, "SELECT * FROM Visits WHERE ? ORDER BY Id DESC", "i", 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visits | BoostYourAccount</title>
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

    <div class="table-responsive">
        <table class="table table-bordered table-dark table-striped table-hover">
            <thead>
                <?php
                foreach (array_keys($visits[0]) as $colName) {
                    echo '<th>' . $colName . '</th>';
                }
                ?>
            </thead>
            <?php
            foreach ($visits as $visit) {
                echo '<tr>';
                foreach (array_keys($visit) as $colName) {

                    $value = str_contains($visit[$colName], "payment_intent") ? "Payment Confirmation" : $visit[$colName];
                    $value = $visit[$colName] == "shop.php" ? "Shop" : $value;
                    $value = $visit[$colName] == "cart.php" ? "Cart" : $value;
                    $value = $visit[$colName] == "about-us.php" ? "About Us" : $value;
                    $value = $visit[$colName] == "contact-us.php" ? "Contact Us" : $value;


                    if (str_contains($visit[$colName], "product.php?")) {
                        $id = substr($visit[$colName], strpos($visit[$colName], "=") + 1);
                        $packageName = Query($conn, "SELECT `Amount`, `Pack`, `Type` FROM Packages WHERE Id = ? ", "i", $id);
                        if (sizeof($packageName) > 0)
                            $value = $packageName[0]["Amount"] . " " . $packageName[0]["Type"] . " " . $packageName[0]["Pack"];
                    } else if (str_contains($visit[$colName], "shop.php?")) {
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
            }
            ?>
        </table>
    </div>
</body>

</html>