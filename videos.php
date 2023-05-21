<?php
session_start();
require_once 'includes/dbh.inc.php';
$query = "SELECT * FROM video";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>

<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <title>Admin - Video Table</title>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
     <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
     <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" />
     <script type="text/javascript"
          src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="/webapp/css/style.css">
     <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

     <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/webapp/css/style.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />

</head>


<body class="">
     <section class="top-nav-admin">
          <div>
               <!--<h2 class="h3-bold mx-2">Violation Detector AdminHub</h2>-->
          </div>
          </label>
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
     <main>
          <div class="container">
               <div class="">
                    <h3 align="center">Video Table</h3>
                    <div class="table-responsive">
                         <table id="employee_grid" class="table table-striped table-bordered">
                              <thead>
                                   <tr>
                                        <th>ACTION</th>
                                        <th>VIEW</th>
                                        <th>VIDEO_ID</th>
                                        <th>LICENSE NO</th>
                                        <th>VIOLATION</th>
                                        <th>URL</th>
                                        <th>DATE</th>
                                        <th>STATUS</th>
                                        <th>BADGE_ID</th>
                                        <th>LAST NAME</th>
                                        <th>FIRST NAME</th>
                                        <th>MIDDLE NAME</th>
                                        <th>BIRTHDAY</th>
                                        <th>LICENSE PLATE</th>
                                        <th>REGISTER NO</th>
                                   </tr>
                              </thead>
                              <?php
                              while ($row = mysqli_fetch_array($result)) {
                                   $fieldname1 = $row['videoID'];
                                   $editdata = '<div class="block-weighted block-vweighted mob-mb-1">
                                        <form action="videoform.php" method="post">
                                        <div class="mb-05 content-hcenter weight-50">
                                        <input type="hidden" class="" maxlength="256" name="edit-id" id="input" value="' . $fieldname1 . '" />
                                        <input type="submit" class="btn btn-default" name="Edit" value="Edit" id="Edit"/></form>';

                                   $deletedata = '<div class="block-weighted block-vweighted mob-mb-1">
                                        <form action="videoform.php" method="post">
                                        <div class="mb-05 content-hcenter weight-50">
                                        <input type="hidden" class="" maxlength="256" name="Delete-id" data-name=""
                                        placeholder="" id="input" value="' . $fieldname1 . '" />
                                        <input type="submit" class="btn btn-danger" name="Delete" value="Delete"  data-name="Delete" placeholder="" id="Delete"/></form>';
                                   $viewdata = '<div class="block-weighted block-vweighted mob-mb-1">
                                        <div class="mb-05 content-hcenter weight-50">
                                        <button class="btn btn-primary view-video" data-url="' . $row['url'] . '">View</button>';
                                   echo '  
                              <tr>  
                              <td>' . $editdata . ' ' . $deletedata . '</td>   
                              <td>' . $viewdata . '</td>    
                                   <td>' . $row['videoID'] . '</td>  
                                   <td>' . $row['licenseNum'] . '</td>   
                                   <td>' . $row['violation'] . '</td>  
							<td>' . $row['url'] . '</td>  
							<td>' . $row['date_time'] . '</td>  
							<td>' . $row['status'] . '</td>  
							<td>' . $row['badgeID'] . '</td>  
                                   <td>' . $row['lastName'] . '</td> 
                                   <td>' . $row['firstName'] . '</td>  
                                   <td>' . $row['middleName'] . '</td> 
                                   <td>' . $row['birthday'] . '</td>    
                                   <td>' . $row['licensePLate'] . '</td>  
                                   <td>' . $row['regNum'] . '</td>  
                               </tr>  
                               ';
                              }
                              ;
                              ?>
                         </table>
                    </div>
                    <center>
                         <div class="tint">
                              <div id="myModal" class="modal mt-3 mx-3">
                                   <!-- Modal content -->
                              </div>
                         </div>
                    </center>
     </main>
     <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
     <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
     <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
     <script>
          $(document).ready(function () {
               $("#employee_grid").DataTable({
                    aaSorting: [],
                    searching: true,
                    responsive: true,
                    "bLengthChange": true,
                    lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
                    columnDefs: [
                         {
                              responsivePriority: 1,
                              targets: 0
                         },
                         {
                              responsivePriority: 2,
                              targets: -1
                         },
                    ],
                    dom: 'Bfrtip',
                    buttons: [
                         'pageLength',
                         {
                              extend: 'excel',
                              exportOptions: {
                                   columns: ':not(.notexport)'
                              }
                         },
                         {
                              extend: 'csv',
                              exportOptions: {
                                   columns: ':not(.notexport)'
                              }
                         },
                         {
                              extend: 'print',
                              exportOptions: {
                                   columns: ':not(.notexport)'
                              }
                         }
                    ]
               });

          });
     </script>
     <!-- Include jQuery library -->
     <!-- Include Bootstrap JavaScript library -->
     <script>
          // Get the modal
          var modal = document.getElementById("myModal");

          // Get all the "View Data" buttons
          var viewButtons = document.getElementsByClassName("view-video");

          // Get the <span> element that closes the modal
          var span = document.getElementsByClassName("close")[0];

          // Function to open the modal and set the video source
          function openModal(url) {
               var videoPlayer = document.createElement("video");
               videoPlayer.setAttribute("controls", "controls");
               videoPlayer.setAttribute("autoplay", "autoplay");

               var source = document.createElement("source");
               source.setAttribute("src", url);
               source.setAttribute("type", "video/mp4");

               videoPlayer.appendChild(source);

               // Remove existing modal content
               while (modal.firstChild) {
                    modal.removeChild(modal.firstChild);
               }

               var modalContent = document.createElement("div");
               modalContent.setAttribute("class", "content");

               var closeSpan = document.createElement("span");
               closeSpan.setAttribute("class", "close");
               closeSpan.innerHTML = "&times;";

               closeSpan.onclick = function () {
                    modal.style.display = "none";
               };

               var textParagraph = document.createElement("p");

               modalContent.appendChild(closeSpan);
               modalContent.appendChild(videoPlayer);
               modalContent.appendChild(textParagraph);

               modal.appendChild(modalContent);

               modal.style.display = "block";
          }

          // Attach click event listeners to the "View Data" buttons
          for (var i = 0; i < viewButtons.length; i++) {
               viewButtons[i].addEventListener("click", function () {
                    var url = this.getAttribute("data-url");
                    openModal(url);
               });
          }

          // When the user clicks on <span> (x), close the modal
          span.onclick = function () {
               modal.style.display = "none";
          }

          // When the user clicks anywhere outside of the modal, close it
          window.onclick = function (event) {
               if (event.target == modal) {
                    modal.style.display = "none";
               }
          }
     </script>
</body>