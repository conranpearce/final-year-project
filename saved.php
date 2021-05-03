<?php
    include_once 'header.php';
?>
<section>
    <?php 
        if (isset($_SESSION["useruid"])) {
            $_LOGGED_IN = True; // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
        } else {
            header('Location: index.php');
            exit();
        }

        // Get the intensity saved
        if ($_LOGGED_IN == True) {
            $intensity = require('includes/get-carbon-intensity-saved.inc.php');   
        }           
        
        // Error handling for co2 intensity saved
        if(isset($_GET["error"])) {
            if ($_GET["error"] == "getco2") {
                echo "<script type='text/javascript'>alert('Error getting CO2 intensity from database');</script>";
            }
        } 
    ?>

    <!-- Set chart in HTML -->
    <div class="chart wrapper">
        <canvas id="co2Chart"></canvas>
	</div>

    <!-- Chart js include -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.js"></script>
    
    <script>
        // Get the intensity value from php
        var intensity = <?php echo json_encode($intensity); ?>;
        // Set arrays to store intensity 
        var intensityUsed = []
        var intensitySaved = []
        var intensityId = []
        for(var i=0; i<intensity.length; i++){
            intensityUsed.push(intensity[i]['co2Used']);
            intensitySaved.push(intensity[i]['co2Saved']);
            // intensityId.push(intensity[i]['carbonIntensityId']);
            intensityId.push(i+1);
        }
        // Access the chart in HTML
        var ctx = document.getElementById('co2Chart').getContext('2d');
        var chart = new Chart(ctx, {
            // Chart type
            type: 'line',
            // Data for graph
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
            // Configuration option
            options: {
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
                            labelString: 'gCO2/kWh',
                            fontSize: 14
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Scheduling iteration',
                            fontSize: 14
                        }
                    }]
                },
                title: {
                    display: true,
                    text: 'Comparison of CO2 intensity used when scheduling against CO2 intensity at time of scheduling.',
                    fontSize: 15,
                    fontColor: '#111111'
                }
            }
        });


    </script>

</section>

<?php
    include_once 'footer.php';
?>