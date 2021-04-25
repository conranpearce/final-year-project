<?php
    // Set the buttons to turn the smart devices on and off
    function setOnOffButtons($token, $deviceListDecoded, $deviceCount, $devices) {
        echo "<p class='header'>Turn on/off:</p>";
        echo "<div class='grid-container'>";
        // For all the devices
        for ($x = 0; $x < $deviceCount; $x++) {
            echo "<div class='grid-item'>";
            echo "<p>" . $deviceListDecoded['result']['deviceList'][$x]['alias'] . "</p>";
            $deviceName = str_replace(' ', '', strtolower($deviceListDecoded['result']['deviceList'][$x]['alias']));
            $deviceID = $deviceListDecoded['result']['deviceList'][$x]['deviceId'];
            // Finding state of the buttons
            $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n\n }\n}";
            $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
            $relayStateDecoded = json_decode($relayStateResponse, true);
            $dataResponse = json_encode($relayStateDecoded["result"]["responseData"]);
            // If a smart plug then add a button
            if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTPLUGSWITCH') !== false) {      
                // Setting button for device on the website
                if (stripos($dataResponse, 'relay_state\":0') !== false) { // If contains relay_state being 0 in the response
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 0]);
                    // Set a clickable button (but not checked already)
                    $checkboxType = " onclick='smartPlugClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if (stripos($dataResponse, 'relay_state\":1') !== false) {                    
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 1]);
                    // Set a clickable checked button
                    $checkboxType = " checked onclick='smartPlugClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if ($relayStateDecoded["msg"] == "Device is offline") {
                    echo "<p> Device is offline </p>";
                    // Set a button that is not clickable to indicate the device is offline
                    $checkboxType = " disabled='disabled' onclick='smartPlugClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                }
            // If it is a smart bulb then carry out these requests
            } else if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTBULB') !== false) {      
                // Setting button for device on the website
                if (stripos($dataResponse, 'on_off\":0') !== false) { // If contains relay_state being 0 in the response
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 0]);
                    // Set a clickable button (but not checked already)
                    $checkboxType = " onclick='smartBulbClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if (stripos($dataResponse, 'on_off\":1') !== false) {                    
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 1]);
                    // Set a clickable checked button
                    $checkboxType = " checked onclick='smartBulbClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if ($relayStateDecoded["msg"] == "Device is offline") {
                    echo "<p> Device is offline </p>";
                    // Set a button that is not clickable to indicate the device is offline
                    $checkboxType = " disabled='disabled' onclick='smartBulbClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                }
            }
            echo "</div>";
        }
        echo "</div>";
        // convert array into json
        $devices_encoded = json_encode($devices);
        return $devices_encoded;
    }
?>