<?php

if (isset($_POST["Register"])) {
    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $badgeID = $_POST["id-number"];
    $userPhone = $_POST["phone-number"];
    $userPwd = $_POST["password"];
    $checkbox = $_POST["checkbox"];
 
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputSignup($firstName, $lastName, $badgeID, $userPhone, $userPwd) !== false) {
        header("location: ../register.php?error=emptyinput");
        exit();
    }
    if (emptyCheckbox($checkbox) !== false) {
        header("location: ../register.php?error=checkbox");
        exit();
    }
    if (invaliduserID($badgeID) !== false) {
        header("location: ../register.php?error=invaliduserID");
        exit();
    }
    if (invalidPhone($userPhone) !== false) {
        header("location: ../register.php?error=invalidPhoneNumber");
        exit();
    }
    if (badgeIDCheck($conn, $badgeID) !== false) {
        header("location: ../register.php?error=IDdoesntexit");
        exit();
    }
    if (badgeIDExists($conn, $badgeID) !== false) {
        header("location: ../register.php?error=usernametaken");
        exit();
    }

    createUser($conn, $firstName, $lastName, $badgeID, $userPhone, $userPwd);
}
else if (isset($_POST["Login"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';
    login($login);
}
else {
    header("location: ../register.php");
    exit();
}
