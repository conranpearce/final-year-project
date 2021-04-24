<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content="Log in system">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <title>Carbon Intensity</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital@1&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/reset.css">

</head>

<body>

<nav>
    <div class="wrapper">
        <a href="index.php"></a>

        <ul>
            <?php 
                if (isset($_SESSION["useruid"])) {
                    echo "<li><a href='includes/logout.inc.php'>Log out</a></li>";
                } 
            ?>
        </ul>
    </div>
</nav>

<div class="wrapper">