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
        
            // Getting current carbon intensity
            function getCurrentCarbonIntensity() {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.carbonintensity.org.uk/intensity/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ));

                $carbonIntensityResponse = curl_exec($curl);
                curl_close($curl);
                $carbonIntensityDecoded = json_decode($carbonIntensityResponse, true);
                echo "<h2>" . "Current carbon intensity is: " . $carbonIntensityDecoded['data'][0]['intensity']['index'] . "</h2>";
            }

            // Getting UUID
            function getUUID() {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://www.uuidgenerator.net/api/version4",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                ));

                $uuid4 = curl_exec($curl);
                curl_close($curl);
                return $uuid4;
            }

            // Get token from TP-Link using log in credentials
            function getToken($uuid) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://wap.tplinkcloud.com",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>"{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"***\",\n \"cloudPassword\": \"***\",\n \"terminalUUID\": \"$uuid\"\n }\n}",
                    // CURLOPT_POSTFIELDS =>"{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"***\",\n \"cloudPassword\": \"***\",\n \"terminalUUID\": \"$uuid\"\n }\n}",      
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: text/plain"
                    ),
                ));

                $tokenResponse = curl_exec($curl);
                curl_close($curl);
                $tokenDecoded = json_decode($tokenResponse, true);
                return $tokenDecoded['result']['token'];
            }

            // Get device list using token generated from before
            function getDeviceList($token) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://wap.tplinkcloud.com",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>"{\n \"method\": \"getDeviceList\",\n \"params\": {\n \"token\": \"$token\"\n }\n}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: text/plain"
                    ),
                ));

                $deviceListResponse = curl_exec($curl);
                curl_close($curl);
                return json_decode($deviceListResponse, true);
            }
            
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

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => "https://wap.tplinkcloud.com",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS =>"{\n \"method\": \"passthrough\",\n \"params\": {\n \"deviceId\": \"$deviceID\",\n  \"token\": \"$token\",\n\"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n\n }\n}",
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: text/plain"
                        ),
                    ));

                    $relayStateResponse = curl_exec($curl2);

                    curl_close($curl);

                    $relayStateDecoded = json_decode($relayStateResponse, true);

                    echo "relayState Decoded " . json_encode($relayStateDecoded);

                    $dataResponse = json_encode($relayStateDecoded["result"]["responseData"]);

                    // Setting button for device
                    if (stripos($dataResponse, 'relay_state\":0') !== false) { // If contains relay_state being 0 in the response
                        array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 0]);
                        echo "<label class='switch'>
                                <input type='checkbox' id='" . $deviceName . "' onclick='handleClick(this, this.id);'>
                                <span class='slider round'></span>
                            </label>";
                    } else if (stripos($dataResponse, 'relay_state\":1') !== false) {                    
                        array_push($devices, ['userToken' => $token, 'userDeviceId' => $deviceID, 'userDeviceAlias' => $deviceName, 'userDeviceState' => 1]);
                        echo "<label class='switch'>
                                <input type='checkbox' id='" . $deviceName . "'checked onclick='handleClick(this, this.id);'>
                                <span class='slider round'></span>
                            </label>";
                    } else if ($relayStateDecoded["msg"] == "Device is offline") {
                        echo "<p> Device is offline </p>";
                        echo "<label class='switch'>
                                <input type='checkbox' id='" . $deviceName . "'disabled='disabled' onclick='handleClick(this, this.id);'>
                                <span class='slider round'></span>
                            </label>";
                    }
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

            async function handleClick(cb, id) {
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