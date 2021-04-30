<?php

// Error handling for an empty input on signup
function emptyInputSignup($name, $email, $username, $pwd, $pwdRepeat, $tplinkuser, $tplinkpwd) {
    $result; 
    if (empty($name) || empty($email) || empty($username) || empty($pwd) || empty($pwdRepeat) || empty($tplinkuser) || empty($tplinkpwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Error handling for an invalid username
function invalidUid($username) {
    $result; 
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Checking for invalid email
function invalidEmail($email) {
    $result; 
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Validation of passwords entered matching
function pwdMatch($pwd, $pwdRepeat) {
    $result; 
    if ($pwd !== $pwdRepeat) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// Check if username or email exists
function uidExists($conn, $username, $email) {  
    $sql = "SELECT * FROM users WHERE usersUid = ? OR usersEmail = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    } else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

// Create a user if no errors
function createUser($conn, $name, $email, $username, $pwd, $tplinkuser, $tplinkpwd) {
    $sql = "INSERT INTO users (usersName, usersEmail, usersUid, usersPwd, tpLinkUser, tpLinkPwd) VALUES (?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $username, $hashedPwd, $tplinkuser, $tplinkpwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
    exit();
}

// If the user has not entered a username or password then throw error
function emptyInputLogin($username, $pwd) {
    $result; 
    if (empty($username) || empty($pwd)) {
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

// If the user exists then login
function loginUser($conn, $username, $pwd) {
    $uidExists = uidExists($conn, $username, $username);

    if ($uidExists === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["usersPwd"];
    $checkPwd = password_verify($pwd, $pwdHashed);

    if ($checkPwd === false) {
        header("location: ../login.php?error=wronglogin");
        exit();
    } else if ($checkPwd === true) {
        session_start();
        $_SESSION["userid"] = $uidExists["usersId"];
        $_SESSION["useruid"] = $uidExists["usersUid"];
        $_SESSION["userTpLinkUsr"] = $uidExists["tpLinkUser"];
        $_SESSION["userTpLinkPwd"] = $uidExists["tpLinkPwd"];
        $_SESSION['start_time'] = time();

        header("location: ../index.php");
        exit();
    }
}

// Create a co2 input in the database
function createCO2($conn, $userId, $co2Used, $co2Saved) {
    $sql = "INSERT INTO carbon_intensity_saved (usersId, co2Used, co2Saved) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../index.php?error=createco2");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $userId, $co2Used, $co2Saved);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Get co2 intensity saved in database for a given user
function getIntensity($conn, $userId) {  
    $sql = "SELECT * FROM carbon_intensity_saved WHERE usersId = ?;";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../saved.php?error=getco2");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $userId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    $rows = array();

    // For multiple rows of data, return all rows as an array
    while($row = mysqli_fetch_assoc($resultData)) {
        $rows[] = $row;
    }
    return $rows;

    mysqli_stmt_close($stmt);
}