<?php
    include_once 'header.php';
?>
    <section class="index-intro">

        <?php 
            if (isset($_SESSION["useruid"])) {
                echo "<p>Hello there " . $_SESSION["useruid"] . "</p>";
            } 
        ?>

        <h1>This is an introduction</h1>

        <?php

            // Getting current carbon intensity
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

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response; // This echo does full carbon intensity response

            $decoded = json_decode($response, true);
            echo "<h2>" . $decoded['data'][0]['intensity']['index'] . "</h2>";

            // Getting UUID4
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

            $response = curl_exec($curl);

            $uuid4 = $response;

            curl_close($curl);
        
            // Get token from TP-Link using log in credentials

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
                CURLOPT_POSTFIELDS =>"{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"***\",\n \"cloudPassword\": \"***\",\n \"terminalUUID\": \"$uuid4\"\n }\n}",
                // CURLOPT_POSTFIELDS =>"{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"****\",\n \"cloudPassword\": \"***\",\n \"terminalUUID\": \"$uuid4\"\n }\n}",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: text/plain"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $decoded = json_decode($response, true);

            $token = $decoded['result']['token'];

            // Get device list using token generated from before

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

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;

            $decoded = json_decode($response, true);

            $elementCount = count($decoded['result']['deviceList']);

            $devices = array();

            for ($x = 0; $x < $elementCount; $x++) {
                echo "<p>" . $decoded['result']['deviceList'][$x]['alias'] . "</p>";

                $deviceName = str_replace(' ', '', strtolower($decoded['result']['deviceList'][$x]['alias']));
                $deviceID = $decoded['result']['deviceList'][$x]['deviceId'];

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

                $responseRelayState = curl_exec($curl2);

                curl_close($curl);

                $decodedRelayState = json_decode($responseRelayState, true);

                $dataResponse = json_encode($decodedRelayState["result"]["responseData"]);

                // Can't access relay_state value so doing something else
                if (stripos($dataResponse, 'relay_state\":0') !== false) {
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
                }

                echo "devices array print " . json_decode($devices);
            }

            // convert array into json
            $devices_json = json_encode($devices);

            echo $devices_json;
        ?>

        <!-- Testing calling javascript -->
        <script>

            async function callAPI() {
                var requestOptions = {
                    method: 'GET',
                    redirect: 'follow'
                };

                let response = await fetch("https://api.carbonintensity.org.uk/intensity/", requestOptions);
                // only proceed once promise is resolved
                let data = await response.json();
                // only proceed once second promise is resolved
                return data;

                // Works for logging
                // var requestOptions = {
                    // method: 'GET',
                    // redirect: 'follow'
                // };

                // fetch("https://api.carbonintensity.org.uk/intensity/", requestOptions)
                //     .then(response => response.json())
                //     .then((result) => {
                //         console.log(result);
                //         console.log(result['data'][0]['intensity']['index']);
                //         return await result;
                //     })
                //     .catch(error => console.log('error', error));
            }

            function handleClick(cb, id) {

                console.log(cb.checked);
                console.log(id);

                var deviceObj = JSON.parse('<?= $devices_json; ?>');
                
                // console.log(deviceObj);

                for (var i = 0; i < deviceObj.length; i++) {
                    console.log("DeviceObj[i] ", deviceObj[i]);
                }

                for (var i = 0; i < deviceObj.length; i++) {
                    if (deviceObj[i]['userDeviceAlias'] == id) {
                        console.log("Selected is ", deviceObj[i]);

                        var myHeaders = new Headers();
                        myHeaders.append("Content-Type", "text/plain");

                        // ************************************************************
                        // NOT PROPERLY WORKING HERE AS NEED TO UPDATE STATE MORE FREQUENTLY
                        
                        // Get device current status (RELAY_STATE)

                        var myHeaders = new Headers();
                        myHeaders.append("Content-Type", "text/plain");

                        var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"get_sysinfo\\\":null},\\\"emeter\\\":{\\\"get_realtime\\\":null}}\"\n }\n}`;

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

                        // SETTING STATE OF PLUG HERE

                        // Need to get relay state from above to then set below the device status

                        if (deviceObj[i]['userDeviceState'] == 0) {
                            console.log("Now is 1");
                            // deviceObj[i]['userDeviceState'] == 1;
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":1}}}\"\n }\n}`;
                        } else if (deviceObj[i]['userDeviceState'] == 1) {
                            console.log("Now is 0");
                            // deviceObj[i]['userDeviceState'] == 0;
                            var raw = `{\n \"method\": \"passthrough\",\n \"params\": {\n \"token\": \"${deviceObj[i]['userToken']}\",\n \"deviceId\": \"${deviceObj[i]['userDeviceId']}\",\n \"requestData\": \"{\\\"system\\\":{\\\"set_relay_state\\\":{\\\"state\\\":0}}}\"\n }\n}`;
                        }

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
            // console.log(callAPI());
            callAPI()
                .then(data => console.log(data))
                .catch(reason => console.log(reason.message))
        </script>

    </section>

<script src="js/checkbox.js"></script>

<?php
    include_once 'footer.php';
?>