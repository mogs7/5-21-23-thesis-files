<?php
if (isset($_POST["login"])) {
 
    session_start();
    $badgeID = $_POST["id-number"];
    $_SESSION['id-number'] = $_POST['id-number'];
    $userPwd = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputLoginAdmin($badgeID, $userPwd) !== false) {
        header("location: ../adminLogin.php?error=emptyinput");
        exit();
    }

    loginAdmin($conn, $badgeID, $userPwd);
}
else {
    header("location: ../adminLogin.php");
    exit();
}