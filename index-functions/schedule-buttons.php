<?php
    // Set the buttons to schedule appliances 
    function setScheduleButtons($token, $deviceListDecoded, $deviceCount, $devices) {
        echo "<h2>Schedule on/off:</h2>";
        // For all the devices
        for ($x = 0; $x < $deviceCount; $x++) {
            $deviceName = str_replace(' ', '', strtolower($deviceListDecoded['result']['deviceList'][$x]['alias']));
            $deviceID = $deviceListDecoded['result']['deviceList'][$x]['deviceId'];
            // Find if the devices are already scheduled
            $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n\n }\n}";
            $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
            $relayStateDecoded = json_decode($relayStateResponse, true);
            $dataResponse = json_encode($relayStateDecoded["result"]["responseData"]);
            // If a smart plug then add a button
            if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTPLUGSWITCH') !== false) {    
                echo "<p>" . $deviceListDecoded['result']['deviceList'][$x]['alias'] . "</p>";  
                $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"schedule\\\":{\\\"get_rules\\\":null}}\"\n\n }\n}";
                $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
                // CHANGE THIS TO DISPLAY WHEN SCHEDULED!!! **
                echo "<p> relay state response " . $relayStateResponse . "</p>"; 

                if ($relayStateDecoded["msg"] == "Device is offline") {
                    echo "<p> Device is offline </p>";
                    // Set a button that is not clickable to indicate the device is offline
                    $checkboxType = " disabled='disabled' onclick='smartPlugScheduleClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else if (stripos($relayStateResponse, 'rule_list\":[]') !== false) { // No schedule for device
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 0]); // Set a clickable button (but not checked already)
                    $checkboxType = " onclick='smartPlugScheduleClick(this, this.id);";
                    setDeviceButton($deviceName, $checkboxType);
                } else { // Device is scheduled
                    array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 1]);
                    $checkboxType = " checked onclick='smartPlugScheduleClick(this, this.id);"; // Set a clickable checked button
                    setDeviceButton($deviceName, $checkboxType);
                }
            }
        }
        // Convert array into json
        $devices_encoded = json_encode($devices);
        return $devices_encoded;
    }
?>