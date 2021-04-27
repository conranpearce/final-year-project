<?php
    include_once 'header.php';
?>
    <section class="index-intro">
        <?php 
            include('index-functions/curl-templates.php');
            include('index-functions/get-requests.php');
            include('index-functions/post-requests.php');
            include('index-functions/set-variables.php');
            include('index-functions/on-off-buttons.php');
            include('index-functions/schedule-buttons.php');
            // include('generation.php');
            
            // Global variable
            $_LOGGED_IN = False;

            if (isset($_SESSION["useruid"])) {
                $_LOGGED_IN = True; // Set $_LOGGED_IN to true so that information is only displayed if the user is logged in
            } else {
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

            if ($_GET["argument"]=='schedule'){
                echo "<p>SCHEDULE</p>";
                
                if ($_GET["act"]=='device'){
                    include('includes/set-carbon-intensity-saved.inc.php');
                }

            }

            // If the user is logged in then carry out this code
            if ($_LOGGED_IN == True) {
                # Get the current time and date to pass into the national grid API
                date_default_timezone_set('Europe/London');
                $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";
                $uuid = getUUID();
                $token = getToken($uuid);
                $currentCarbonForecast = getCurrentCarbonIntensity();

                # Get carbon intensity period in the next 24 hours. Then set the device (manually inputted at the moment to turn on/schedule at the best time)
                $bestDay = getBestCarbonIntensity24hr($token, $currentDateTime);

                $bestDay = $bestDay['from'];
                // getCurrentGenerationMix();

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

                // getCurrentGenerationMix();

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

            // Set post request headers and variables
            function postRequestHeaders(raw) {
                var myHeaders = new Headers();
                myHeaders.append("Content-Type", "text/plain");
                var requestOptions = {
                    method: 'POST',
                    headers: myHeaders,
                    body: raw,
                    redirect: 'follow'
                };
                return requestOptions;
            }

            // await TP-Link Request
            async function tpLinkRequest(raw) {
                console.log("raw ", raw);
                return (await (await fetch("https://wap.tplinkcloud.com", postRequestHeaders(raw))).json());
            }

            // TP-Link Post request
            function postTpLinkRequest(raw) {
                fetch("https://wap.tplinkcloud.com", postRequestHeaders(raw))
                    .then(response => response.text())
                    .then(result => console.log(result))
                    .catch(error => console.log('error', error)
                );
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

            // Return the day scheduled
            function findDay(schedule) {
                console.log('find day ', schedule);
                if (schedule.includes('"wday":[1,0,0,0,0,0,0]')) { 
                    return "Sunday";
                } else if (schedule.includes('"wday":[0,1,0,0,0,0,0]')) { 
                    return "Monday";
                } else if (schedule.includes('"wday":[0,0,1,0,0,0,0]')) { 
                    return "Tuesday";
                } else if (schedule.includes('"wday":[0,0,0,1,0,0,0]')) { 
                    return "Wednesday";
                } else if (schedule.includes('"wday":[0,0,0,0,1,0,0]')) { 
                    return "Thursday";
                } else if (schedule.includes('"wday":[0,0,0,0,0,1,0]')) { 
                    return "Friday";
                } else if (schedule.includes('"wday":[0,0,0,0,0,0,1]')) { 
                    return "Saturday";
                } 
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
                        postTpLinkRequest(raw);
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
                            tpLinkReturn = await callTplinkAPI(deviceObj[i]);
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
                        postTpLinkRequest(raw);
                    }
                }
            }

            async function smartPlugScheduleClick(cb, id) {
                var token = '<?= $token; ?>';
                var minutes = '<?= $minutes; ?>';
                var bestDayFormatted = '<?= $bestDayFormatted; ?>';
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
                            postTpLinkRequest(raw);
                            // Get id for the smart device button
                            deviceId = (id.replace(' ', '') + '-button').toLowerCase();
                            console.log(document.getElementById("ev"));

                            // Get the current device state (updated after clicking the webpage button), pass in the device object the user has selected
                            try {
                                // Find scheduled time
                                tpLinkNewSchedule = await callTplinkAPIScheduled(deviceObj[i]);   
                                console.log("tpLinkNewSchedule ", tpLinkNewSchedule );
                                updatedSchedule = tpLinkNewSchedule["result"]["responseData"];
                                document.getElementById(deviceId).innerHTML = "Scheduled for " + findDay(updatedSchedule);

                                schedulePlugs();
                            }  catch (e) {
                                console.log("Error");
                                console.log(e);
                            }
                        } else {
                            var raw = '{"method": "passthrough","params": {"deviceId": "' + deviceObj[i]['userDeviceId'] + '","token": "' + token +'","requestData": "{\\"schedule\\":{\\"delete_all_rules\\":null, \\"erase_runtime_stat\\":null}}}"}}';
                            console.log("The device is not scheduled");
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