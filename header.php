<?php
    require_once "includes/dbh.inc.php";
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
    <title>Violation Detector</title>
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
                    url: "includes/data.inc.php",  // URL of the PHP script that retrieves data from database
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
                            window.location.href = "includes/details.inc.php?id=" + id;
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
            <a href="./index.php">Logout</a>
        </ul>
    </section>
    <div class="wrapper">

