<?php 

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php'; 

    include('../index-functions/get-requests.php');
    include('../index.php');

    // Getting and setting carbon intensity in carbon_intensity_saved table
    $uuid = getUUID();
    $token = getToken($uuid);
    $currentDateTime = date("Y-m-d") . "T" .date("H:i") ."Z";

    $bestForecast = getBestCarbonIntensity24hr($token, $currentDateTime);
    $carbonUsed = $bestForecast['intensity']['forecast'];

    $carbonSaved = getCurrentCarbonIntensity();

    createCO2($conn, $_SESSION["userid"], $carbonUsed, $carbonSaved);
