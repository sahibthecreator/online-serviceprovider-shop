<?php
include 'connect.php';
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header("Access-Control-Allow-Credentials: true");

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $packages = Query($conn, "SELECT * FROM Packages WHERE Id = ?", "s", $id);
} else
    $packages = Query($conn, "SELECT * FROM Packages WHERE ?
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
echo json_encode($packages);
