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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<!-- Header to be displayed, including navigation bar -->
<div class="nav wrapper" id="navigation-bar">
    <?php 
        if (isset($_SESSION["useruid"])) {
            echo "<a href='index.php'>Home</a>";
            echo "<a href='about.php'>About</a>";
            echo "<a href='saved.php'>CO2 Saved</a>";
            echo "<a href='generation.php'>Generation Mix</a>";
            echo "<a href='includes/logout.inc.php'>Log out</a>";
            echo "<a href='javascript:void(0);' class='menu-icon' onclick='menuInteraction()'>
                    <div class='hamburger-icon'></div>
                    <div class='hamburger-icon'></div>
                    <div class='hamburger-icon'></div>
                </a>";
        } else if (($_SERVER['REQUEST_URI'] == '/digital-systems-project/login.php') || ($_SERVER['REQUEST_URI'] == '/digital-systems-project/signup.php')) {
            echo "<a href='index.php'>Home</a>";
        }
            
    ?>
</div>

<script>
    // Interaction with the navigation bar
    function menuInteraction() {
        var x = document.getElementById("navigation-bar");
        if (x.className === "nav wrapper") {
            x.className += " responsive wrapper";
        } else {
            x.className = "nav wrapper";
        }
    }

</script>

<div class="wrapper">