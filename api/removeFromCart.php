<?php
session_start();
if (isset($_POST['removeAll'])) {
    unset($_SESSION['cart']);
} else if (isset($_POST['remove'])) {
    unset($_SESSION['cart'][$_POST['remove']]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
    header("Location: ../cart.php");
}
