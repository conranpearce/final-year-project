<?php
// Signup the user to the system
if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["uid"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];
    $tplinkuser = $_POST["tplinkuser"];
    $tplinkpwd = $_POST["tplinkpwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    // Error handling for invalid signup
    if (emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat, $tplinkuser, $tplinkpwd) !== false) {
        header("location: ../signup.php?error=emptyinput");
        exit();
    }

    if (invalidUid($username) !== false) {
        header("location: ../signup.php?error=invaliduid");
        exit();
    }

    if (invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }

    if (pwdMatch($pwd, $pwdRepeat) !== false) {
        header("location: ../signup.php?error=passwordsdontmatch");
        exit();
    }

    if (uidExists($conn, $username, $email) !== false) {
        header("location: ../signup.php?error=usernametaken");
        exit();
    }

    // If no errors then sign the user up
    createUser($conn, $name, $email, $username, $pwd, $tplinkuser, $tplinkpwd);
    
} else {
    header("location: ../signup.php");
    exit();
}