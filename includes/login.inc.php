<?php 

if(isset($_POST["submit"])) {
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';    

    if (emptyInputLogin($username, $pwd) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    // loginUser($conn, $username, $pwd);

    // createCO2($conn, $name, $email, $username, $pwd, $tplinkuser, $tplinkpwd);

    echo "<p> get intesnity " . getIntensity($conn, 1)  . "</p>";

    $intensity = getIntensity($conn, 1);

    $_SESSION["co2U"] = $intensity["co2Used"];

    echo "<p> co2 " . $_SESSION["co2U"]  . "</p>";

    // $id = 2;
    // $used = 39;
    // $saved = 200;

    // createCO2($conn, $id, $used, $saved);


} else {
    header("location: ../login.php");
    exit();
}