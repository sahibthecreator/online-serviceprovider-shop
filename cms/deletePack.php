<?php 
include "../api/connect.php";
if(!empty($_GET["id"])){
    $id = $_GET["id"];
    Query($conn, "DELETE FROM `Packages` WHERE `Packages`.`Id` = ?", "i", $id);
    header("Location: packages.php");
}
