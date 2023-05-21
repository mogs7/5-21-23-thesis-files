<?php

//REGISTER
function emptyInputSignup($firstName, $lastName, $badgeID, $userPhone, $userPwd) {
    $result;
    if (empty($firstName) || empty($lastName) || empty($badgeID) || empty($userPhone) || empty($userPwd)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function emptyCheckbox($checkbox) {
    $result;
    if (empty($checkbox)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invaliduserID($badgeID) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $badgeID)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidPhone($userPhone) {
    $result;
    if (!preg_match('/^[0-9]{11}+$/', $userPhone)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function badgeIDExists($conn, $badgeID) {
    $sql = "SELECT * FROM usersinfo WHERE badgeID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../register.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function badgeIDCheck($conn, $badgeID) {
    $sql = "SELECT * FROM ids WHERE badgeID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../register.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $result = false;
        return $result;
    }
    else {
        return $row;
    }

    mysqli_stmt_close($stmt);
}

function createUser($conn, $firstName, $lastName, $badgeID, $userPnumber, $userPwd) {
    $sql = "INSERT INTO usersinfo (firstName, lastName, badgeID, usersPnumber, usersPwd) VALUES (?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../register.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($userPwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss", $firstName, $lastName, $badgeID, $userPnumber, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../register.php?error=none");
    exit();
}

function login($login) {
    header("location: ../login.php");
}


//LOGIN
function emptyInputLogin($badgeID, $userPwd) {
    $result;
    if ( empty($badgeID) || empty($userPwd)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $badgeID, $userPwd) {
    $badgeExists = badgeIDExists($conn, $badgeID);

    if ($badgeExists == false){
        header("location: ../login.php?error=doesntexist/wrongusername");
        exit();
    }

    $Pwdhashed = $badgeExists["usersPwd"];
    $checkPwd = password_verify($userPwd, $Pwdhashed);

    if ($checkPwd == false){
        header("location: ../login.php?error=wrongpassword");
        exit();
    }
    else if ($checkPwd == true){
        session_start();
        require_once 'dbh.inc.php';
        $date_time = date('Y-m-d H:i:s');
        $sql = "UPDATE usersinfo SET userStatus = 1, date_time = NOW() WHERE badgeID = '$badgeID'";
        $result=mysqli_query($conn, $sql);
        $_SESSION["$usersID"] = $badgeExists["$usersID"];
        $_SESSION["$badgeID"] = $badgeExists["$badgeID"];
        header("location: ../UserPage.php");

        acceptUser($conn, $badgeID);
        exit();
    }
}


//ADMIN REGISTER
function badgeIDCheckAdmin($conn, $badgeID) {
    $sql = "SELECT * FROM admins WHERE adminName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../reg.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        $result = false;
        return $result;
    }
    else {
        return $row;
    }

    mysqli_stmt_close($stmt);
}

function badgeIDExistsAdmin($conn, $badgeID) {
    $sql = "SELECT * FROM admins WHERE adminName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../register.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function createUserAdmin($conn, $badgeID, $userPwd) {
    $sql = "INSERT INTO admins (adminName, adminPwd) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../reg.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($userPwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "ss", $badgeID, $hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../reg.php?error=none");
    exit();
}


//ADMIN LOGIN
function emptyInputLoginAdmin($badgeID, $userPwd) {
    $result;
    if ( empty($badgeID) || empty($userPwd)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function loginAdmin($conn, $badgeID, $userPwd) {
    $badgeExists = badgeIDExistsAdmin($conn, $badgeID);

    if ($badgeExists == false){
        header("location: ../adminLogin.php?error=doesntexist/wrongusername");
        exit();
    }

    $Pwdhashed = $badgeExists["adminPwd"];
    $checkPwd = password_verify($userPwd, $Pwdhashed);

    if ($checkPwd == false){
        header("location: ../adminLogin.php?error=wrongpassword");
        exit();
    }
    else if ($checkPwd == true){
        session_start();
        $_SESSION["$usersID"] = $badgeExists["$usersID"];
        $_SESSION["$badgeID"] = $badgeExists["$badgeID"];
        header("location: ../adminpage.php");

        acceptUser($conn, $badgeID);
        exit();
    }
}


//REPORT
function emptyInputReport($lastName, $firstName, $birthday, $licenseNum, $licensePlate, $regNum, $violation) {
    $result;
    if ( empty($lastName) || empty($firstName) || empty($birthday) || empty($licenseNum) || empty($licensePlate) || empty($regNum) || empty($violation)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidlicenseNum($licenseNum) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $licenseNum)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidlicensePlate($licensePlate) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $licensePlate)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function invalidregNum($regNum) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $regNum)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function report($conn, $lastName, $firstName, $middleName, $birthday, $licenseNum, $licensePlate, $regNum, $violation, $date_time, $ID) {
    $sql = "INSERT INTO report (lastName, firstName, middleName, birthday, licenseNum, licensePlate, regNum, violation, date, badgeID) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../report.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ssssssssss", $lastName, $firstName, $middleName, $birthday, $licenseNum, $licensePlate, $regNum, $violation, $date_time, $ID );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../report.php?error=none");
    exit();
}


//Status
function emptyInputStatus($licenseNum, $status, $lastName, $firstName, $birthday, $licensePlate, $regNum) {
    $result;
    if (empty($licenseNum) || empty($status)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
function emptyInputStatusUn($licenseNum) {
    $result;
    if (empty($licenseNum)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}
function illegalreviewed($conn, $id, $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time) {
    $sql = "UPDATE video SET licenseNum = ?, status = ?, badgeID = ?, lastName = ?, firstName = ?, middleName = ?, birthday = ?, licensePlate = ?, regNum = ?, date_reg = ? WHERE videoID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../illegallane.php?error=none");
        exit();
    }
}

function illegalunaddressed($conn, $id, $date_time, $status, $badgeID, $licensePlate) {
    $fetchSql = "SELECT status FROM video WHERE videoID = ?";
    $fetchStmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($fetchStmt, $fetchSql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    } else{
        mysqli_stmt_bind_param($fetchStmt, "i", $id);
        mysqli_stmt_execute($fetchStmt);
        mysqli_stmt_bind_result($fetchStmt, $currentStatus2);
        mysqli_stmt_fetch($fetchStmt);
        mysqli_stmt_close($fetchStmt);

        if ($currentStatus2 === "Reviewed") {
            header("Location: ../illegallane.php?error=cannotchange");
            exit();
        }

        $sql = "UPDATE video SET status = ?, badgeID = ?, licensePLate = ?, date_reg = ? WHERE videoID = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ssssi", $status, $badgeID, $licensePlate, $date_time, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("location: ../illegallane.php?error=none");
            exit();
        }
    }
}
function overreviewed($conn, $id, $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time) {
    $sql = "UPDATE video SET licenseNum = ?, status = ?, badgeID = ?, lastName = ?, firstName = ?, middleName = ?, birthday = ?, licensePlate = ?, regNum = ?, date_reg = ? WHERE videoID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: ../overspeeding.php?error=none");
        exit();
    }
}
function overunaddressed($conn, $id, $status, $badgeID, $licensePlate, $date_time) {
    $fetchSql = "SELECT status FROM video WHERE videoID = ?";
    $fetchStmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($fetchStmt, $fetchSql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    } else{
        mysqli_stmt_bind_param($fetchStmt, "i", $id);
        mysqli_stmt_execute($fetchStmt);
        mysqli_stmt_bind_result($fetchStmt, $currentStatus);
        mysqli_stmt_fetch($fetchStmt);
        mysqli_stmt_close($fetchStmt);

        if ($currentStatus === "Reviewed") {
            header("Location: ../overspeeding.php?error=cannotchange");
            exit();
        }

        $sql = "UPDATE video SET status = ?, badgeID = ?, licensePLate = ?, date_reg = ? WHERE videoID = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ssssi", $status, $badgeID, $licensePlate, $date_time, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("location: ../overspeeding.php?error=none");
            exit();
        }
    }
}
function redreviewed($conn, $id, $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time){
    $sql = "UPDATE video SET licenseNum = ?, status = ?, badgeID = ?, lastName = ?, firstName = ?, middleName = ?, birthday = ?, licensePlate = ?, regNum = ?, date_reg = ? WHERE videoID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    }else{
        mysqli_stmt_bind_param($stmt, "ssssssssssi", $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("location: ../redlight.php?error=none");
        exit();
    }
}

function redunaddressed($conn, $id, $date_time, $status, $badgeID, $licensePlate) {
    $fetchSql = "SELECT status FROM video WHERE videoID = ?";
    $fetchStmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($fetchStmt, $fetchSql)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
        exit();
    } else{
        mysqli_stmt_bind_param($fetchStmt, "i", $id);
        mysqli_stmt_execute($fetchStmt);
        mysqli_stmt_bind_result($fetchStmt, $currentStatus1);
        mysqli_stmt_fetch($fetchStmt);
        mysqli_stmt_close($fetchStmt);

        if ($currentStatus1 === "Reviewed") {
            header("Location: ../redlight.php?error=cannotchange");
            exit();
        }

        $sql = "UPDATE video SET status = ?, badgeID = ?, licensePLate = ?, date_reg = ? WHERE videoID = ?";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("location: " . $_SERVER['PHP_SELF'] . "?error=stmtfailed");
            exit();
        }else{
            mysqli_stmt_bind_param($stmt, "ssssi", $status, $badgeID, $licensePlate, $date_time, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header("location: ../redlight.php?error=none");
            exit();
        }
    }


}



//Admin Dashboard
function emptyDashboard($badgeID, $adminID) {
    $result;
    if ( empty($badgeID) || empty($adminID)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

function adminIDExists($conn, $adminID) {
    $sql = "SELECT * FROM admins WHERE adminName = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../adminpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $adminID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function IDExists($conn, $badgeID) {
    $sql = "SELECT * FROM ids WHERE badgeID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../adminpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function registerBadge($conn, $badgeID, $adminID, $date_time) {
    $adminExists = adminIDExists($conn, $adminID);
    $IDExists = IDExists($conn, $badgeID);

// if (($IDExists == true && $adminID == false) || ($IDExists == false && $adminID == false) || ($IDExists == true && $adminID == true))
    if ((!empty($IDExists) && empty($adminExists)) || (empty($IDExists) && empty($adminExists)) || (!empty($IDExists) && !empty($adminExists))){
        header("location: ../adminpage.php?error=checkdetails");
        exit();
    }

    $sql = "INSERT INTO ids (badgeID, adminID, date_time) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../adminpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "sss", $badgeID, $adminID, $date_time);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../adminpage.php?error=none");
    exit();
}

function badgeIDAdmin($conn, $badgeID) {
    $sql = "SELECT * FROM usersinfo WHERE badgeID = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../register.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

function removeBadge($conn, $badgeID) {
    $badgeExists = badgeIDAdmin($conn, $badgeID);
    $IDExists = IDExists($conn, $badgeID);

    if (empty($IDExists) && empty($badgeExists)){
        header("location: ../adminpage.php?error=doesntexist/wrongusername");
        exit();
    }

    $sql = "DELETE ids, usersinfo FROM ids LEFT JOIN usersinfo ON ids.badgeID = usersinfo.badgeID WHERE ids.badgeID = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../adminpage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $badgeID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../adminpage.php?error=none");
    exit();
}
