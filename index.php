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

            // Get current carbon intensity

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
            // Not print out full carbon intensity data
            // echo $response;

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
            // Not printing out uuid
            // echo "<p>" . $uuid4 . "</p>";
        
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
            CURLOPT_POSTFIELDS =>"{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"cameron.backus@gmail.com\",\n \"cloudPassword\": \"jes5&SW2Mq5&qPcvGZba\",\n \"terminalUUID\": \"$uuid4\"\n }\n}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: text/plain"
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;

            $decoded = json_decode($response, true);
            // echo $decoded['result']['token'];

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

            $plugAlias = $decoded['result']['deviceList'][0]['alias'];

            // echo $plugAlias

            // $length = json_decode($decoded['result']['deviceList'], true);

            $elementCount = count($decoded['result']['deviceList']);

            for ($x = 0; $x < $elementCount; $x++) {
                echo "<p>" . $decoded['result']['deviceList'][$x]['alias'] . "</p>";

                if ($decoded['result']['deviceList'][$x]['status'] == 0) {
                        
                    str_replace(' ', '', );

                    echo "<label class='switch'>
                            <input type='checkbox' id='" . str_replace(' ', '', strtolower($decoded['result']['deviceList'][$x]['alias'])) . "' onclick='handleClick(this, this.id);'>
                            <span class='slider round'></span>
                        </label>";
            
                } else if ($decoded['result']['deviceList'][$x]['status'] == 1) {
                    echo "<label class='switch'>
                            <input type='checkbox' id='" . str_replace(' ', '', strtolower($decoded['result']['deviceList'][$x]['alias'])) . "'checked onclick='handleClick(this, this.id);'>
                            <span class='slider round'></span>
                        </label>";
                }
            }
        ?>

        <!-- Testing calling javascript -->
        <script>
            function callAPI() {
                var requestOptions = {
                method: 'GET',
                redirect: 'follow'
                };

                fetch("https://api.carbonintensity.org.uk/intensity/", requestOptions)
                    .then(response => response.json())
                    .then((result) => {
                        console.log(result);
                        console.log(result['data'][0]['intensity']['index']);
                        return result;
                    })
                    .catch(error => console.log('error', error));
            }
        </script>

        <!-- Calling javascript function above-->
        <script type="text/javascript"> 
            callAPI();
        </script>

    </section>

<script src="js/checkbox.js"></script>

<?php
    include_once 'footer.php';
?>