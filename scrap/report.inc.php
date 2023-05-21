<?php
if (isset($_POST["report"])) {
    session_start();
    $lastName = $_POST["last-name"];
    $firstName = $_POST["first-name"];
    $middleName = $_POST["middle-name"];
    $birthday = $_POST["birthday"];
    $licenseNum = $_POST["license-number"];
    $licensePlate = $_POST["license-plate"];
    $regNum = $_POST["registration-number"];
    $violation = $_POST["violation"];
    $date_time = date("Y-m-d H:i:s");
    $ID = $_SESSION["id-number"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputReport($lastName, $firstName, $birthday, $licenseNum, $licensePlate, $regNum, $violation) !== false) {
        header("location: ../report.php?error=emptyinput");
        exit();
    }
    if (invalidlicenseNum($licenseNum) !== false) {
        header("location: ../report.php?error=invalidlicenseNum");
        exit();
    }
    if (invalidlicensePlate($licensePlate) !== false) {
        header("location: ../report.php?error=invalidlicensePlate");
        exit();
    }
    if (invalidlicensePlate($regNum) !== false) {
        header("location: ../report.php?error=invalidregNum");
        exit();
    }
    
    report($conn, $lastName, $firstName, $middleName, $birthday, $licenseNum, $licensePlate, $regNum, $violation,$date_time,$ID );
}
else {
    header("location: ../report.php");
    exit();
}