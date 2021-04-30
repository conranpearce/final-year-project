<?php
    // Get token from TP-Link using log in credentials and UUID
    function getToken($uuid) {
        $tpUser = $_SESSION["userTpLinkUsr"];
        $tpPwd = $_SESSION["userTpLinkPwd"];
        $fieldPost = "{\n \"method\": \"login\",\n \"params\": {\n \"appType\": \"Kasa_Android\",\n \"cloudUserName\": \"$tpUser\",\n \"cloudPassword\": \"$tpPwd\",\n \"terminalUUID\": \"$uuid\"\n }\n}";
        $tokenResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
        $tokenDecoded = json_decode($tokenResponse, true);
        return $tokenDecoded['result']['token'];
    }

    // Get device list using token generated from before
    function getDeviceList($token) {
        $fieldPost = "{\n \"method\": \"getDeviceList\",\n \"params\": {\n \"token\": \"$token\"\n }\n}";
        $deviceListResponse = postCurlRequest("https://wap.tplinkcloud.com", $fieldPost);
        return json_decode($deviceListResponse, true);
    }
?>