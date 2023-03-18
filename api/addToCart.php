<?php
session_start();

if (isset($_POST['addToCart']) || isset($_POST['buyNow']) || isset($_POST["addToCartFromMainPage"])) {
    if ($_POST['link'] == "") {
        header("Location: ../product.php?id=" . $_POST['product'] . "&error=link");
        die();
    }
    if (!isset($_SESSION['cart']))
        $_SESSION["cart"] = array();
    $product = new stdClass();
    $product->id = $_POST['product'];
    $product->link = $_POST['link'];
    $product->quantity = $_POST['quantity'];
    if (isset($_POST['language']))
        $product->language = $_POST['language'];
    if (isset($_POST['comments']))
        $product->comments = $_POST['comments'];
    array_push($_SESSION['cart'], $product);

    if (isset($_POST['buyNow']))
        header("Location: ../cart.php");
    else if (isset($_POST['addToCart']))
        header("Location: ../product.php?id=" . $_POST['product']);
}
