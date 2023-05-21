<?php
require_once 'dbh.inc.php';

$sql = "SELECT videoID, violation, date_time, status FROM video WHERE violation = 'beating the red light' ORDER BY videoID DESC LIMIT 10";

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