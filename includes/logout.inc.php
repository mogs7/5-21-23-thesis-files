<?php

    
    session_start();
    $ID = $_SESSION["id-number"];
    require_once 'dbh.inc.php';

    $sql = "UPDATE usersinfo SET userStatus = 0, date_time = 0 WHERE badgeID = '$ID'";

    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("location: ../UserPage.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    session_destroy();

    header("Location: /webapp/index.php");


?>