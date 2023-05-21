<?php  
session_start();
 require_once 'includes/dbh.inc.php';
 $ID = $_SESSION["edit-id"];
 $sql = "SELECT * FROM report where userID = '$ID'";
 $result = mysqli_query($conn, $sql);
 $data = mysqli_fetch_array($result);
 ?>  
 <!DOCTYPE html>  
 <html>  
      <head>  
           <title>Admin - Report Table</title>  
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
            <li role="presentation"><a href="report.admin.php">Reports</a></li>
            <li role="presentation"><a href="admin.table.php">Admin</a></li>
            <li role="presentation"><a href="index.php">Logout</a></li>
        </ul>
    </section>
           <br /><br />  
           <div class="container">  
                <h3 align="center">Report Edit/ Delete</h3>  
                <center>
                <?php
            ?>
               <div class=" shadow border mt-3 p-3 mob-w-100" id="form-container">
               <form class="px-2" action="includes/report.admin.inc.php" method="post">
                    <div class="block-weighted block-vweighted mb-2 mob-mb-1">
                        <div class="weight-25" id="order-1">
                            <div class=" text-justify">
                                <label for="last-name" class=" text-dark">Last Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="last-name" data-name="last name"
                                    placeholder="" id="input" value="<?php echo $data['lastName']  ?>" />
                            </div>
                        </div>
                        <div class="weight-25 mx-1 mob-m-0" id="order-2">
                            <div class="text-justify">
                                <label for="first-name" class="text-dark ">First Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="first-name" data-name="first name"
                                    placeholder="" id="input" value="<?php echo $data['firstName']  ?>" />
                            </div>
                        </div>
                        <div class="weight-25" id="order-3">
                            <div class=" text-justify">
                                <label for="middle-name" class="text-dark ">Middle Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="middle-name" data-name="middle name"
                                    placeholder="" id="input" value="<?php echo $data['middleName']  ?>" />
                            </div>
                        </div>
                        <div class="ml-1 weight-25 mob-m-0" id="order-4">
                            <div class=" text-justify">
                                <label for="birthday" class="text-dark ">Birthday</label>
                            </div>
                            <div class="">
                                <input style="width: 100%;" type="date" class="" maxlength="256" name="birthday"
                                    data-name="birthday" placeholder="" id="date" value="<?php echo $data['birthday']  ?>" />
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
                                    data-name="License Number" placeholder="" id="input" value="<?php echo $data['licenseNum']  ?>" />
                            </div>
                        </div>
                        <div class="weight-33 mx-1 mob-m-0" id="order-6">
                            <div class="text-justify">
                                <label for="license-plate" class="text-dark ">License Plate Number</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="license-plate"
                                    data-name="License Plate" placeholder="" id="input" value="<?php echo $data['licensePLate']  ?>" />
                            </div>
                        </div>
                        <div class="weight-33" id="order-7">
                            <div class=" text-justify">
                                <label for="registration-number" class="text-dark ">Registration Number</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="registration-number"
                                    data-name="Registration Number" placeholder="" id="input" value="<?php echo $data['regNum']?>" />
                            </div>
                        </div>
                    </div>
                    <div class="block-weighted block-vweighted relative mb-2 mob-mb-1">
                        <div class="weight-30" id="order-8">
                            <div class="">
                                <div class="text-justify">
                                    <label class="" for="violation">Violation</label>
                                </div>
                                <div>
                                    <select id="violation" name="violation" value="<?php echo $data['violation']  ?>">
                                        <option value="Overspeeding">Overspeeding</option>
                                        <option value="Beating the Red Light">Beating the Red Light</option>
                                        <option value="Illegal Lane Change">Illegal Lane Change</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="block-weighted block-vweighted mt-3 mb-2 mob-mb-1">
                            <div class="weight-50 content-hcenter mb-1" id="order-9">
                                <input type="submit" class=" btn btn-default mr-2 " name="Edit" id="Edit"
                                value="Save" data-name="Edit" placeholder="" id="Edit" />
                            </div>
                    </div>
                </form>
                <center class="mt-2">
                    <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "emptyinput") {
                                echo "<p>Fill in all fields!</p>";
                            }
                        else if ($_GET["error"] == "invalidlicenseNum") {
                            echo "<p>Please enter a valid license number.</p>";
                            }
                        else if ($_GET["error"] == "invalidlicensePlate") {
                            echo "<p>Please enter a valid license plate.</p>";
                            }
                        else if ($_GET["error"] == "invalidregNum") {
                            echo "<p>Please enter a valid registration number.</p>";
                            }
                        else if ($_GET["error"] == "stmtfailed") {
                            echo "<p>Something went wrong. please try again.</p>";
                            }
                        else if ($_GET["error"] == "none") {
                            echo "<p>Saved.</p>";
                            }
                        }
                    ?>
                    </center>
               </div>
          </center>
                <center class="mt-2">
                    <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "emptyinput") {
                                echo "<p>Fill in all fields!</p>";
                            }
                        else if ($_GET["error"] == "invalidlicenseNum") {
                            echo "<p>Please enter a valid license number.</p>";
                            }
                        else if ($_GET["error"] == "invalidlicensePlate") {
                            echo "<p>Please enter a valid license plate.</p>";
                            }
                        else if ($_GET["error"] == "invalidregNum") {
                            echo "<p>Please enter a valid registration number.</p>";
                            }
                        else if ($_GET["error"] == "stmtfailed") {
                            echo "<p>Something went wrong. please try again.</p>";
                            }
                        else if ($_GET["error"] == "none") {
                            echo "<p>Saved.</p>";
                            }
                        }
                    ?>
                    </center>
            </div>
      </body>  
 </html>  
 <script>  
 $(document).ready(function(){  
      $('#employee_data').DataTable();  
 });  
 </script>  
<!--<script>
  function toggleDiv() {
    var formDiv = document.getElementById("formDiv");
    formDiv.style.display = (formDiv.style.display == "none") ? "block" : "none";
    
    // Set session variable
  }
</script>-->

