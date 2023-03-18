<?php
session_start();
$errormsg = "";
if (isset($_SESSION["logedin"])) {
    header("Location: index.php");
}
if (isset($_POST["login"])) {
    if ($_POST["username"] == "root" && $_POST["pass"] == "HiRoot") {
        $_SESSION["logedin"] = uniqid();
        header("Location: index.php");
        $errormsg = "Login fasssiled";
    } else {
        $errormsg = "Login failed";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="../css/styleCMS.css?v=<?= time() ?>">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <link rel="shortcut icon" href="https://ru.boostyouraccount.com/icon.ico">

</head>

<body id="auth">
    <div class="center-text">
        <p>BoostYourAccount</p>
    </div>
    <!-- <div class="login-container">
        <img src="../img/face-with-peeking-eye.webp" />
        <form method="post" action="">
            <div class="inputs">
                <input type="text" name="username" placeholder="username">
                <input type="password" name="pass" placeholder="password">
            </div>
            <button type="submit" name="login">
                Login
            </button>
            <p class="error-message"><?= $errormsg ?></p>
        </form>
    </div> -->
    <div class="login-box">
        <h2>Login</h2>
        <form method="post" action="">
            <div class="user-box">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="user-box">
                <input type="password" name="pass" required>
                <label>Password</label>
            </div>
            <button type="submit" name="login">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                Login
            </button>
            <p class="error-message"><?= $errormsg ?></p>
        </form>
    </div>

</body>

</html>