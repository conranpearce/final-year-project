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
            $intensity = require('includes/get-carbon-intensity-saved.inc.php');   
        }            
    ?>

    <div class="chart wrapper">
        <canvas id="myChart"></canvas>
	</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    
    <script>

        var intensity = <?php echo json_encode($intensity); ?>;

        var intensityUsed = []
        var intensitySaved = []
        var intensityId = []

        for(var i=0; i<intensity.length; i++){
            console.log(intensity[i]);
            intensityUsed.push(intensity[i]['co2Used']);
            intensitySaved.push(intensity[i]['co2Saved']);
            intensityId.push(intensity[i]['carbonIntensityId']);
        }

        console.log("intensityUsed ", intensityUsed);
        console.log("intensitySaved ", intensitySaved);
        console.log("intensityId ", intensityId);


        var ctx = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',
            title: 'Title',
            // The data for our dataset
            data: {
                labels: intensityId,
                datasets: [{
                    label: "CO2 used",
                    borderColor: '#258349',
                    fill: false,
                    data: intensityUsed,
                },
                {
                    label: "CO2 at time of scheduling",
                    borderColor: '#a7332b',
                    fill: false,
                    data: intensitySaved,
                }]
            },
            // Configuration options go here
            options: {
                scales: {
                    xAxes: [{
                        ticks: {
                            display: false
                        }
                    }]
                },
                elements: {
                    line: {
                    tension: 0
                    }
                },
                legend: {
                    onClick: null
                },
                scales: {
                    yAxes: [{
                    scaleLabel: {
                        display: true,
                        labelString: 'gCO2/kWh'
                    }
                    }]
                } 
            }
        });


    </script>

</section>