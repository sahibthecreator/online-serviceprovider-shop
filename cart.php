<?php
session_start();
include "api/connect.php";
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
    <title>Cart | BoostYourAccount — Social Media Growth</title>
    <meta name="description" content="Add to cart real followers, likes, views, comments and a lot more in BoostYourAccount." />
    <meta name="keywords" content="BoostYourAccount, Buy followers, Buy likes, grow social media cheap, buy instagram likes, buy instagram followers cheap, buy tiktok likes, buy app store reviews, buy reviews, Social media marketing" />

    <!-- Facebook and Twitter integration -->
    <meta property="og:title" content="Cart | BoostYourAccount — Social Media Growth" />
    <meta property="og:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta property="og:url" content="https://boostyouraccount.com/cart.php" />
    <meta property="og:site_name" content="Boost Your Account" />
    <meta property="og:description" content="Add to cart real followers, likes, views, comments and a lot more in BoostYourAccount." />
    <meta name="twitter:title" content="Cart | BoostYourAccount — Social Media Growth" />
    <meta name="twitter:image" content="https://boostyouraccount.com/img/preview.jpg?v" />
    <meta name="twitter:url" content="https://boostyouraccount.com/cart.php" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="shortcut icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="apple-touch-icon" href="https://boostyouraccount.com/icon.ico?v">
    <link rel="canonical" href="https://boostyouraccount.com/cart.php">

    <link rel="stylesheet" href="css/style.css?v=<?= time() ?>">
    <script defer src="js/cart.js?v=<?= time() ?>"></script>
    <script defer src="js/utils.js?v=<?= time() ?>"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <link rel="stylesheet" href="css/checkout.css?v=<?= time() ?>" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <div class="center-text-container hidden slideRight">
        <p>CHECKOUT</p>
    </div>
    <section class="cart-container hidden slideRight">
        <section class="main">
            <?php
            $packages = array();
            if (isset($_SESSION["cart"])) {
                foreach ($_SESSION["cart"] as $item) {
                    array_push($packages, Query($conn, "SELECT * FROM Packages WHERE Id = ?", "i", $item->id));
                }
            }
            $totalPrice = 0;
            if (sizeof($packages) == 0) {
                echo '
                <div class="empty-cart-container">
                <img class="empty-cart-image" src="img/empty-cart.webp" />
                <p class="empty-cart-text">Cart is empty</p>
                </div>
                ';
            }
            $currency = ConvertPrice(0, true)->currency;
            for ($i = 0; $i < sizeof($packages); $i++) {
                $converter = ConvertPrice($packages[$i][0]['Price'], true);
                $price = $converter->price;
                $totalPrice +=  floatval($price) * $_SESSION["cart"][$i]->quantity;

                echo '
            <div class="product cart">
                <div class="product-container ' . $packages[$i][0]['Name'] . '">
                        <div class="row1">
                            <img src="img/' . $packages[$i][0]['Name'] . '-white-icon.webp" />
                            <p>' . $packages[$i][0]['Pack'] . '</p>
                        </div>
                        <div class="row2">';
                if ($packages[$i][0]['Type'] == "Activity")
                    echo '<img src="img/' . $packages[$i][0]['Type'] . '-icon.webp" alt="activity icon">';
                else
                    echo '<p class="amount">' . $packages[$i][0]['Amount'] . '</p>';
                echo '<p class="type">' . $packages[$i][0]['Type'] . '</p>
                        </div>
                </div>
                <section class="details">
                    <div class="row1">
                        <section class="price-section"> 
                            <p>Price</p>
                            <p class="price-text">' . $currency . ' ' . $price . '</p>
                        </section>
                        <section class="quantity-section"> 
                            <p>Quantity</p>
                            <p class="quantity-text">' . $_SESSION["cart"][$i]->quantity . '</p>
                        </section>
                        <section class="total-section"> 
                            <p>Total</p>
                            <p class="total-text">' . $currency . ' ' . $price * $_SESSION["cart"][$i]->quantity . '</p>
                        </section>
                    </div>
                    <div class="row2">
                    <p><b>Link:</b> ' . $_SESSION["cart"][$i]->link . '</p>';
                if (isset($_SESSION["cart"][$i]->comments)) {
                    echo '<p><b>Comments:</b> ' . $_SESSION["cart"][$i]->comments . '</p>';
                }
                echo '</div>
                    <form action="api/removeFromCart.php" type="hidden" method="post">
                    <button name="remove" value="' . $i . '" type="submit"><a class="remove-btn">Remove</a></button>
                    </form>
                </section>
            </div>
            ';
            }
            if (sizeof($packages) != 0) {
                $items = sizeof($packages) == 1 ? 'item' : 'items';
                echo '<section class="total-items-section">
            <p>' . sizeof($packages) . ' ' . $items . '</p>
            <p>' . $currency . ' ' . $totalPrice . ' </p>
            </section>
            ';
            }
            ?>
        </section>
        <aside class="sidebar">
            <p>ENTER PROMO CODE</p>
            <section class="promo-input">
                <input type="text" name="promo" id="promocode">
                <button id="submitPromo">SUBMIT</button>
            </section>
            <section class="price-fields">
                <p>Subtotal:</p>
                <p><?php echo $currency . $totalPrice ?></p>
            </section>
            <section class="price-fields">
                <p>Discount:</p>
                <p id="discountText">- <?php $discount = 0;
                                        $totalPrice -= $discount;
                                        echo $currency . $discount; ?></p>
            </section>
            <section class="price-fields">
                <p>Total:</p>
                <p id="totalPriceText"><?php echo $currency . $totalPrice ?></p>
            </section>
            <button class="checkout-btn" id="checkoutBtn">CHECKOUT</button>
            <p id="userMessage"></p>
        </aside>

    </section>
    <div id="checkoutModal">
        <!-- Display a payment form -->
        <form id="payment-form">
            <div class="input-container">
                <div>
                    <label for="name" class="input-label">Full Name</label>
                    <input type="text" placeholder="Required" id="name" name="name" class="inputs" />
                </div>
                <div>
                    <label for="email" class="input-label">Email</label>
                    <input type="text" placeholder="Required" id="email" name="email" class="inputs" />
                </div>
            </div>
            <p id="errorMessage"></p>
            <button type="button" id="confirmBtn">
                <span id="button-text">Continue</span>
            </button>
            <div id="payment-element">
                <!--Stripe.js injects the Payment Element-->
            </div>
            <div class="agreement-checkbox">
                <input checked="checked" type="checkbox" disabled>
                <p>I accept <a href="user-agreement.php">Terms of Service</a></p>
            </div>
            <button id="submit">
                <div class="spinner hiddenn" id="spinner"></div>
                <span id="button-text">Confirm</span>
            </button>
            <div id="payment-message" class="hiddenn"></div>
        </form>
    </div>

    <?php include "footer.html"; ?>
</body>

</html>