<?php

if (isset($_POST["Register"])) {
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    $badgeID = $_POST["id-number"];
    $adminID = $_POST["admin-id"];
    $date_time = date('Y-m-d H:i:s');

    if (emptyDashboard($badgeID, $adminID) !== false) {
        header("Location: ../adminpage.php?error=incomplete");
        exit();
    }

    registerBadge($conn, $badgeID, $adminID, $date_time);
}
elseif (isset($_POST["Remove"])) {
    $badgeID = $_POST["id-number"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    removeBadge($conn, $badgeID);
}
else {
    header("location: ../adminpage.php?error=none");
    exit();
}