<?php 

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php'; 

    include('../index-functions/get-requests.php');

    return getIntensity($conn, $_SESSION["userid"]);
