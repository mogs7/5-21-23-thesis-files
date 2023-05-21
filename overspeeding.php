<?php
session_start();
ini_set('display_errors', 0);
require_once 'includes/dbh.inc.php';
$video = $_SESSION['videoURL'];
$id = $_SESSION['vID'];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Violation Detector - Overspeeding</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="/webapp/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function () {
            // Define a function to fetch data from server and update the web page
            function fetchData(data) {
                $.ajax({
                    url: "includes/overspeeding.data.inc.php",  // URL of the PHP script that retrieves data from database
                    dataType: "json",   // Data type expected from server
                    success: function (data) {
                        // Update the web page with the retrieved data
                        var html = "";
                        $.each(data, function (index, item) {
                            html += '<tr>';
                            html += '<td>' + item.videoID + '</td>';
                            html += '<td>' + item.violation + '</td>';
                            html += '<td>' + item.date_time + '</td>';
                            html += '<td>' + item.status + '</td>';
                            html += '<td><button class="status-button" data-id="' + item.videoID + '">View</button></td>';
                            html += '</tr>';
                        });
                        $("#data-rows-container").html(html);
                        $(".status-button").click(function () {
                            var id = $(this).data("id");
                            window.location.href = "includes/overspeeding.details.inc.php?id=" + id;
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        // Handle errors if any
                        console.log("Error: " + textStatus + ": " + errorThrown);
                    }
                });
            }
            // Call the fetchData function every 5 seconds to update the web page
            setInterval(fetchData, 5000);
        });
    </script>

    <link
        href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Lora:wght@400;500&family=Montserrat:ital,wght@0,200;0,400;0,500;1,400&family=Rubik:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        .center {
            padding: 2rem 0;
            text-align: center;
            margin: 0;
        }
    </style>
</head>

<body class="bg-whitee">
    <section class="top-nav">
        <div>
            <h2 class="h3-bold">Violation Detector</h2>
        </div>
        <input class="mt-1" id="menu-toggle" type="checkbox" />
        <label class='menu-button-container mt-1' for="menu-toggle">
            <div class='menu-button'></div>
        </label>
        <ul class="menu z-index">
            <a href="./UserPage.php">Dashboard</a>
            <a href="./overspeeding.php">Overspeeding</a>
            <a href="./illegallane.php">Lane Change</a>
            <a href="./redlight.php">Red Light</a>
            <a href="./index.php">Logout</a>
        </ul>
    </section>
    <div class="wrapper">


        <center>
            <h1 class="h2 my-2">Overspeeding</h1>
        </center>
        <div class="block-vweighted block-weighted mt-2">
            <div class="weight-50 px-2 " id="order-2">
            <div class="content-hcenter h-min-100 top-half">
                            <div class="">
                                <h2 class="h3-bold text-center">Logs</h2>
                            </div>
                        </div>
                <div class=" mb-2" style="overflow: auto; max-height: 400px; margin-bottom: 3rem">
                    <div class="">

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Violation</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="data-rows-container">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="weight-50 mr-4 order-1 mob-m-0" id="order-1">
                <center class="mb-1">
                    <?php
                    if (isset($_GET["error"])) {
                        if ($_GET["error"] == "emptyvID") {
                            echo "<p>Please click a video.</p>";
                        } else if ($_GET["error"] == "incompletedetails") {
                            echo "<p>Fill in all fields!</p>";
                        } else if ($_GET["error"] == "none") {
                            echo "<p>Saved!</p>";
                        } else if ($_GET["error"] == "invalidlicenseNum") {
                            echo "<p>Please enter a valid license number.</p>";
                        } else if ($_GET["error"] == "invalidlicensePlate") {
                            echo "<p>Please enter a valid license plate.</p>";
                        } else if ($_GET["error"] == "invalidregNum") {
                            echo "<p>Please enter a valid registration number.</p>";
                        } else if ($_GET["error"] == "stmtfailed") {
                            echo "<p>Something went wrong. please try again.</p>";
                        }else if ($_GET["error"] == "cannotchange") {
                            echo "<p>Already registered as 'Reviewed'.</p>";
                        }
                    }
                    ?>
                </center>
                <div class="content-hcenter h-min-200 mb-2 ">
                    <div class="video h-min-200">
                        <div class="content-hcenter h-min-100 top-half">
                            <div class="">
                                <h2 class="h3-bold text-center">ID:
                                    <?php echo $id ?>
                                </h2>
                            </div>
                        </div>
                        <center>
                            <video width="85%" height="350px" controls="controls" />
                            <source src="<?php echo $video ?>" type="video/mp4">
                            </video>
                        </center>
                        <div class=" mob-w-100">
                            <form class="px-2" action="includes/overspeeding.status.inc.php" method="post">
                                <div class="block-weighted block-vweighted mb-2 mob-mb-1">
                                    <div class="weight-25" id="order-1">
                                        <div class=" text-justify">
                                            <label for="last-name" class=" text-dark">Last Name</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="last-name"
                                                data-name="last name" placeholder="" id="input" />
                                        </div>
                                    </div>
                                    <div class="weight-25 mx-1 mob-m-0" id="order-2">
                                        <div class="text-justify">
                                            <label for="first-name" class="text-dark ">First Name</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="first-name"
                                                data-name="first name" placeholder="" id="input" />
                                        </div>
                                    </div>
                                    <div class="weight-25" id="order-3">
                                        <div class=" text-justify">
                                            <label for="middle-name" class="text-dark ">Middle Name</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="middle-name"
                                                data-name="middle name" placeholder="" id="input" />
                                        </div>
                                    </div>
                                    <div class="ml-1 weight-25 mob-m-0" id="order-4">
                                        <div class=" text-justify">
                                            <label for="birthday" class="text-dark ">Birthday</label>
                                        </div>
                                        <div class="">
                                            <input style="width: 100%;" type="date" class="" maxlength="256"
                                                name="birthday" data-name="birthday" placeholder="" id="date" />
                                        </div>
                                    </div>
                                </div>
                                <div class="block-weighted block-vweighted mb-2 mob-mb-1">
                                    <div class="weight-33" id="order-5">
                                        <div class=" text-justify">
                                            <label for="license-number" class=" text-dark">Driver's License ID</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="license-number"
                                                data-name="License Number" placeholder="" id="input" />
                                        </div>
                                    </div>
                                    <div class="weight-33 mx-1 mob-m-0" id="order-6">
                                        <div class="text-justify">
                                            <label for="license-plate" class="text-dark ">License Plate No</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="license-plate"
                                                data-name="License Plate" placeholder="" id="input" />
                                        </div>
                                    </div>
                                    <div class="weight-33" id="order-7">
                                        <div class=" text-justify">
                                            <label for="registration-number" class="text-dark ">Registration
                                                No</label>
                                        </div>
                                        <div class="">
                                            <input type="text" class="" maxlength="256" name="registration-number"
                                                data-name="Registration Number" placeholder="" id="input" />
                                        </div>
                                    </div>
                                </div>
                                <center>
                                    <div class="block-vweighted block-weighted mb-2">
                                        <div class="weight-50 mob-mb-1" id="order-1">
                                            <input type="submit" class="button-dark-main button-radius-violation"
                                                name="Unaddressed" value="Unaddressed" data-name="Unaddressed"
                                                placeholder="" id="Unaddressed" />
                                        </div>
                                        <div class="weight-50" id="order-3">
                                            <input type="submit" class="button-dark-main button-radius-violation"
                                                name="Reviewed" value="Reviewed" data-name="Reviewed" placeholder=""
                                                id="Reviewed" />
                                        </div>
                                    </div>
                                </center>

                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>

</html>