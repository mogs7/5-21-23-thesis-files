<?php
session_start();
ini_set('display_errors', 0);
require_once 'includes/dbh.inc.php';
$video = $_SESSION['videoURL'];
$id = $_SESSION['vID'];
// Specify the file path on your computer
$file_path = "sample.mp4";
$date_time = date('Y-m-d H:i:s');
// Insert the file path or URL into the database
$query = "INSERT INTO video (url, date_time) VALUES ('$file_path', '$date_time')";
// Execute the query
mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Violation Detector - Illegal Lane Change</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="/webapp/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // Define a function to fetch data from server and update the web page
            function fetchData() {
                $.ajax({
                    url: "includes/illegal.data.inc.php",  // URL of the PHP script that retrieves data from database
                    dataType: "json",   // Data type expected from server
                    success: function(data) {
                        // Update the web page with the retrieved data
                        var html = "";
                        $.each(data, function(index, item) {
                            html += '<button class="status-button" data-id ="' + item.videoID + '">';
                            html += '<div class="block-hweighted block-weighted ml-2">';
                            html += '<div class="weight-20">';
                            html += '<h3 class="h4-bold text-center">Details: </h3>';
                            html += '</div>';
                            html += '<div class="weight-80">';
                            html += '<p class="h4">' + item.violation + ', ' + item.date_time + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '<div class="block-hweighted block-weighted ml-2 mb-1">';
                            html += '<div class="weight-20">';
                            html += '<h3 class="h4-bold text-center">Status: </h3>';
                            html += '</div>';
                            html += '<div class="weight-80">';
                            html += '<p class="h4">' + item.status + '</p>';
                            html += '</div>';
                            html += '</div>';
                            html += '</button>';
                            html += '<div class="underline">';
                        });
                        $("#data-container").html(html);
                        $(".status-button").click(function() {
                            var id = $(this).data("id");
                            window.location.href = "includes/illegal/details.inc.php?id=" + id;
                        });
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
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
            <a href="./report.php">Report</a>
            <a href="./logout.inc.php">Logout</a>
        </ul>
    </section>
    <div class="wrapper">


    <center>
        <h1 class="h2 my-2">Illegal Lane Change</h1>
    </center>
    <div class="block-vweighted block-weighted mt-2">
        <div class="weight-50 " id="order-2">
            <div class="content-hcenter h-min-200 mb-2 ">
                <div class="logs-info h-min-200">
                    <div class="content-hcenter h-min-100 top-half">
                        <div class="">
                            <h2 class="h3-bold text-center">Logs</h2>
                        </div>
                    </div>
                    <div class="h-min-350" id="data-container">

                    </div>
                </div>
            </div>
        </div>
        <div class="weight-50 mr-4 order-1 mob-m-0" id="order-1">
            <div class="content-hcenter h-min-200 mb-2 ">
                <div class="video h-min-200">
                    <div class="content-hcenter h-min-100 top-half">
                        <div class="">
                            <h2 class="h3-bold text-center">ID: <?php echo $id ?></h2>
                        </div>
                    </div>
                    <center>
                        <video width="85%" height="350px" controls="controls"/>
                            <source src="<?php echo $video ?>" type="video/mp4">
                        </video>
                    </center>
                    <center>
                        <form class="block-vweighted block-weighted mt-1 mx-1 mb-3" action="includes/illegal.status.inc.php" method="post">
                            <div class="weight-50 mob-mb-1" id="order-1">
                            <div class=" text-justify">
                                <label for="last-name" class=" text-dark">Last Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="mb-1" maxlength="256" name="last-name" data-name="last-name"
                                    placeholder="" id="input" />
                            </div>
                            <input type="submit" class="button-dark-main button-radius-violation" name="Unaddressed"
                            value="Unaddressed" data-name="Unaddressed" placeholder="" id="Unaddressed" />
                                </div>
                                <div class="weight-50 pl-1" id="order-3">
                                <div class=" text-justify">
                                <label for="birthday" class="text-dark ">Birthday</label>
                            </div>
                            <div class="">
                                <input style="width: 100%;" type="date" class="mb-1" maxlength="256" name="birthday"
                                    data-name="birthday" placeholder="" id="date" />
                            </div>
                                    <input type="submit" class="button-dark-main button-radius-violation" name="Reviewed"
                                        value="Reviewed" data-name="Reviewed" placeholder="" id="Reviewed" />
                                </div>

                        </form>
                    </center>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

</html>