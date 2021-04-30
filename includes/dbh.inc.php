<?php

// Database connection

$serverName = "localhost";
$dBUsername = "root";
$dBPassword = "root";
$dBName = "digitalsystemsproject";

$conn = mysqli_connect($serverName, $dBUsername, $dBPassword, $dBName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}