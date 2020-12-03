<?php
    include_once 'header.php';
?>
    <section class="index-intro">

        <h1>This is an introduction</h1>

        <?php 
            // Global variable
            $_LOGGED_IN = False;

            if (isset($_SESSION["useruid"])) {
                echo "<p>Hello there " . $_SESSION["useruid"] . "</p>";

                // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
                $_LOGGED_IN = True;
            } 

            # Return the weekday (as an array) to set the schedule to
            function setDaySchedule($day, $token, $minutes) {

                $dateBestDay = explode("T", $day);

                echo "<h2>" . "Best day date is " . $dateBestDay[0] . "</h2>";


                $timestamp = strtotime($dateBestDay[0]);
                $dayFormatted = date('w', $timestamp);

                echo "<h2>" . "Day 0-6: " . $dayFormatted . "</h2>";

                // $weekArr = [0,0,0,0,0,0,0];

                # Sunday
                if ($dayFormatted == 0) {
                    // $weekArr[0] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[1,0,0,0,0,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Monday
                else if ($dayFormatted == 1){
                    // $weekArr[1] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,1,0,0,0,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Tuesday
                else if ($dayFormatted == 2){
                    // $weekArr[2] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,0,1,0,0,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Wednesday
                else if ($dayFormatted == 3){
                    // $weekArr[3] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,0,0,1,0,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Thursday
                else if ($dayFormatted == 4){
                    // $weekArr[4] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,0,0,0,1,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Friday
                else if ($dayFormatted == 5) {
                    // $weekArr[5] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,0,0,0,0,1,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                }
                # Saturday
                else if ($dayFormatted == 6) {
                    // $weekArr[6] = 1;
                    $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[0,0,0,0,0,0,1],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';

                }
            
                return $fieldPost;
            }

            # Schedule the best time in minutes for the plug to turn on
            function setSchedule($token, $minutes, $bestDay) {

                # Pass the best day to set which day to set the schedule on to
                $fieldPost = setDaySchedule($bestDay, $token, $minutes);


                
                # Set week day and no repeat
                # Get the date and check which day it is
                # set wday equal to 1 on the day to turn on
                # repeat 0

                # get current date, if date is different then means the next day


                # Pass in variable correctly

                // $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[1,0,0,0,0,0,0],\\"smin\\":1380,\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                // $fieldPost = '{"method": "passthrough","params": {"deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC","token": "'.$token.'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[1,0,0,0,0,0,0],\\"smin\\":'.$minutes.',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';

                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://wap.tplinkcloud.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $fieldPost,
                // CURLOPT_POSTFIELDS =>'{
                // "method": "passthrough",
                // "params": {
                // "deviceId": "800675856DF78F73B410C3FB4DF41B8B1D01F6DC",
                // "token": "3dceff19-BT8mPTHfX4no2Skne3cGzPN",
                // "requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":[1,0,0,0,0,0,0],\\"smin\\":1380,\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"
                // }
                // }
                // ',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: text/plain'
                ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                echo $response;
            }

            // Basic template for a POST request
            function postCurlRequest($url, $postfields) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $postfields,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: text/plain"
                    ),
                ));
                $resp = curl_exec($curl);
                curl_close($curl);
                return $resp;
            }

            // Basic template for a GET request
            function getCurlRequest($url) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ));
                $resp = curl_exec($curl);
                curl_close($curl);
                return $resp;
            }
        
            // Getting current carbon intensity
            function getCurrentCarbonIntensity() {
                $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/");
                $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);
                echo "<h2>" . "Current carbon intensity is: " . $carbonIntensityDecoded['data'][0]['intensity']['index'] . "</h2>";
            }

            // Getting current carbon intensity
            function getBestCarbonIntensity24hr($token) {

                $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";

                echo "<h2>" . "Current date and time forecast for API is " . $currentDateTime . "</h2>";


                $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/". $currentDateTime. "/fw24h");
                // $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/2020-12-02T17:35Z/fw24h");
                $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);
                // echo "<h2>" . "Current carbon intensity is: " . $carbonIntensityDecoded['data'][0]['intensity']['index'] . "</h2>";

                $lowestForecast = 600; # Not sure if this is correct highest value!!!!!!

                // echo "<h2>" . "Array size " . sizeof($carbonIntensityDecoded['data']) . "</h2>";

                for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
                    if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] < $lowestForecast) {
                        $lowestForecast = $carbonIntensityDecoded['data'][$x]['intensity']['forecast'];
                    }
                }

                // return the best time
                $bestCarbonIntensityTime = "";

                for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
                    if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] == $lowestForecast) {
                        $bestCarbonIntensityTime = $carbonIntensityDecoded['data'][$x];
                    }
                }
                echo "<h2>" . "Best forecast time is " . $bestCarbonIntensityTime['from'] . "</h2>";
                echo "<h2>" . "Best forecast index is " . $bestCarbonIntensityTime['intensity']['forecast'] . "</h2>";

                # NEED TO THEN CONVERT THIS BEST TIME INTO MINUTES AND ADD TO SCHEDULE
                # Find out day of the forecast best time
                # Set repeat to off
                # Set name

                $time = $bestCarbonIntensityTime['from'];
                

                $timeExplode = explode(":", $time);

                # Check best hour logic here!!!!!!!
                // echo "<h2>" . "timeExplode[0][sizeof(timeExplode[0]):   " . $timeExplode[0][sizeof($timeExplode[0]) - 3] . "</h2>";

                $bestHour = ($timeExplode[0][sizeof($timeExplode[0]) - 3] * 10) +  $timeExplode[0][sizeof($timeExplode[0]) -2];
                $bestMinute = $timeExplode[1][0] +  $timeExplode[1][1];

                echo "<h2>" . "Best hour is:  " . $bestHour . "</h2>";
                echo "<h2>" . "Best minute is:  " . $bestMinute . "</h2>";

                $bestTimeInMinutes = ($bestHour * 60) + $bestMinute;

                echo "<h2>" . "Best time in minutes is:  " . $bestTimeInMinutes . "</h2>";

                

                setSchedule($token, $bestTimeInMinutes, $bestCarbonIntensityTime['from']);




            }

            // Getting UUID
            function getUUID() {
                $uuid4 = getCurlRequest("https://www.uuidgenerator.net/api/version4");
                return $uuid4;
            }

            // Get token from TP-Link using log in credentials
            function getToken($uuid) {
                $fieldPost = "{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"***\",\n \"cloudPassword\": \"***\",\n \"terminalUUID\": \"$uuid\"\n }\n}";
                $tokenResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
                $tokenDecoded = json_decode($tokenResponse, true);
                return $tokenDecoded['result']['token'];
            }

            // Get device list using token generated from before
            function getDeviceList($token) {
                $fieldPost = "{\n \"method\": \"getDeviceList\",\n \"params\": {\n \"token\": \"$token\"\n }\n}";
                $deviceListResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);

                // echo "<p> DEVICE LiST " . $deviceListResponse . " END DEVICE LIST</p>";
                return json_decode($deviceListResponse, true);
            }

            // Setting the device button on the website based on the device name and if the button is clickable or not
            function setDeviceButton($deviceName, $checkboxType) {
                echo "<label class='switch'>
                    <input type='checkbox' id='" . $deviceName . "' ". $checkboxType ."'>
                    <span class='slider round'></span>
                </label>";
            }
            
            // If the user is logged in then carry out this code
            if ($_LOGGED_IN == True) {
                getCurrentCarbonIntensity();

                $uuid = getUUID();
                $token = getToken($uuid);
                $deviceListDecoded = getDeviceList($token);
                $deviceCount = count($deviceListDecoded['result']['deviceList']);
                $devices = array();

                // For all the devices
                for ($x = 0; $x < $deviceCount; $x++) {
                    echo "<p>" . $deviceListDecoded['result']['deviceList'][$x]['alias'] . "</p>";

                    // echo "<p>" . json_decode($deviceListDecoded) . "</p>";

                    $deviceName = str_replace(' ', '', strtolower($deviceListDecoded['result']['deviceList'][$x]['alias']));
                    $deviceID = $deviceListDecoded['result']['deviceList'][$x]['deviceId'];

                    $fieldPost = "{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n\n }\n}";
                    $relayStateResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
                    $relayStateDecoded = json_decode($relayStateResponse, true);

                    echo "relayState Decoded " . json_encode($relayStateDecoded);

                    $dataResponse = json_encode($relayStateDecoded["result"]["responseData"]);

                    // If a smart plug then add a button
                    if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTPLUGSWITCH') !== false) {      
                        echo "<p> Smart plug </p>";
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
                    } else if (stripos(json_encode($deviceListDecoded['result']['deviceList'][$x]['deviceType']), 'IOT.SMARTBULB') !== false) {      
                        echo "<p> Smart bulb </p>";

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

                    # Get carbon intensity
                    getBestCarbonIntensity24hr($token);
                }
                // convert array into json
                $devices_json = json_encode($devices);
                echo $devices_json;
            }
        ?>

        <!-- Testing calling javascript -->
        <script>

            // Async function getting the current carbon intensity from the national grid API
            async function getCarbonIntensity() {
                var requestOptions = {
                    method: 'GET',
                    redirect: 'follow'
                };
                let response = await fetch("https://api.carbonintensity.org.uk/intensity/", requestOptions);
                // only proceed once promise is resolved
                let data = await response.json();
                // only proceed once second promise is resolved
                return data;
            }

            // async function to return the fetch API which retrieves the state (relay_state) of the device selected by the user
            async function callTplinkAPI(deviceObject) {
                var myHeaders = new Headers();
                myHeaders.append("Content-Type", "text/plain");
                var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObject['userToken']}\",\n \"deviceId\": \"${deviceObject['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n }\n}`;
                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                return (await (await fetch("https://wap.tplinkcloud.com", requestOptions)).json());
            }

            async function smartPlugClick(cb, id) {
                console.log(cb.checked);
                console.log(id);

                var deviceObj = JSON.parse('<?= $devices_json; ?>');

                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        console.log("Selected is ", deviceObj[i]);

                        let tpLinkReturn = [];

                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturn = await callTplinkAPI(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }

                        // console.log("tpLinkReturn ", tpLinkReturn);
                        deviceReturnResponse = tpLinkReturn["result"]["responseData"]

                        if (deviceReturnResponse.includes('relay_state":0')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":1}}}\"\n }\n}`;
                            console.log("The device is now on");
                        } else if (deviceReturnResponse.includes('relay_state":1')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":0}}}\"\n }\n}`;
                            console.log("The device is now off");
                        }

                        var myHeaders = new Headers();
                        myHeaders.append("Content-Type", "text/plain");

                        var requestOptions = {
                            method: 'POST',
                            headers: myHeaders,
                            body: raw,
                            redirect: 'follow'
                        };

                        fetch("https://wap.tplinkcloud.com", requestOptions)
                            .then(response => response.text())
                            .then(result => console.log(result))
                            .catch(error => console.log('error', error));
                    }
                }
            }

            async function smartBulbClick(cb, id) {
                console.log(cb.checked);
                console.log(id);

                var deviceObj = JSON.parse('<?= $devices_json; ?>');

                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        console.log("Selected is ", deviceObj[i]);

                        let tpLinkReturn = [];

                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturn = await callTplinkAPI(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }

                        // console.log("tpLinkReturn ", tpLinkReturn);
                        deviceReturnResponse = tpLinkReturn["result"]["responseData"]

                        console.log("deviceReturnResponse ", deviceReturnResponse);

                        if (deviceReturnResponse.includes('on_off\":0')) {
                            console.log("BULB IS OFF");
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":1,\\\"transition_period\\\":1000}}}\"\n }\n}`;
                            console.log("The device is now on");
                        } else if (deviceReturnResponse.includes('on_off\":1')) {
                            console.log("BULB IS ON");
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":0,\\\"transition_period\\\":1000}}}\"\n }\n}`;
                            console.log("The device is now off");
                        }

                        var myHeaders = new Headers();
                        myHeaders.append("Content-Type", "text/plain");

                        var requestOptions = {
                            method: 'POST',
                            headers: myHeaders,
                            body: raw,
                            redirect: 'follow'
                        };

                        fetch("https://wap.tplinkcloud.com", requestOptions)
                            .then(response => response.text())
                            .then(result => console.log(result))
                            .catch(error => console.log('error', error));
                    }
                }
            }

        </script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

        <!-- Calling javascript function above-->
        <script type="text/javascript"> 
            // Printing the result of the current carbon intensity
            getCarbonIntensity()
                .then(data => console.log("DATA ", data))
                .catch(reason => console.log(reason.message))
        </script>

    </section>

<script src="js/checkbox.js"></script>

<?php
    include_once 'footer.php';
?>