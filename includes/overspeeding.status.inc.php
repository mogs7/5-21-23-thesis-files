<?php
session_start();
require_once 'dbh.inc.php';

// Check if vID is set in the session
if (isset($_SESSION['vID'])) {
    $id = $_SESSION['vID'];
} else {
    // If vID is not set in the session, redirect the user and display an error message
    header("Location: ../overspeeding.php?error=emptyvID");
    exit();
}

// Check if vID is set in the session
if (isset($_SESSION["id-number"])) {
    $badgeID = $_SESSION["id-number"];
} else {
    // If vID is not set in the session, redirect the user and display an error message
    header("Location: ../overspeeding.php?error=emptybadgeID");
    exit();
}

if (isset($_POST["Reviewed"])) {
    $status = $_POST["Reviewed"];
    $lastName = $_POST["last-name"];
    $firstName = $_POST["first-name"];
    $middleName = $_POST["middle-name"];
    $birthday = $_POST["birthday"];
    $licenseNum = $_POST["license-number"];
    $licensePlate = $_POST["license-plate"];
    $regNum = $_POST["registration-number"];
    $date_time = date("Y-m-d H:i:s");

    require_once 'functions.inc.php';

    if (emptyInputStatus($licenseNum, $status, $lastName, $firstName, $birthday, $licensePlate, $regNum) !== false) {
        header("Location: ../overspeeding.php?error=incompletedetails");
        exit();
    }

    overreviewed($conn, $id, $licenseNum, $status, $badgeID, $lastName, $firstName, $middleName, $birthday, $licensePlate, $regNum, $date_time);
    header("Location: ../overspeeding.php?error=none");

}
if (isset($_POST["Unaddressed"])) {
    $status = $_POST["Unaddressed"];
    $licensePlate = $_POST["license-plate"];
    require_once 'functions.inc.php';
    $date_time = date("Y-m-d H:i:s");

    if (emptyInputStatusUn($licensePlate) !== false) {
        header("Location: ../overspeeding.php?error=incompletedetails");
        exit();
    }
    overunaddressed($conn, $id, $status, $badgeID, $licensePlate, $date_time,);
    header("Location: ../overspeeding.php?error=none");
}

mysqli_close($conn);