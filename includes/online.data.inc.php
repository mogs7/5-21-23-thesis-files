<?php
  session_start();
require_once 'dbh.inc.php';
$sql = "SELECT lastName, badgeID, date_time, usersPnumber FROM usersinfo WHERE userStatus = 1";

// Execute the query
$result = mysqli_query($conn, $sql);

// Fetch data and store it in an array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Close the database connection
mysqli_close($conn);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);