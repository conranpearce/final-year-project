<?php
    include_once 'header.php';
?>
<section>
    <!-- <script src="https://kit.fontawesome.com/f8e9bf29fb.js" crossorigin="anonymous"></script> -->

    <?php 
        if (isset($_SESSION["useruid"])) {
            $_LOGGED_IN = True; // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
        } else {
            header('Location: index.php');
            exit();
        }

        if ($_LOGGED_IN == True) {
            echo "<div'>
                    <p>Graphs</p>
                </div>";
            
            // $intensity = include('includes/get-carbon-intensity-saved.inc.php');
            $intensity = require('includes/get-carbon-intensity-saved.inc.php');

            for ($x = 0; $x < count($intensity); $x++) {

                echo "<p> co2Used " . $intensity[$x]['co2Used'] . "</p>";
                echo "<p> co2Saved " . $intensity[$x]['co2Saved'] . "</p>";
            }

           
        }            
    ?>

    <div>
        <canvas id="myChart"></canvas>
    </div>

    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',

            // The data for our dataset
            data: {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "My First dataset",
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: [0, 10, 5, 2, 20, 30, 45],
                }]
            },

            // Configuration options go here
            options: {}
        });

    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

</section>