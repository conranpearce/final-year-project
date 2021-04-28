<?php
    // Time conversion into hh:mm format
    function timeConversion($time, $format = '%02d:%02d') {
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    // Split time scheduled string
    function findTimeScheduled($relayStateResponse) {
        $time = explode('smin\":', $relayStateResponse);
        $time = explode(',', $time[1]);
        return timeConversion($time[0]);
    }

    // Find day scheduled from response
    function findDayScheduled($relayStateResponse) {
        if (stripos($relayStateResponse, 'wday\":[1,0,0,0,0,0,0]') !== false) {
            return "Sunday";
        } else if (stripos($relayStateResponse, 'wday\":[0,1,0,0,0,0,0]') !== false) {
            return "Monday";
        } else if (stripos($relayStateResponse, 'wday\":[0,0,1,0,0,0,0]') !== false) {
            return "Tuesday";
        } else if (stripos($relayStateResponse, 'wday\":[0,0,0,1,0,0,0]') !== false) {
            return "Wednesday";
        } else if (stripos($relayStateResponse, 'wday\":[0,0,0,0,1,0,0]') !== false) {
            return "Thursday";
        } else if (stripos($relayStateResponse, 'wday\":[0,0,0,0,0,1,0]') !== false) {
            return "Friday";
        } else if (stripos($relayStateResponse, 'wday\":[0,0,0,0,0,0,1]') !== false) {
            return "Saturday";
        }
    }

    // Set the buttons to schedule appliances 
    function setScheduleButtons($token, $deviceListDecoded, $deviceCount, $devices) {
        echo "<p class='header'>Schedule on/off:</p>";

        echo "<div class='grid-container'>";
        // For all the devices
        for ($x = 0; $x < $deviceCount; $x++) {
            $deviceName = str_replace(' ', '', strtolower($deviceListDecoded['result']['deviceList'][$x]['alias']));
            $deviceID = $deviceListDecoded['result']['deviceList'][$x]['deviceId'];
            // Set id for buttons, based on the device name
            $idname = strtolower(str_replace(' ', '', $deviceName));
            $idname = $idname . '-button';
            // Find if the devices are already scheduled
            $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n\n }\n}";
            $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
            $relayStateDecoded = json_decode($relayStateResponse, true);
            $dataResponse = json_encode($relayStateDecoded["result"]["responseData"]);
            // If a smart plug then add a button
            if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTPLUGSWITCH') !== false) {    
                echo "<div class='grid-item'>";
                echo "<p>" . $deviceListDecoded['result']['deviceList'][$x]['alias'] . "</p>";  
                $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"schedule\\\":{\\\"get_rules\\\":null}}\"\n\n }\n}";
                $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
                if ($relayStateDecoded["msg"] == "Device is offline") {
                    echo "<p> Device is offline </p>";
                    // Set a button that is not clickable to indicate the device is offline
                    $checkboxType = " disabled='disabled' onclick='smartPlugScheduleClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if (stripos($relayStateResponse, 'rule_list\":[]') !== false) { // No schedule for device
                    echo "<p id='". $idname ."'> Device is not scheduled </p>";
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 0]); // Set a clickable button (but not checked already)
                    $checkboxType = " onclick='smartPlugScheduleClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else { // Device is scheduled
                    echo "<p id='". $idname ."'> Scheduled  for " .  findTimeScheduled($relayStateResponse) . " " . findDayScheduled($relayStateResponse) . "</p>";
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 1]);
                    $checkboxType = " checked onclick='smartPlugScheduleClick(this, this.id);"; // Set a clickable checked button
                    setDeviceButton($deviceName, $checkboxType);
                }
                echo "</div>";
            }
        }
        echo "</div>";

        // Convert array into json
        $devices_encoded = json_encode($devices);
        return $devices_encoded;
    }
?>