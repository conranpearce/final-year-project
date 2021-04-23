<?php
    include_once 'header.php';
?>
    <section class="index-intro">

        <h1>This is an introduction</h1>

        <?php 
            include('index-functions/curl-templates.php');
            include('index-functions/get-requests.php');
            include('index-functions/post-requests.php');
            include('index-functions/set-variables.php');
            include('index-functions/on-off-buttons.php');
            include('index-functions/schedule-buttons.php');
            
            // Global variable
            $_LOGGED_IN = False;

            if (isset($_SESSION["useruid"])) {
                echo "<p>Hello there " . $_SESSION["useruid"] . "</p>";

                // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
                $_LOGGED_IN = True;
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
            }

            // Update the information displayed every 30 minutes, relating to the update of the carbon intensity API
            if ($_GET["act"]=='update'){
                echo "success";
            }

            // If the user is logged in then carry out this code
            if ($_LOGGED_IN == True) {
                # Get the current time and date to pass into the national grid API
                $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";
                echo "<h2>" . "Current date and time forecast for API is " . $currentDateTime . "</h2>";

                $uuid = getUUID();

                $token = getToken($uuid);

                getCurrentCarbonIntensity();
                getCurrentGenerationMix();

                # Get carbon intensity period in the next 24 hours. Then set the device (manually inputted at the moment to turn on/schedule at the best time)
                $bestDay = getBestCarbonIntensity24hr($token, $currentDateTime);

                $deviceListDecoded = getDeviceList($token);
                $deviceCount = count($deviceListDecoded['result']['deviceList']);
                $devices = array();
                
                // Set the devices_json available for the JS to interact with
                $devices_json = setOnOffButtons($token, $deviceListDecoded, $deviceCount, $devices);
                $schedule_devices_json = setScheduleButtons($token, $deviceListDecoded, $deviceCount, $devices);
                $minutes = setMinutes($token, $bestDay);
                $bestDayFormatted = setDaySchedule($bestDay, $token, $minutes);
            }
        ?>

        <!-- Testing calling javascript -->
        <script>
            
            // When the page loads, check for inactivity
            window.onload = function() {
                // Check if a user is logged in
                if (<?php echo $_LOGGED_IN ?> == 1) {
                    inactivity();  
                    // Refresh the page every 30 minutes
                    updateAPIs();
                }
            }

            async function tpLinkRequest(raw) {
                var myHeaders = new Headers();
                myHeaders.append("Content-Type", "text/plain");
                
                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                return (await (await fetch("https://wap.tplinkcloud.com", requestOptions)).json());

            }

            // async function to return the fetch API which retrieves the state (relay_state) of the device selected by the user
            async function callTplinkAPI(deviceObject) {
                var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObject['userToken']}\",\n \"deviceId\": \"${deviceObject['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n }\n}`;
                return tpLinkRequest(raw);
            }

            // async function to return the fetch API which retrieves the state (relay_state) of the device selected by the user
            async function callTplinkAPIScheduled(deviceObject) {
                var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObject['userToken']}\",\n \"deviceId\": \"${deviceObject['userDeviceId']}\",\n \"requestData\": \"{\\\"schedule\\\":{\\\"get_rules\\\":null}}"\n}\n}`;
                return tpLinkRequest(raw);
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

                        deviceReturnResponse = tpLinkReturn["result"]["responseData"]

                        if (deviceReturnResponse.includes('relay_state":0')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":1}}}\"\n }\n}`;
                        } else if (deviceReturnResponse.includes('relay_state":1')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":0}}}\"\n }\n}`;
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

                console.log("device object ", deviceObj);

                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        let tpLinkReturn = [];

                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturn = await callTplink(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }

                        deviceReturnResponse = tpLinkReturn["result"]["responseData"];

                        console.log("deviceReturnResponse ", deviceReturnResponse);

                        if (deviceReturnResponse.includes('on_off\":0')) {
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":1,\\\"transition_period\\\":1000}}}\"\n }\n}`;
                        } else if (deviceReturnResponse.includes('on_off\":1')) {
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":0,\\\"transition_period\\\":1000}}}\"\n }\n}`;
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

            async function smartPlugScheduleClick(cb, id) {
                var token = '<?= $token; ?>';
                var minutes = '<?= $minutes; ?>';
                var bestDayFormatted = '<?= $bestDayFormatted; ?>';

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

                        deviceReturnResponse = tpLinkReturn["result"]["responseData"];

                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturnSchedule = await callTplinkAPIScheduled(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }

                        console.log("tpLinkReturnSchedule ", tpLinkReturnSchedule);
                        deviceReturnResponseSchedule = tpLinkReturnSchedule["result"]["responseData"];

                        if (deviceReturnResponseSchedule.includes('rule_list\":[]')) { // no schedule                     
                            var raw = '{"method": "passthrough","params": {"deviceId": "' + deviceObj[i]['userDeviceId'] + '","token": "' + token +'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":' + bestDayFormatted + ',\\"smin\\":' + minutes + ',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                            console.log("The device is now scheduled");
                        } else {
                            var raw = '{"method": "passthrough","params": {"deviceId": "' + deviceObj[i]['userDeviceId'] + '","token": "' + token +'","requestData": "{\\"schedule\\":{\\"delete_all_rules\\":null, \\"erase_runtime_stat\\":null}}}"}}';
                            console.log("The device is not scheduled");
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
        <script type="text/javascript" src="js/inactivity.js"></script>
        <script type="text/javascript" src="js/update.js"></script>
    </section>

<?php
    include_once 'footer.php';
?>