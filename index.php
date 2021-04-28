<?php
    include_once 'header.php';
?>
    <section class="index-intro">
        <?php 
            // Include files
            include('index-functions/curl-templates.php');
            include('index-functions/get-requests.php');
            include('index-functions/post-requests.php');
            include('index-functions/set-variables.php');
            include('index-functions/on-off-buttons.php');
            include('index-functions/schedule-buttons.php');
            
            // Global variable
            $_LOGGED_IN = False;

            // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
            if (isset($_SESSION["useruid"])) {
                $_LOGGED_IN = True; 
            } else { // Otherwise direct the user to the login or signup page
                echo "
                    <div class='container'>
                        <div class='center'>
                            <a href='login.php' class='button'>LOGIN</a>
                            <a href='signup.php' class='button'>SIGN UP</a>
                        </div>
                    </div>";
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

            // If the device has eben scheduled then update the database with the carbon intensity used when scheduling and current intensity
            if ($_GET["argument"]=='schedule'){
                if ($_GET["act"]=='device'){
                    include('includes/set-carbon-intensity-saved.inc.php');
                }
            }

            // If the user is logged in then carry out this code
            if ($_LOGGED_IN == True) {
                # Get the current time and date to pass into the national grid API
                date_default_timezone_set('Europe/London');
                $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";
                // Get the token from the TP-Link API using a UUID
                $uuid = getUUID();
                $token = getToken($uuid);
                $currentCarbonForecast = getCurrentCarbonIntensity();
                # Get carbon intensity period in the next 24 hours. Then set the device (manually inputted at the moment to turn on/schedule at the best time)
                $bestDay = getBestCarbonIntensity24hr($token, $currentDateTime);
                $bestDay = $bestDay['from'];

                // If the TP-Link credentials are not valid then do not display any buttons
                if (empty($token)) {
                    echo "<script type='text/javascript'>alert('Invalid TP-Link credentials');</script>";
                } else { // Display the devices available to turn on/off and schedule 
                    $deviceListDecoded = getDeviceList($token);
                    $deviceCount = count($deviceListDecoded['result']['deviceList']);
                    $devices = array();
                    // Set the devices_json available for the JS to interact with
                    $devices_json = setOnOffButtons($token, $deviceListDecoded, $deviceCount, $devices);
                    $schedule_devices_json = setScheduleButtons($token, $deviceListDecoded, $deviceCount, $devices);
                    $minutes = setMinutes($token, $bestDay);
                    $bestDayFormatted = setDaySchedule($bestDay, $token, $minutes);
                }
            }
        ?>

        <script type="text/javascript" src="js/findDayTime.js"></script>
        <script type="text/javascript" src="js/postRequests.js"></script>

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

            // await TP-Link Request
            async function tpLinkRequest(raw) {
                return (await (await fetch("https://wap.tplinkcloud.com", postRequestHeaders(raw))).json());
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
            
            // If the smart plug is interacted with then change the state 
            async function smartPlugClick(cb, id) {
                // For each of the devices linked to the account
                var deviceObj = JSON.parse('<?= $devices_json; ?>');
                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        let tpLinkReturn = [];
                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturn = await callTplinkAPI(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }
                        deviceReturnResponse = tpLinkReturn["result"]["responseData"]
                        // Set the state of the smart plug
                        if (deviceReturnResponse.includes('relay_state":0')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":1}}}\"\n }\n}`;
                        } else if (deviceReturnResponse.includes('relay_state":1')) {
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":0}}}\"\n }\n}`;
                        }
                        postTpLinkRequest(raw);
                    }
                }
            }

            // If a smart bulb is interacted with
            async function smartBulbClick(cb, id) {
                var deviceObj = JSON.parse('<?= $devices_json; ?>');

                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        let tpLinkReturn = [];
                        // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                        try {
                            tpLinkReturn = await callTplinkAPI(deviceObj[i]);
                        }  catch (e) {
                            console.log("Error");
                            console.log(e);
                        }
                        deviceReturnResponse = tpLinkReturn["result"]["responseData"];
                        // Change the sate of the smart bulb
                        if (deviceReturnResponse.includes('on_off\":0')) {
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":1,\\\"transition_period\\\":1000}}}\"\n }\n}`;
                        } else if (deviceReturnResponse.includes('on_off\":1')) {
                            var raw = `{\n \"method": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"smartlife.iot.smartbulb.lightingservice\\\":{\\\"transition_light_state\\\":{\\\"brightness\\\":100,\\\"color_temp\\\":3500,\\\"ignore_default\\\":0,\\\"mode\\\":\\\"normal\\\",\\\"on_off\\\":0,\\\"transition_period\\\":1000}}}\"\n }\n}`;
                        }
                        postTpLinkRequest(raw);
                    }
                }
            }
            
            // if the smart plug is set to be scheduled or remove scheduling
            async function smartPlugScheduleClick(cb, id) {
                var token = '<?= $token; ?>';
                var minutes = '<?= $minutes; ?>';
                var bestDayFormatted = '<?= $bestDayFormatted; ?>';
                var deviceObj = JSON.parse('<?= $devices_json; ?>');
                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
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
                        deviceReturnResponseSchedule = tpLinkReturnSchedule["result"]["responseData"];
                        // If the device is not scheduled then set it to be scheduled
                        if (deviceReturnResponseSchedule.includes('rule_list\":[]')) { // no schedule                     
                            var raw = '{"method": "passthrough","params": {"deviceId": "' + deviceObj[i]['userDeviceId'] + '","token": "' + token +'","requestData": "{\\"schedule\\":{\\"add_rule\\":{\\"stime_opt\\":0,\\"wday\\":' + bestDayFormatted + ',\\"smin\\":' + minutes + ',\\"enable\\":1,\\"repeat\\":1,\\"etime_opt\\":-1,\\"name\\":\\"plug on\\",\\"eact\\":-1,\\"month\\":0,\\"sact\\":1,\\"year\\":0,\\"longitude\\":0,\\"day\\":0,\\"force\\":0,\\"latitude\\":0,\\"emin\\":0},\\"set_overall_enable\\":{\\"enable\\":1}}}"}}';
                            postTpLinkRequest(raw);
                            // Get id for the smart device button
                            deviceId = (id.replace(' ', '') + '-button').toLowerCase();

                            // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                            try {
                                // Find scheduled time
                                tpLinkNewSchedule = await callTplinkAPIScheduled(deviceObj[i]);   
                                updatedSchedule = tpLinkNewSchedule["result"]["responseData"];
                                document.getElementById(deviceId).innerHTML = "Scheduled for " + findTime(updatedSchedule) + " " + findDay(updatedSchedule);
                                schedulePlugs();
                            }  catch (e) {
                                console.log("Error");
                                console.log(e);
                            }
                        // If the device is scheduled then remove any scheduling on clock
                        } else {
                            var raw = '{"method": "passthrough","params": {"deviceId": "' + deviceObj[i]['userDeviceId'] + '","token": "' + token +'","requestData": "{\\"schedule\\":{\\"delete_all_rules\\":null, \\"erase_runtime_stat\\":null}}}"}}';
                            deviceId = (id.replace(' ', '') + '-button').toLowerCase();
                            document.getElementById(deviceId).innerHTML = "The device is not scheduled";
                            postTpLinkRequest(raw);
                        }
                    }
                }
            }
        </script>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <script type="text/javascript" src="js/inactivity.js"></script>
        <script type="text/javascript" src="js/update.js"></script>
        <script type="text/javascript" src="js/schedule.js"></script>

    </section>

<?php
    include_once 'footer.php';
?>