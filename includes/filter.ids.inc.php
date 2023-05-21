<?php
// establish database connection
require_once 'dbh.inc.php';

// check if form submitted
if (isset($_POST["Filter"])) {
    $firstName = $_POST["first-name"];
    $lastName = $_POST["last-name"];
    $badgeID = $_POST["id-number"];
    $userPhone = $_POST["phone-number"];
    $userPwd = $_POST["password"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (emptyInputSignup($firstName, $lastName, $badgeID, $userPhone, $userPwd) !== false) {
        header("location: ../register.php?error=emptyinput");
        exit();
    }
    if (invaliduserID($badgeID) !== false) {
        header("location: ../register.php?error=invaliduserID");
        exit();
    }
    if (invalidPhone($userPhone) !== false) {
        header("location: ../register.php?error=invalidPhoneNumber");
        exit();
    }
    if (badgeIDCheck($conn, $badgeID) !== false) {
        header("location: ../register.php?error=IDdoesntexit");
        exit();
    }
    if (badgeIDExists($conn, $badgeID) !== false) {
        header("location: ../register.php?error=usernametaken");
        exit();
    }

    createUser($conn, $firstName, $lastName, $badgeID, $userPhone, $userPwd);
}
if (isset($_GET['name']) || isset($_GET['category']) || isset($_GET['minprice']) || isset($_GET['maxprice'])) {
    // construct the SQL query
    $query = "SELECT * FROM products WHERE 1=1";
    
    // add filters to the query
    if (!empty($_GET['name'])) {
        $query .= " AND name LIKE '%" . $_GET['name'] . "%'";
    }
    if (!empty($_GET['category'])) {
        $query .= " AND category = '" . $_GET['category'] . "'";
    }
    if (!empty($_GET['minprice'])) {
        $query .= " AND price >= " . $_GET['minprice'];
    }
    if (!empty($_GET['maxprice'])) {
        $query .= " AND price <= " . $_GET['maxprice'];
    }
    
    // execute the query
    $result = mysqli_query($conn, $query);
    
    // display the results in a table
    echo "<table>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['category'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// close database connection
mysqli_close($conn);
?>
