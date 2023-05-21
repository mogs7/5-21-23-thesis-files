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
    header("location: /webapp/reportform.php");
}

if (isset($_POST["Delete"])) {
    $_SESSION['delete-id'] = $_POST['delete-id'];
    header("location: includes/report.admin.inc.php");
}