<?php
    // Getting UUID to be used for getting the TP-Link token
    function getUUID() {
        $uuid = getCurlRequest("https://www.uuidtools.com/api/generate/v1/");
        $uuid = trim($uuid, '[');
        $uuid = trim($uuid, ']');
        $uuid = trim($uuid, '"');
        return $uuid;
    }

    // Get the current carbon intensity from the National Grid API
    function getCurrentCarbonIntensity() {
        $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/");
        $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);

        echo "<h2 class='header'>" . "Current carbon intensity</h2>";

        if ($carbonIntensityDecoded['data'][0]['intensity']['index'] == 'very low') {
            echo "<div class='intensity very-low'>";
        } else if ($carbonIntensityDecoded['data'][0]['intensity']['index'] == 'low') {
            echo "<div class='intensity low'>";
        } else if ($carbonIntensityDecoded['data'][0]['intensity']['index'] == 'moderate') {
            echo "<div class='intensity moderate'>";
        } else if ($carbonIntensityDecoded['data'][0]['intensity']['index'] == 'high') {
            echo "<div class='intensity high'>";
        } else if ($carbonIntensityDecoded['data'][0]['intensity']['index'] == 'very high') {
            echo "<div class='intensity very-high'>";
        } 
        echo "<h2>" . $carbonIntensityDecoded['data'][0]['intensity']['index'] . "</h2>";
        echo "<h2>" . $carbonIntensityDecoded['data'][0]['intensity']['forecast'] . " gCO2/KwH</h2>";
        echo "</div>";
    }

    // Get the current generation mix from the National Grid API
    function getCurrentGenerationMix() {
        $generationMixResponse = getCurlRequest("https://api.carbonintensity.org.uk/generation/");
        $generationMixDecoded = json_decode($generationMixResponse, true);
        # Display all of the generation sources in descending order of percentage
        $generationPerc = array();
        echo "<h2 class='header'>" . "Current generation mix of energy is:" . "</h2>";
        for ($x = 0; $x < sizeof($generationMixDecoded['data']['generationmix']); $x++) {
            array_push($generationPerc, $generationMixDecoded['data']['generationmix'][$x]['perc']);
        }
        rsort($generationPerc);
        $generationMix = array();
        for ($x = 0; $x < sizeof($generationPerc); $x++) {
            for ($y = 0; $y < sizeof($generationPerc); $y++) {
                if ($generationPerc[$x] == $generationMixDecoded['data']['generationmix'][$y]['perc']) {
                    array_push($generationMix, ['fuel' => $generationMixDecoded['data']['generationmix'][$y]['fuel'], 'perc' => $generationMixDecoded['data']['generationmix'][$y]['perc']]);
                }
            }
            echo "<h2> " . $generationMix[$x]['fuel'] . " " . $generationMix[$x]['perc'] . "% </h2>";
        }
    }

    // Getting the best carbon intensity period of time (lowest index) in the next 24 hours
    function getBestCarbonIntensity24hr($token, $currentDateTime) {
        // Return the forecast for the next 24 hours from the current time
        $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/". $currentDateTime. "/fw24h");
        $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);
        // Set a variable for the maximum value of carbon intensity index
        $lowestForecast = 600; 
        // Loop through and find the lowest forecasted index
        for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
            if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] < $lowestForecast) {
                // Set the lowest forecast
                $lowestForecast = $carbonIntensityDecoded['data'][$x]['intensity']['forecast'];
            }
        }
        // Find the best time by checking for the lowest forecast
        $bestCarbonIntensityTime = "";
        for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
            if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] == $lowestForecast) {
                $bestCarbonIntensityTime = $carbonIntensityDecoded['data'][$x];
            }
        }

        // split best carbon time
        $bestDate = explode('T', $bestCarbonIntensityTime['from']);
        $bestTime = explode('Z', $bestDate[1]);
        
        # Output to the user the lowest time and forecast
        echo "<h2 class='header'> Best forecasted time in 24hrs</h2>";
        echo "<h2> ". $bestTime[0] . " at " . $bestDate[0] . "</h2>";
        echo "<h2>" . $bestCarbonIntensityTime['intensity']['forecast'] . " gCO2/KwH</h2>";
        return $bestCarbonIntensityTime['from'];
    }
?>