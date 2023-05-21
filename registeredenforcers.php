<?php
require_once 'includes/dbh.inc.php';
$query = "SELECT * FROM ids ORDER BY userID DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>

<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <title>Admin - IDs Table</title>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
     <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">-->
     <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" />
     <script type="text/javascript"
          src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>
     <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.css" />
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
                    <h3 align="center">Registered Enforcers Table</h3>
                    <div class="table-responsive">
                         <table id="employee_grid" class="table table-striped table-bordered">
                              <thead>
                                   <tr>
                                        <th>USERID</th>
                                        <th>BADGEID</th>
                                        <th>ADMINID</th>
                                        <th>DATE</th>
                                   </tr>
                              </thead>
                              <?php
                              while ($row = mysqli_fetch_array($result)) {
                                   echo '  
                              <tr>     
                                    <td>' . $row['userID'] . '</td>  
                                    <td>' . $row['badgeID'] . '</td>  
                                    <td>' . $row['adminID'] . '</td>  
                                    <td>' . $row['date_time'] . '</td>   
								
                               </tr>  
                               ';
                              }
                              ;
                              ?>
                         </table>
                    </div>


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
</body>