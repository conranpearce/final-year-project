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
                echo "<p>start time " . $_SESSION['start_time'] . "</p>";
            } 

            // Check if the argument logout is passed from JavaScript via AJAX 
            if ($_GET["argument"]=='logout'){
                $_LOGGED_IN = False;
                session_start();
                session_unset();
                session_destroy();
                header("location: index.php");
                exit();
                echo "success";
            } else {
                echo "error";
            }

            # Return the weekday (as an array) to set the schedule to
            function setDaySchedule($day, $token, $minutes) {
                # The TP-Link API sets the day on the schedule to turn on, find the correct day within the next 24 hours to turn the smart device on
                $dateBestDay = explode("T", $day);
                $bestTime = strtotime($dateBestDay[0]);
                $dayFormatted = date('w', $bestTime);

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
                # Return the field post to then be set in the API
                return $fieldPost;
            }

            # Schedule the best time in minutes for the plug to turn on
            function setSchedule($token, $minutes, $bestDay) {

                # Pass the best day to set which day to set the schedule on to
                $fieldPost = setDaySchedule($bestDay, $token, $minutes);

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

            // Getting the best carbon intensity period of time (lowest index) in the next 24 hours
            function getBestCarbonIntensity24hr($token) {
                # Get the current time and date to pass into the national grid API
                $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";

                echo "<h2>" . "Current date and time forecast for API is " . $currentDateTime . "</h2>";

                # Return the forecast for the next 24 hours from the current time
                $carbonIntensityResponse = getCurlRequest("https://api.carbonintensity.org.uk/intensity/". $currentDateTime. "/fw24h");
                $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);

                # Set a variable for the maximum value of carbon intensity index
                $lowestForecast = 600; # Not sure if this is correct highest value!!!!!!
                # Loop through and find the lowest forecasted index
                for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
                    if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] < $lowestForecast) {
                        $lowestForecast = $carbonIntensityDecoded['data'][$x]['intensity']['forecast'];
                    }
                }

                # Find the best time by checking for the lowest forecast
                $bestCarbonIntensityTime = "";
                for ($x = 0; $x < sizeof($carbonIntensityDecoded['data']); $x++) {
                    if ($carbonIntensityDecoded['data'][$x]['intensity']['forecast'] == $lowestForecast) {
                        $bestCarbonIntensityTime = $carbonIntensityDecoded['data'][$x];
                    }
                }

                # Output to the user the lowest time and forecast
                echo "<h2>" . "Best forecast time is " . $bestCarbonIntensityTime['from'] . "</h2>";
                echo "<h2>" . "Best forecast index is " . $bestCarbonIntensityTime['intensity']['forecast'] . "</h2>";

                # Get the hour and minutes of the best time
                $time = $bestCarbonIntensityTime['from'];
                $timeExplode = explode(":", $time);
                $bestHour = ($timeExplode[0][sizeof($timeExplode[0]) - 3] * 10) +  $timeExplode[0][sizeof($timeExplode[0]) -2];
                $bestMinute = ($timeExplode[1][0] * 10) +  $timeExplode[1][1];
                $bestTimeInMinutes = ($bestHour * 60) + $bestMinute;
                # Pass the best time in hour and minutes to then be set using the TP-Link API
                setSchedule($token, $bestTimeInMinutes, $bestCarbonIntensityTime['from']);
            }

            // Getting UUID
            function getUUID() {
                $uuid4 = getCurlRequest("https://www.uuidgenerator.net/api/version4");
                return $uuid4;
            }

            // Get token from TP-Link using log in credentials
            function getToken($uuid) {
                $fieldPost = "{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"conranpearce@hotmail.com\",\n \"cloudPassword\": \"CamerasAreWatching111!\",\n \"terminalUUID\": \"$uuid\"\n }\n}";
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

                    // echo "relayState Decoded " . json_encode($relayStateDecoded);

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
                }
                // convert array into json
                $devices_json = json_encode($devices);
                echo $devices_json;

                # Get carbon intensity period in the next 24 hours. Then set the device (manually inputted at the moment to turn on/schedule at the best time)
                getBestCarbonIntensity24hr($token);
            }
        ?>

        <!-- Testing calling javascript -->
        <script>
            
            // When the page loads, check for inactivity
            window.onload = function() {
                // Check if a user is logged in
                if (<?php echo $_LOGGED_IN ?> == 1) {
                    inactivity();        
                }
            }
            
            // Check if the user is not carrying out any of these DOM events
            var inactivity = function () {
                var time;
                window.onload = resetTimer;
                window.onmousemove = resetTimer;
                window.onkeypress = resetTimer;
                window.ontouchstart = resetTimer; 
                window.onclick = resetTimer;      
                window.onkeydown = resetTimer;   
                window.addEventListener('scroll', resetTimer, true);

                // Log the user out if there are no DOM events, using AJAX
                function logout() {
                    $.ajax({
                        url: "http://localhost:8888/login-system/index.php?argument=logout", // Pass the logout argument to index.php
                        success: function(data) {
                            window.location.href = "login.php"; // Redirect the user to the login page if there is inactivity
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                            console.log(error);
                            console.log(status);
                            console.log(xhr);
                        }
                    });
                }
                // Set a 5 minute timer to check for DOM events (inactivity)
                function resetTimer() {
                    clearTimeout(time);
                    time = setTimeout(logout, 300000)
                }
            };

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

<?php
    include_once 'footer.php';
?>