<?php
if (isset($_POST["login"])) {

    session_start();
    $badgeID = $_POST["id-number"];
    $_SESSION['id-number'] = $_POST['id-number'];
    $userPwd = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputLogin($badgeID, $userPwd) !== false) {
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($conn, $badgeID, $userPwd);
}
else {
    header("location: ../login.php");
    exit();
}