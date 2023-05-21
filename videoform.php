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
    $_SESSION['videoedit-id'] = $_POST['edit-id'];
    header("location: /webapp/video.form.php");
}

if (isset($_POST["Delete"])) {
    $_SESSION['videoDelete-id'] = $_POST['Delete-id'];
    header("location: includes/videos.inc.php");
}