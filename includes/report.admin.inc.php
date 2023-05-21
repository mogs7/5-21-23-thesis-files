<?php
require_once 'dbh.inc.php';

session_start();
$ID = $_SESSION["edit-id"];
$deleteID = $_SESSION["delete-id"];
echo $ID;
if (isset($_POST["Edit"])) {
    $lastName = $_POST["last-name"];
    $firstName = $_POST["first-name"];
    $middleName = $_POST["middle-name"];
    $birthday = $_POST["birthday"];
    $licenseNum = $_POST["license-number"];
    $licensePlate = $_POST["license-plate"];
    $regNum = $_POST["registration-number"];
    $violation = $_POST["violation"];

    $sql = "UPDATE report SET 
            lastName = '$lastName',
            firstName =  '$firstName',
            middleName = '$middleName',
            birthday = '$birthday',
            licenseNum = '$licenseNum',
            licensePLate = '$licensePlate',
            regNum = '$regNum',
            violation = '$violation'
            WHERE userID = '$ID'";

            $result = mysqli_query($conn, $sql);
            if($result){
                header("Location: ../report.admin.php?error=none");
            }
}
else {
    $sql = "DELETE FROM report WHERE userID = '$deleteID';";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: ../report.admin.php?error=none");
    }
}
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

