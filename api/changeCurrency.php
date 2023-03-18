<?php
session_start();
$data = file_get_contents('php://input');
$data = json_decode($data);

$_SESSION['currency'] = $data->currency;
