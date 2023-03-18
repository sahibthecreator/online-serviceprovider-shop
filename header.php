<?php
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = "GBP";
}
?>
<div class="header">
    <div class="menuIcon" onclick="MenuBtn()">
        <div class="bar1"></div>
        <div class="bar2"></div>
        <div class="bar3"></div>
    </div>
    <div class="links">
        <a href="index.php">
            <p>Home</p>
        </a>
        <a href="shop.php">
            <p>Shop</p>
        </a>

        <a href="about-us.php">
            <p>About us</p>
        </a>
        <a href="contact-us.php">
            <p>Contact us</p>
        </a>
        <div class="search-container-mobile">
            <div class="search-bar-mobile">
                <input class="search expandright" id="searchright" type="search" name="q" placeholder="Search eg: 'Instagram'">
                <label class="button searchbutton" for="searchright"><span class="mglass">&#9906;</span></label>
            </div>
            <div class="results"></div>
        </div>
        <div class="currency-selector-mobile">
            <p>Currency:</p>
            <select class="currency-picker" name="currency" onchange="changeCurrency(value)">
                <option value="GBP" <?= $_SESSION['currency'] == 'GBP' ? ' selected' : '' ?>>GBP</option>
                <option value="EUR" <?= $_SESSION['currency'] == 'EUR' ? ' selected' : '' ?>>EUR</option>
                <option value="USD" <?= $_SESSION['currency'] == 'USD' ? ' selected' : '' ?>>USD</option>
            </select>
        </div>
    </div>
    <a href="index.php" class="logo">
        <img class="logoHeader" src="img/logo.webp" />
    </a>
    <div class="icons">
        <div class="currency-selector">
            <select class="currency-picker" name="currency" onchange="changeCurrency(value)">
                <option value="GBP" <?= $_SESSION['currency'] == 'GBP' ? ' selected' : '' ?>>GBP</option>
                <option value="EUR" <?= $_SESSION['currency'] == 'EUR' ? ' selected' : '' ?>>EUR</option>
                <option value="USD" <?= $_SESSION['currency'] == 'USD' ? ' selected' : '' ?>>USD</option>
            </select>
        </div>
        <div class="search-container">
            <input class="search expandright" id="searchleft" type="search" name="q" placeholder="Поиск">
            <label class="button searchbutton" for="searchleft"><span class="mglass">&#9906;</span></label>
            <div class="results"></div>
        </div>
        <a href="cart.php" class="icon">
            <img class="shop-icon" src="img/shop-icon.webp" />
            <div class="cart">
                <p><?php echo isset($_SESSION["cart"]) ? sizeof($_SESSION["cart"]) : "0" ?></p>
            </div>
        </a>
    </div>

</div>
<script type="text/javascript">
    $(function() {
        var url = window.location.pathname;
        var page = url.substring(url.lastIndexOf("/") + 1, url.length);
        if (page == "") {
            $(".links a").get(0).className += "selected";
        }
        $(".links a").each(function() {
            let href = this.href.substring(this.href.lastIndexOf("/") + 1, this.href.length);
            if (page == href) {
                $(this).addClass("selected");
            }
        });
    });

    function MenuBtn() {
        var x = document.getElementsByClassName("links").item(0);
        document.getElementsByClassName("menuIcon").item(0).classList.toggle("change");
        if (x.style.display === "block") {
            x.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }
</script>
<script type="text/javascript" src="js/search.js"></script>