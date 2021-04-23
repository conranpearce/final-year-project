<?php 
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
        if (curl_errno($curl)) { // If an error then set message.
            $error_message = curl_error($curl);
        }
        curl_close($curl);
        // If an error then return error message.
        if (isset($error_message)) {
            echo "<p>Error with request.</p>";
        } else {
            return $resp;
        }
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
        if (curl_errno($curl)) {
            $error_message = curl_error($curl);
        }
        curl_close($curl);
        if (isset($error_message)) {
            echo "<p>Error with request. 2 "  . $postfields . "</p>";
        } else {
            return $resp;
        }
    }
?>