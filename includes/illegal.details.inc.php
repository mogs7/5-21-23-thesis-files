<?php
session_start();
require_once 'dbh.inc.php';

// Retrieve the ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("Error: ID not provided in URL.");
}

// Retrieve the details of the selected item from the database
$sql = "SELECT * FROM video WHERE videoID = '$id'";
$result = mysqli_query($conn, $sql);

// Check if the query was successful
if (!$result) {
    die("Error retrieving data: " . mysqli_error($conn));
}

// Fetch the data and store it in a variable
$row = mysqli_fetch_assoc($result);
$_SESSION['videoURL'] = $row['url'];
$_SESSION['vID'] = $row['videoID'];

// Close the database connection
mysqli_close($conn);

header("Location: ../illegallane.php");
exit();