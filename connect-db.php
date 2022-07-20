<?php
require_once realpath(__DIR__ . '/vendor/autoload.php');

// Retrive env variable
$userName = $_ENV['HOME'];

echo $userName; //Pretoria
//server info
$server = '';
$user = '';
$pass = '';
$db = '';

//connect to the database
$mysqli = new mysqli($server, $user, $pass, $db);

//show error (remove this line if on live site)
//mysqli_report(MYSQLI_REPORT_ERROR);
