<?php
require_once 'dbh.inc.php';

$sql = "SELECT * FROM admins";

// Execute the query
$result = mysqli_query($conn, $sql);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}

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

