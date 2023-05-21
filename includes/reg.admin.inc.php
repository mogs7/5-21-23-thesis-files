<?php

if (isset($_POST["click"])) {
    $badgeID = $_POST["id-number"];
    $userPwd = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    createUserAdmin($conn, $badgeID, $userPwd);
}
else {
    header("location: ../reg.php?error=none");
    exit();
}
