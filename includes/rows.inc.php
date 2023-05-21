<?php
require_once 'dbh.inc.php';

$sql = "SELECT * FROM video WHERE violation = 'illegal lane change'";
$result1 = mysqli_query($conn, $sql);

if (!$result1) {
    die("Query failed: " . mysqli_error($conn));
}

$row_count1 = mysqli_num_rows($result1);

$sql = "SELECT * FROM video WHERE violation = 'beating the red light'";
$result2 = mysqli_query($conn, $sql);

if (!$result2) {
    die("Query failed: " . mysqli_error($conn));
}

$row_count2 = mysqli_num_rows($result2);

$sql = "SELECT * FROM video WHERE violation = 'over speeding'";
$result3 = mysqli_query($conn, $sql);

if (!$result3) {
    die("Query failed: " . mysqli_error($conn));
}

$row_count3 = mysqli_num_rows($result3);
$_SESSION['row_count1'] = $row_count1;
$_SESSION['row_count2'] = $row_count2;
$_SESSION['row_count3'] = $row_count3;




