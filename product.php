<?php
session_start();
include "api/connect.php";
if (!isset($_GET['id'])) header("Location: shop.php");
$id = $_GET['id'];
$packages = Query($conn, "SELECT * FROM Packages WHERE Id = ?", "s", $id);

?>
<!doctype html>

<head>
    <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-944123305"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-944123305');
</script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="author" content="Sahib Zulfigar" />
    <title>BoostYourAccount | Buy <?= $packages[0]["Amount"] ?> <?= $packages[0]["Type"] ?></title>
    <meta name="description" content="Buy any kind of activities for social medias such as Instagram, TikTok, Youtube etc. Instant delivery, real followers, and 24/7 support." />
    <meta name="keywords" content="BoostYourAccount, Buy followers, Buy likes, grow social media cheap, buy instagram likes, buy instagram followers cheap, buy tiktok likes, buy app store reviews, buy reviews, Social media marketing" />

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content='BoostYourAccount | Buy <?= $packages[0]["Amount"] ?> <?= $packages[0]["Type"] ?>' />
    <meta property="og:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta property="og:url" content="https://boostyouraccount.com/product.php" />
    <meta property="og:site_name" content="Boost Your Account" />
    <meta property="og:description" content="Buy any kind of activities for social medias such as Instagram, TikTok, Youtube etc. Instant delivery, real followers, and 24/7 support." />
    <meta name="twitter:title" content="BoostYourAccount | Buy <?= $packages[0]["Amount"] ?> <?= $packages[0]["Type"] ?>" />
    <meta name="twitter:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta name="twitter:url" content="https://boostyouraccount.com/product.php" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="apple-touch-icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="canonical" href="https://boostyouraccount.com/product.php">

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script defer src="js/product.js?v=<?= time() ?>"></script>
    <script defer src="js/utils.js?v=<?= time() ?>"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

</head>

<body class="product-page">
    <?php include "header.php"; ?>
    <section class="center-text-container breadcrumbs">
        <p class="small-header" id="breadcrumbs">Shop</p>
    </section>
    <section class="product-page container">
        <?php
        $cheapestPackage = Query($conn, "SELECT Price, Amount FROM Packages WHERE Type = ? AND Pack = ? Order BY Amount limit 1", "ss", $packages[0]['Type'], $packages[0]['Pack']);
        $priceNoDiscount = $packages[0]['Amount'] / $cheapestPackage[0]['Amount'];
        $priceNoDiscount = $priceNoDiscount * $cheapestPackage[0]['Price'];
        $converter = ConvertPrice($packages[0]['Price'], true);
        $price = $converter->price;
        $currency = $converter->currency;
        $priceNoDiscount = ConvertPrice($priceNoDiscount, true)->price;
        $options = Query($conn, "SELECT * FROM Packages WHERE Pack = ? AND Type = ? ORDER BY Amount", "ss", $packages[0]["Pack"], $packages[0]["Type"]);
        echo '
        <div class="col1">
            <div class="product-container ' . $packages[0]['Name'] . '">
                    <div class="row1">
                        <img src="img/' . $packages[0]['Name'] . '-white-icon.webp" />
                        <p>' . $packages[0]['Pack'] . '</p>
                    </div>
                    <div class="row2">';
        if ($packages[0]['Type'] == "Activity")
            echo '<img src="img/' . strtolower($packages[0]['Type']) . '-icon.webp" alt="activity icon">';
        else
            echo '<p class="amount">' . $packages[0]['Amount'] . '</p>';
        echo '<p class="type">' . $packages[0]['Type'] . '</p>
                    </div>
                </div>
                </div>
        <div class="col2">
            <div class="caption">
                <p>' . $packages[0]['Pack'] . ' ' . $packages[0]['Type'] . '</p>
            </div>
            <div class="options">';
        if ($packages[0]['Type'] != "Activity") {
            foreach ($options as $option) {
                echo '<a href="?id=' . $option['Id'] . '"><div class="rounded-product ' . $option['Name'] . '"><p>' . $option['Amount'] . '</p></div></a>';
            }
        } else {
            $arr = explode("\n", $packages[0]['Details']);
            echo '<p class="description">';
            foreach ($arr as $line) {
                echo '-' . $line . "<br>";
            }
        }
        echo '</div>
            <p class="discount-price">' . $currency . $priceNoDiscount . '</p>
            <p class="price">' . $currency . ' ' . $price . '</p>
            <form action="api/addToCart.php" type="hidden" method="post">
            <input class="text-input" name="link" type="text" placeholder="Link">
            <div class="help-tools">
            <div class="paste-btn">
                <img src="img/paste-icon.webp" alt="Paste Icon" />
                <p>Paste</p>
            </div>
            </div>'; // <p class="link-help">Где взять ссылку?</p>
        if (isset($_GET['error']))
            echo '<p class="error-message">Please, enter the link</p>';
        if ($packages[0]['Type'] == "Activity") {
            echo '<select class="text-input extra" name="language">
                <option value="null" disabled>Select language:</option>
                <option value="Russian">Russian</option>
                <option value="English">English</option>
            </select>';
        }
        if ($packages[0]['Type'] == "Comments" || $packages[0]['Type'] == "Reviews") {
            echo '
            <input maxlength="1000" class="text-input extra" name="comments" type="text" placeholder="Comments">
            ';
        }
        echo '<input readonly class="numberstyle" name="quantity" type="number" min="1" max="10" step="1" value="1">
            <input type="hidden" name="product" value="' . $_GET["id"] . '" />
            <button class="addproduct-btn slide_right" name="addToCart" type="submit">
                <p>ADD TO CART</p>
            </button>
            <button class="boost-btn" type="submit" name="buyNow">
                <p>BUY NOW</p>
            </button>
        </form>
        </div>';
        ?>

    </section>
    <div class="slider-container hidden">
        <p class="heading">Packages related to this item:</p>
        <div class="arrows-container">

            <img src="img/arrow.webp" class="left-arrow" alt="left arrow">
            <div class="slider" id="slider">
                <?php
                $recommended = Query($conn, "SELECT * FROM Packages WHERE Type = ? OR Pack = ? ORDER BY RAND() limit 15", "ss", $packages[0]['Type'], $packages[0]['Pack']);
                for ($i = 0; $i < sizeof($recommended); $i++) {
                    $converter = ConvertPrice($recommended[$i]['Price'], true);
                    $price = $converter->price;
                    echo '<a href="?id=' . $recommended[$i]['Id'] . '" class="product">
                    <div class="product-container ' . $recommended[$i]['Name'] . '">
                            <div class="row1">
                                <img src="img/' . $recommended[$i]['Name'] . '-white-icon.webp" />
                                <p>' . $recommended[$i]['Pack'] . '</p>
                            </div>
                            <div class="row2">';
                    if ($recommended[$i]['Type'] == "Activity") {
                        echo '<img src="img/activity-icon.webp" alt="activity icon">
                                    <p class="type">' . $recommended[$i]['Type'] . '</p>';
                    } else {
                        echo '<p class="amount">' . $recommended[$i]['Amount'] . '</p>
                                <p class="type">' . $recommended[$i]['Type'] . '</p>';
                    }
                    echo '</div>
                        </div>
                        <p class="price-text">' . $currency . ' ' . $price . '</p>
                        </a>';
                }
                ?>
            </div>
            <img src="img/arrow.webp" alt="right arrow" class="right-arrow">
        </div>
        <div id="ctaShopPage" class="boost-btn">
            <p>VIEW ALL BOOSTS</p>
        </div>

    </div>

    <?php include "footer.html"; ?>

</body>

</html>