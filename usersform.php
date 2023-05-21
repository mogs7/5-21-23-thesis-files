<?php  
$serverName = "localhost";
$dbUserName = "root";
$dbPassword = "";
$dbName = "ViolationDetector";

date_default_timezone_set('Asia/Manila');

$conn = mysqli_connect($serverName, $dbUserName, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: ". mysqli_connect_error());
}
session_start();

if (isset($_POST["Edit"])) {
    $_SESSION['edit-id'] = $_POST['edit-id'];
    header("location: /webapp/usersinfo.form.php");
}

if (isset($_POST["Delete"])) {
    $_SESSION['Delete-id'] = $_POST['Delete-id'];
    header("location: includes\usersinfo.inc.php");
}

 

