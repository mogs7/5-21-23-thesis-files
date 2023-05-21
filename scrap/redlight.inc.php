<?php
if (isset($_POST["Unaddressed"])) {

    session_start();
    $video = $_POST[""]; //from the arduino
    $location = $_POST[""]; //from arduino
    $status = $_POST["Unaddressed"];


    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

}
else if (isset($_POST["Unaddressed"])) {

    session_start();
    $video = $_POST[""]; //from the arduino
    $location = $_POST[""]; //from arduino
    $status = $_POST["Unaddressed"];


    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

}
else {
    header("location: ../redlight.php?error=incpagefailed");
    exit();
}