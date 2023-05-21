<?php
require_once 'includes/dbh.inc.php';
$query = "SELECT * FROM video";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin - Video Table</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/webapp/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
</head>

<body>
    <section class="top-nav-admin">
        <div>
            <!--<h2 class="h3-bold mx-2">Violation Detector AdminHub</h2>-->
        </div>
        <!-- <ul class="menu z-index">   
            <a href="./UserPage.php">Dashboard</a>
            <a href="./overspeeding.php">Overspeeding</a>
            <a href="./illegallane.php">Lane Change</a>
            <a href="./redlight.php">Red Light</a>
            <a href="./report.php">Report</a>
            <a href="./index.php">Logout</a>
        </ul>-->
        <ul class="nav nav-tabs nav-justified">
            <li role="presentation" class=""><a href="adminpage.php">Dashboard</a></li>
            <li role="presentation"><a href="registeredenforcers.php">Registered Enforcers</a></li>
            <li role="presentation"><a href="usersinfo.php">User Info</a></li>
            <li role="presentation"><a href="videos.php">Videos</a></li>
            <li role="presentation"><a href="admin.table.php">Admin</a></li>
            <li role="presentation"><a href="index.php">Logout</a></li>
        </ul>
    </section>
    <br /><br />
    <div class="table-data">
        <div class="col-md-9 col-md-10 col-md-offset-2 main">
            <h1 class="page-header">Dashboard</h1>
            <div class="order" id="box">
                <div class="head">
                    <h3>Register/ Remove an Enforcer</h3>
                </div>
                <form action="includes/admin.regrem.inc.php" method="post">
                    <div class="block-weighted mb-1 block-vweighted mob-mb-1">
                        <div class="weight-50" id="order-1">
                            <p class="mb-1">Please enter the Enforcer's badge number.</p>
                            <div class=" mx-2 mb-05">
                                <label for="id-number" class=" ">Badge Number</label>
                            </div>
                            <div class=" mx-3 mb-2">
                                <input type="text" class="" maxlength="256" name="id-number" data-name="Id Number"
                                    placeholder="" id="input" />
                            </div>
                        </div>
                        <div class="weight-50" id="order-2">
                            <p class="mb-1">Registering Only: Please enter your admin ID.</p>
                            <div class=" mx-2 mb-05">
                                <label for="admin-id" class=" ">Admin ID Number</label>
                            </div>
                            <div class=" mx-3">
                                <input type="text" class="" maxlength="256" name="admin-id" data-name="Admind Id"
                                    placeholder="" id="input" />
                            </div>
                        </div>
                    </div>
                    <div class="block-weighted">
                        <div class="weight-50">
                            <input type="submit" class="btn btn-success" name="Register" value="Register"
                                data-name="Register" placeholder="" id="Register" />
                        </div>
                        <div class="weight-50">
                            <input type="submit" class="btn btn-danger" name="Remove" value="Remove" data-name="Remove"
                                placeholder="" id="Remove" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="mb-1">
                    <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "incomplete") {
                                echo "<p>Please fill in all fields.</p>";
                            }
                        else if ($_GET["error"] == "doesntexist/wrongusername") {
                            echo "<p>Please check the information entered.</p>";
                            }
                            else if ($_GET["error"] == "checkdetails") {
                                echo "<p>Please check the information entered.</p>";
                                }
                        else if ($_GET["error"] == "none") {
                            echo "<p>Saved!</p>";
                            }
                        }
                    ?>
                    </div>
        </div>
</body>

</html>