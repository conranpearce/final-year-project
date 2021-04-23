<?php
    // Getting UUID to be used for getting the TP-Link token
    function getUUID() {
        $uuid4 = getCurlRequest("https://www.uuidgenerator.net/api/version4");
        return $uuid4;
    }

    // Get the current carbon intensity from the National Grid API
    function getCurrentCarbonIntensity() {
        $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/");
        $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);
        echo "<h2>" . "Current carbon intensity is: " . $carbonIntensityDecoded['data'][0]['intensity']['index'] . "</h2>";
    }

    // Get the current generation mix from the National Grid API
    function getCurrentGenerationMix() {
        $generationMixResponse = getCurlRequest("https://api.carbonintensity.org.uk/generation/");
        $generationMixDecoded = json_decode($generationMixResponse, true);
        # Display all of the generation percentages
        echo "<h2>" . "Current generation mix of energy is:" . "</h2>";
        for ($x = 0; $x < sizeof($generationMixDecoded['data']['generationmix']); $x++) {
            echo "<h2>" . $generationMixDecoded['data']['generationmix'][$x]['fuel'] . " " . $generationMixDecoded['data']['generationmix'][$x]['perc'] . "%" . "</h2>";
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
        # Output to the user the lowest time and forecast
        echo "<h2>" . "Best forecast time is " . $bestCarbonIntensityTime['from'] . "</h2>";
        echo "<h2>" . "Best forecast index is " . $bestCarbonIntensityTime['intensity']['forecast'] . " CO2/KwH" . "</h2>";
        return $bestCarbonIntensityTime['from'];
    }
?>