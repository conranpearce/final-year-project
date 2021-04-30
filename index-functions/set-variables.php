<?php
    # Setting the device button on the website based on the device name and if the button is clickable or not
    function setDeviceButton($deviceName, $checkboxType) {
        echo "<label class='switch'>
            <input type='checkbox' id='" . $deviceName . "' ". $checkboxType ."'>
            <span class='slider round'></span>
        </label>";
    }

    # Return the weekday (as an array) to set the schedule to
    function setDaySchedule($day, $token, $minutes) {
        # The TP-Link API sets the day on the schedule to turn on, find the correct day within the next 24 hours to turn the smart device on
        $dateBestDay = explode("T", $day);
        $bestTime = strtotime($dateBestDay[0]);
        $dayFormatted = date('w', $bestTime);
        if ($dayFormatted == 0) { // Sunday
            $bestDayFormatted = "[1,0,0,0,0,0,0]";
        } else if ($dayFormatted == 1){ // Monday
            $bestDayFormatted = "[0,1,0,0,0,0,0]";
        } else if ($dayFormatted == 2){ // Tuesday
            $bestDayFormatted = "[0,0,1,0,0,0,0]";
        } else if ($dayFormatted == 3){ // Wednesday
            $bestDayFormatted = "[0,0,0,1,0,0,0]";
        } else if ($dayFormatted == 4){ // Thursday
            $bestDayFormatted = "[0,0,0,0,1,0,0]";
        } else if ($dayFormatted == 5) { // Friday
            $bestDayFormatted = "[0,0,0,0,0,1,0]";
        } else if ($dayFormatted == 6) { // Saturday
            $bestDayFormatted = "[0,0,0,0,0,0,1]";
        }
        return $bestDayFormatted;
    }

    # Set th best interval of the day (in minutes) of carbon intensity in the next 24 hours
    function setMinutes($token, $bestDay) {
        $time = $bestDay;
        $timeExplode = explode(":", $time);
        $bestHour = ($timeExplode[0][sizeof($timeExplode[0]) - 3] * 10) +  $timeExplode[0][sizeof($timeExplode[0]) -2];
        $bestMinute = ($timeExplode[1][0] * 10) +  $timeExplode[1][1];
        $minutes = ($bestHour * 60) + $bestMinute;
        return $minutes;
    }
?>