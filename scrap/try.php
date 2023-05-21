<?php  
 require_once 'includes/dbh.inc.php';
 $query ="SELECT * FROM ids ORDER BY userID DESC";  
 $result = mysqli_query($conn, $query);  
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Admin - Ids Table</title>  
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  
           <link rel="stylesheet" href="style.css">
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
        <input class="mt-1" id="menu-toggle" type="checkbox" />
        <label class='menu-button-container mt-1' for="menu-toggle">
        <div class='menu-button'></div>
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
            <li role="presentation" class="active"><a href="adminpage.php">Dashboard</a></li>
            <li role="presentation"><a href="registeredenforcers.php">Registered Enforcers</a></li>
            <li role="presentation"><a href="usersinfo.php">User Info</a></li>
            <li role="presentation"><a href="videos.php">Videos</a></li>
            <li role="presentation"><a href="report.admin.php">Reports</a></li>
            <li role="presentation"><a href="admin.table.php">Admin</a></li>
        </ul>
    </section>
           <br /><br />  
           <div class="container">  
                <h3 align="center">Registered IDs Table</h3>  
                <br />  
                <div class="table-responsive">  
                     <table id="employee_data" class="table table-striped table-bordered">  
                          <thead>  
                               <tr>  
                               <th>USERID</th>
								<th>BADGEID</th>
								<th>ADMINID</th>
								<th>DATE</th>  
                               </tr>  
                          </thead>  
                          <?php  
                          while($row = mysqli_fetch_array($result))  
                          {  
                               echo '  
                               <tr>  
                                    <td>'.$row['userID'].'</td>  
                                    <td>'.$row['badgeID'].'</td>  
                                    <td>'.$row['adminID'].'</td>  
                                    <td>'.$row['date_time'].'</td>  
                               </tr>  
                               ';  
                          }  
                          ?>  
                     </table>  
                </div>  
           </div>  
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  

 </script>  

<th>USERID</th>
								<th>FIRSTNAME</th>
								<th>LASTNAME</th>
								<th>BADGEID</th>
								<th>PHONE NUMBER</th>
                                <th>PASSWORD</th>