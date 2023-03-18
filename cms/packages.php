<?php
session_start();
include "../api/connect.php";
if (!isset($_SESSION["logedin"])) {
    header("Location: auth.php");
}
if (isset($_POST['submit'])) {
    if ($_POST['amount'] != "" && $_POST['price'] != "") {
        $nameLowCase = strtolower($_POST['pack']);
        $name = str_replace(" ", "-", $nameLowCase);
        $sql = "INSERT INTO `Packages`(`Pack`, `Type`, `Amount`, `Name`, `Price`) VALUES (?, ?, ?, ?, ?)";
        Query($conn, $sql, 'ssiss', $_POST['pack'], $_POST['type'], $_POST['amount'], $name, $_POST['price']);
    }
}
if (isset($_POST['delete'])) {
    if ($_POST['amount'] != "" && $_POST['price'] != "") {
        $nameLowCase = strtolower($_POST['pack']);
        $name = str_replace(" ", "-", $nameLowCase);
        $sql = "INSERT INTO `Packages`(`Pack`, `Type`, `Amount`, `Name`, `Price`) VALUES (?, ?, ?, ?, ?)";
        Query($conn, $sql, 'ssiss', $_POST['pack'], $_POST['type'], $_POST['amount'], $name, $_POST['price']);
    }
}

?>
<!doctype html>

<head>
    <meta charset="utf-8">
    <title>Boost Your Account</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="Sahib Zulfigar" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />


    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="" />
    <meta property="og:image" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <meta property="og:description" content="" />
    <meta name="twitter:title" content="" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:url" content="" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://ru.boostyouraccount.com/icon.ico">

    <link rel="stylesheet" href="../css/styleCMS.css?v=<?= time() ?>">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Akshar:wght@400;500;600;700&family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include "navbar.php"; ?>

    <section class="add-pack-container">
        <form action="" method="POST">
            <h1>Add pack</h1>
            <label for="">Select pack</label>
            <select name="pack" id="select-pack">
                <option value="Instagram">Instagram</option>
                <option value="TikTok">TikTok</option>
                <option value="Telegram">Telegram</option>
                <option value="Youtube">Youtube</option>
                <option value="Facebook">Facebook</option>
                <option value="Linkedin">Linkedin</option>
                <option value="App Store">App Store</option>
                <option value="Google Play">Google Play</option>
            </select>
            <label for="">Select Type</label>
            <select name="type" id="select-type">
                <option value="Followers">Followers</option>
                <option value="Subscribers">Subscribers</option>
                <option value="Likes">Likes</option>
                <option value="Page Likes">Page Likes</option>
                <option value="Comments">Comments</option>
                <option value="Views">Views</option>
                <option value="Saves">Saves</option>
                <option value="Activity">Activity</option>
                <option value="Story Views">Story Views</option>
                <option value="Reviews">Reviews</option>
                <option value="Friends">Friends</option>
                <option value="Group Members">Group Members</option>
                <option value="Channel Members">Channel Members</option>
                <option value="Reactions">Reactions</option>
                <optgroup label="Linkedin">
                    <option value="Connections">Connections</option>
                </optgroup>
                <optgroup label="App Store">
                    <option value="Installs">Installs</option>
                    <option value="Reviews">Reviews</option>
                </optgroup>
            </select>
            <label for="">Amount</label>
            <input type="number" placeholder="500" name="amount" id="amountInput">
            <label for="">Price</label>
            <input type="text" placeholder="£5" name="price" id="priceInput">
            <input type="submit" name="submit" value="Add Package" class="addPack-btn">
        </form>
        <section class="preview-box">
            <h1>Preview</h1>
            <div>
                <div class="product-container instagram">
                    <div class="row1">
                        <img src="../img/instagram-white-icon.webp" id="image" />
                        <p id="packName">Instagram</p>
                    </div>
                    <div class="row2">
                        <p class="amount">500</p>
                        <p id="type">Followers</p>
                    </div>
                </div>
                <p class="price-text">£</p>
            </div>
        </section>
    </section>
    <section class="center-text-container">
        <p>SHOP PREVIEW</p>
    </section>
    <section class="shop-preview">
        <?php
        $packages = Query($conn, "SELECT * FROM Packages WHERE ? ORDER BY Pack", "i", 1);
        for ($i = 0; $i < sizeof($packages); $i++) {
            echo '<div class="product">
                <div class="product-container ' . $packages[$i]['Name'] . '">
                        <div class="row1">
                            <img src="../img/' . $packages[$i]['Name'] . '-white-icon.webp" />
                            <p>' . $packages[$i]['Pack'] . '</p>
                        </div>
                        <div class="row2">
                            <p class="amount">' . $packages[$i]['Amount'] . '</p>
                            <p>' . $packages[$i]['Type'] . '</p>
                        </div>
                    </div>
                    <p class="price-text">£ ' . $packages[$i]['Price'] . '</p>
                    <a href="deletePack.php?id=' . $packages[$i]['Id'] . '">Delete<a/>
                    </div>';
        }
        ?>
    </section>
    <script>
        document.getElementById("select-pack").onchange = function() {
            let name = document.getElementById("select-pack").value.toLowerCase();
            name = name.replace(/\s+/g, '-');
            document.getElementsByClassName("product-container")[0].className = "product-container " + name;
            document.getElementById("image").src = "../img/" + name + "-white-icon.webp";
            document.getElementById("packName").innerHTML = document.getElementById("select-pack").value;
        }
        document.getElementById("select-type").onchange = function() {
            document.getElementById("type").innerHTML = document.getElementById("select-type").value;
        }
        document.getElementById("amountInput").onchange = function() {
            document.getElementsByClassName("amount")[0].innerHTML = document.getElementById("amountInput").value;
        }
        document.getElementById("priceInput").onchange = function() {
            document.getElementsByClassName("price-text")[0].innerHTML = "£ " + document.getElementById("priceInput").value;
        }
    </script>
</body>

</html>