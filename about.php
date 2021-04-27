<?php
    include_once 'header.php';
?>
<section>
    <script src="https://kit.fontawesome.com/f8e9bf29fb.js" crossorigin="anonymous"></script>

    <?php 
        if (isset($_SESSION["useruid"])) {
            $_LOGGED_IN = True; // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
        } else {
            header('Location: index.php');
            exit();
        }

        if ($_LOGGED_IN == True) {
            echo "<div class='about'>
                    <p>This application manages and schedules  TP-Link smart devices, to help reduce an individual's local carbon impact.</p>
                    <p>The <a class='link' href='http://carbonintensity.org.uk/' target='_blank'>National Grid's API</a> has been used to provide carbon intensity related data, updating every 30 minutes.</p>
                    <p>TP-Link smart plugs and bulbs are able to be turned on/off through the application.</p>
                    <p>Smart plugs are able to scheduled to turn on in the next 24 hours, when there is the lowest CO2 impact.</p>
                    <p>Carbon intensity is measured by grams of CO2 to create a unit of electricity a kilowatt per hour (gCO2/kWh).</p>
                    <a class='link' href='https://github.com/conranpearce' target='_blank'><i class='fab fa-github fa-4x icon'></i></a>
                </div>";
        }            
    ?>
</section>