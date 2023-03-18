<?php
session_start();
include "api/connect.php";
?>
<!doctype html>

<head>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4DZZG6870T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-4DZZG6870T');
    </script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="author" content="Sahib Zulfigar" />
    <title>BoostYourAccount | Contact Us</title>
    <meta name="description" content="If you have any questions about our growth services, please send us a message via Contact Us page. We are glad to help!" />
    <meta name="keywords" content="BoostYourAccount, Buy followers, Buy likes, grow social media cheap, buy instagram likes, buy instagram followers cheap, buy tiktok likes, buy app store reviews, buy reviews, Social media marketing" />


    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="BoostYourAccount | Contact Us" />
    <meta property="og:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta property="og:url" content="https://boostyouraccount.com/contact-us.php" />
    <meta property="og:site_name" content="Boost Your Account" />
    <meta property="og:description" content="If you have any questions about our growth services, please send us a message via Contact Us page. We are glad to help!" />
    <meta name="twitter:title" content="BoostYourAccount | Contact Us" />
    <meta name="twitter:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta name="twitter:url" content="https://boostyouraccount.com/contact-us.php" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="apple-touch-icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="canonical" href="https://boostyouraccount.com/contact-us.php">

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <script defer src="js/contact-us.js?v=<?= time() ?>"></script>
    <script defer src="js/utils.js?v=<?= time() ?>"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <section class="contact-us hidden ">
        <img src="img/support-guy.webp" alt="guy with headphones" />

        <div class="center-text-container ">
            <p>Get in Touch</p>
            <p class="base-text">If you have any questions about our growth services,<br>
                please send us a message below.</p>
        </div>

        <section class="container ">
            <section class="field">
                <p>Full Name</p>
                <input type="text" name="fullName" placeholder="Required" />
            </section>
            <section class="field">
                <p>Your E-mail</p>
                <input type="email" name="email" placeholder="Required" />
            </section>
            <section class="field">
                <p>Order Number</p>
                <input type="text" name="orderNumber" placeholder="Optional" />
            </section>
            <section class="field">
                <p class="message">Message</p>
                <textarea name="message" placeholder="How can we help you?" id="" cols="30" rows="10"></textarea>
            </section>
            <div class="boost-btn" onclick="SendMessage()">
                <p>Contact Us!</p>
            </div>
            <p id="usr-message"></p>
        </section>

        <div class="slider-container hidden">
            <p>Most favourite</p>
            <div class="arrows-container hidden slideRight">

                <img src="img/arrow.webp" class="left-arrow" alt="left arrow">
                <div class="slider" id="slider">
                    <?php

                    $packages = Query($conn, "SELECT * FROM Packages WHERE ? Group BY Pack 
                ORDER BY CASE
                WHEN Pack = 'Instagram' then 1
                WHEN Pack = 'TikTok' then 2 
                WHEN Pack = 'Facebook' then 3
                WHEN Pack = 'Youtube' then 4
                WHEN Pack = 'Telegram' then 5
                WHEN Pack = 'Linkedin' then 6
                WHEN Pack = 'App Store' then 7
                WHEN Pack = 'Google Play' then 8
                END ASC", "i", 1);
                    for ($i = 0; $i < sizeof($packages); $i++) {
                        $minPrice = Query($conn, "SELECT Price FROM Packages WHERE Pack = ? ORDER BY Price LIMIT 1", "s", $packages[$i]['Pack']);
                        $converter = ConvertPrice($minPrice[0]['Price'], true);
                        $price = $converter->price;
                        $currency = $converter->currency;

                        echo '<a href="shop.php?socMedia=' . $packages[$i]['Name'] . '" class="product">
                    <div class="product-container ' . $packages[$i]['Name'] . '">
                            <div class="row1">
                                <p>' . $packages[$i]['Pack'] . '</p>
                            </div>
                            <div class="row2">
                                <img src="img/' . $packages[$i]['Name'] . '-white-icon.webp" />
                            </div>
                        </div>
                        <p class="price-text">From  ' . $currency . '' . $price . '</p>
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

    </section>



    <?php include "footer.html"; ?>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</body>

</html>