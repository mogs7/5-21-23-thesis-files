<?php
require_once 'dbh.inc.php';

if (isset($_POST["DESC1"])) {
    $sql = "SELECT * FROM `ids` order by userID desc";
    $result = mysqli_query($conn, $sql);
}
elseif (isset($_POST["ASC1"])) {
    $sql = "SELECT * FROM `ids` order by userID asc";
    $result = mysqli_query($conn, $sql);
}
else {
    $sql = "SELECT * FROM ids";
    $result = mysqli_query($conn, $sql);
}


$table_html = "";
while ($row = mysqli_fetch_assoc($result)) {
    $table_html .= "<tr>";
    $table_html .= "<td>" . $row['userID'] . "</td>";
    $table_html .= "<td>" . $row['badgeID'] . "</td>";
    $table_html .= "<td>" . $row['adminID'] . "</td>";
    $table_html .= "<td>" . $row['date_time'] . "</td>";
    $table_html .= "</tr>";
}

// output the HTML to the browser

echo $table_html;

// close the database connection
mysqli_close($conn);
// Execute the query
//if (!$result) {
    //die('Error executing query: ' . mysqli_error($conn));
//}

// Fetch data and store it in an array
//$data = array();
//while ($row = mysqli_fetch_assoc($result)) {
    //$data[] = $row;
//}

// Close the database connection
//mysqli_close($conn);

// Return data as JSON
//header('Content-Type: application/json');
//echo json_encode($data);


