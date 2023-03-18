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
    <title>BoostYourAccount | Social Media Growth starting at £0.99</title>
    <meta name="description" content="Buying followers, likes, comments etc. for your social media is the best way to gain more engagement and success. Improve your social media marketing strategy with BoostYourAccount! We offer a huge range of social medias and activiy services." />
    <meta name="keywords" content="BoostYourAccount, Buy followers, Buy likes, grow social media cheap, buy instagram likes, buy instagram followers cheap, buy tiktok likes, buy app store reviews, buy reviews, Social media marketing" />

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="BoostYourAccount | Social Media Growth starting at £0.99" />
    <meta property="og:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta property="og:url" content="https://boostyouraccount.com/" />
    <meta property="og:site_name" content="Boost Your Account" />
    <meta property="og:description" content="Buying followers, likes, comments etc. for your social media is the best way to gain more engagement and success. Improve your social media marketing strategy with BoostYourAccount! We offer a huge range of social medias and activiy services." />
    <meta name="twitter:title" content="BoostYourAccount | Social Media Growth starting at £0.99" />
    <meta name="twitter:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta name="twitter:url" content="https://boostyouraccount.com/" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="apple-touch-icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="canonical" href="https://boostyouraccount.com/">

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <script defer src="js/index.js?v=<?= time() ?>"></script>
    <script defer src="js/utils.js?v=<?= time() ?>"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>
    <div class="main">
        <div class="col1 ">
            <div class="row1">
                <h1 class="hidden delay-1"><span class="yellow-text">Grow</span> your social media presence
                    <span class="yellow-text">organically</span>
                </h1>
            </div>
            <div class="row3 hidden delay-4 glow">
                <div id="ctaShopPage" class="boost-btn">
                    <p>BOOST NOW</p>
                </div>
            </div>
        </div>
        <div class="col2 hidden delay-3">
            <img src="img/rocket.webp">
        </div>
    </div>
    <div class="slider-container hidden">
        <p>Most favourite</p>
        <div class="arrows-container">

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
    <section class="center-text-container">
        <p>Why Us?</p>
    </section>
    <section class="white-container hidden">
        <div class="col1">
            <article class="row1 hidden slideRight">
                <p>Algorithms are safe with us!</p>
            </article>
            <article class="row2 hidden">
                <p>We take care about your algorithms and make sure it is not damaged.
                    Profiles we use contain real bio, posts and photos. Also, Boost speed
                    and quantity are calculated correctly to make it organic. So there will
                    be no doubts of organic growth of your account.</p>
            </article>
        </div>
        <div class="col2">
            <img src="img/high-quality.webp" alt="High quality delivery 3d illustration">
        </div>
    </section>
    <section class="transparent-container">
        <div class="col1">
            <img src="img/fast-delivery.webp" alt="3D Space Rocket illustration">
        </div>
        <div class="col2">
            <article class="row1 hidden slideLeft">
                <p>Fast Delivery</p>
            </article>
            <article class="row2 hidden">
                <p>Boost of account will be almost instantly!
                    Just choose suitable service and place an order,
                    our team will start the process of Boost your account
                </p>
            </article>
        </div>
    </section>
    <section class="white-container">
        <div class="col1">
            <article class="row1 hidden slideRight">
                <p>Chat with us!</p>
            </article>
            <article class="row2 hidden">
                <p>Our professional team are always happy to assist you.
                    We will help you to choose best suitable service for successful growth of your account!</p>
            </article>
        </div>
        <div class="col2">
            <img src="img/man-with-phone.webp" alt="Girl with phone">
        </div>
    </section>
    <section class="center-text-container hidden">
        <p>A MUST HAVE</p>
    </section>

    <section class="must-have-container hidden slideRight">
        <?php
        $package = Query($conn, "SELECT * FROM Packages WHERE Id = ?", "i", 102);
        $converter = ConvertPrice($package[0]['Price'], true);
        $price = $converter->price;
        $currency = $converter->currency;
        echo '
        <div class="col1">
            <div class="product-container ' . $package[0]['Name'] . '">
                <div class="row1">
                    <img src="img/' . $package[0]['Name'] . '-white-icon.webp" />
                    <p>' . $package[0]['Pack'] . '</p>
                </div>
                <div class="row2">
                    <img src="img/' . strtolower($package[0]['Type']) . '-white-icon.webp" alt="activity icon">
                    <p>' . $package[0]['Type'] . '</p>
                </div>
            </div>
        </div>
        <div class="col2">
            <div class="caption">
                <p>Instagram Activity</p>
            </div>
            <p class="discount-price">' . $currency . ' ' . $price * 2 . '</p>
            <div class="current-price-box">
                <p class="price">' . $currency . ' ' . $price . '</p>
                <div class="discount-box">
                    <p>50% OFF</p>
                </div>
            </div>
            ';
        $arr = explode("\n", $package[0]['Details']);
        echo '<p class="description">';
        foreach ($arr as $line) {
            echo  $line . "<br>";
        }

        echo '</p>
            <input class="text-input" type="text" placeholder="Link" name="link">
            <select class="text-input" name="language">
                <option value="null" disabled>Select language:</option>
                <option value="English">English</option>
                <option value="Russian">Russian</option>
            </select>
            <input readonly class="numberstyle" type="number" min="1" max="20" step="1" value="1" name="quantity">
            <button class="addproduct-btn slide_right" name="addToCart">
                <p>ADD TO CART</p>
            </button>
            <button class="boost-btn" name="buyNow">
                <p>BUY NOW</p>
            </button>
        </div>';
        ?>
    </section>

    <?php include "footer.html"; ?>
    <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script>

    </script>
</body>

</html>