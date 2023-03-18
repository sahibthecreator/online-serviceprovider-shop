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
    <title>BoostYourAccount | Buy social media growth</title>
    <meta name="description" content="Buy any kind of activities for social medias such as Instagram, TikTok, Youtube etc. Instant delivery, real followers, and 24/7 support." />
    <meta name="keywords" content="BoostYourAccount, Buy followers, Buy likes, grow social media cheap, buy instagram likes, buy instagram followers cheap, buy tiktok likes, buy app store reviews, buy reviews, Social media marketing" />

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="BoostYourAccount | Buy social media growth" />
    <meta property="og:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta property="og:url" content="https://boostyouraccount.com/shop.php" />
    <meta property="og:site_name" content="Boost Your Account" />
    <meta property="og:description" content="Buy any kind of activities for social medias such as Instagram, TikTok, Youtube etc. Instant delivery, real followers, and 24/7 support." />
    <meta name="twitter:title" content="BoostYourAccount | Buy social media growth" />
    <meta name="twitter:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta name="twitter:url" content="https://boostyouraccount.com/shop.php" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="apple-touch-icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="canonical" href="https://boostyouraccount.com/shop.php">

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script defer src="js/shop.js?v=<?= time() ?>"></script>
    <script defer src="js/utils.js?v=<?= time() ?>"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
</head>

<body>
    <?php include "header.php"; ?>
    <section class="center-text-container breadcrumbs">
        <p>Packages</p>
        <p class="small-header" id="breadcrumbs">Shop</p>
    </section>

    <section class="container">
        <div class="spinner" id="loading">
            <div class="spinner1"></div>
        </div>
        <div class="filter-icon-container">
            <div class="view-filter">
                <div class="block"></div>
                <div class="block"></div>
                <div class="block1"></div>
                <div class="block1"></div>
            </div>
        </div>
        <section class="product-list hidden slideLeft" id="product-list">

        </section>
    </section>


    <?php include "footer.html"; ?>

</body>

</html>