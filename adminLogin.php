<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Log In - Violation Detector</title>
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <link href="/webapp/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Lora:wght@400;500&family=Montserrat:ital,wght@0,200;0,400;0,500;1,400&family=Rubik:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        .center {
            padding: 2rem 0;
            text-align: center;
            margin: 0;
        }
        .content-center {
            margin: auto !important;
        }
        .text-center {
            text-align: center !important;
        }
    </style>
</head>

<body class="bg-whitee">
    <div class="">
        <div class="block-weighted m-1 block-vweighted m-1">
            <div class="weight-30 relative">
                <div class="absolute login-flow pt-4 register-form-padding h100">
                    <h1 class="h2 m-0 mb-2 text-center">Welcome Back, Admin!</h1>
                    <form class="px-2" action="includes/admin.inc.php" method="post">
                        <div class=" mx-2 mb-05">
                            <label for="id-number" class=" ">Username</label>
                        </div>
                        <div class=" mx-3 mb-2">
                            <input type="text" class="" maxlength="256" name="id-number" data-name="Id Number" placeholder="" id="input" />
                        </div>
                        <div class=" mx-2 mb-05">
                            <label for="password" class=" ">Password</label>
                        </div>
                        <div class=" mx-3">
                            <input type="password" class="" maxlength="256" name="password" data-name="Password" placeholder="" id="input" />
                        </div>
                        <div class=" mb-05 content-hcenter weight-50">
                                <input type="submit" class="button-dark button-dark-main button-radius2 con"
                                    name="login"  value="login" data-name="login" placeholder="" id="login" />
                        </div>
                    </form>
                    <center class="mt-2"> 
                    <?php
                        if (isset($_GET["error"])) {
                            if ($_GET["error"] == "doesntexist/wrongusername") {
                                echo "<p>You've input the wrong ID number or it has not been registered.</p>";
                            }
                        else if ($_GET["error"] == "wrongpassword") {
                            echo "<p>Wrong password. Please try again.</p>";
                            }
                        else if ($_GET["error"] == "stmtfailed") {
                            echo "<p>Something went wrong. please try again.</p>";
                            }
                        }
                    ?>
                    </center>
                </div>
            </div>
            <div class="weight-70 outline text-center weight-70-bg3 h-100-m mob-hidden">
            </div>

        </div>
        <!--<center>
            <div class="relative hidden mob-shown">
                <div class="w-100 w-100-bg mob-shown h-50px m-0"></div>
                <div class="bg-whitee border-large form-top z-index absolute w-100 top-shadow">
                    <h1 class="h2 my-2 text-center">Welcome Back!</h1>
                    <form class="px-2">
                        <div class=" mx-2 mb-05">
                            <label for="id-number" class=" ">ID Number</label>
                        </div>
                        <div class=" mx-3 mb-2">
                            <input type="text" class="" maxlength="256" name="id-number" data-name="Id Number" placeholder="" id="input" />
                        </div>
                        <div class=" mx-2 mb-05">
                            <label for="password" class=" ">Password</label>
                        </div>
                        <div class=" mx-3">
                            <input type="password" class="" maxlength="256" name="password" data-name="Password" placeholder="" id="input" />
                        </div>
                        <center>
                            <div class=" content-hcenter mt-3 w-50">
                                <input type="submit" class="button-dark button-radius m-0" name="login" value="Login" data-name="Login" placeholder="" id="login" Log In />
                            </div>
                        </center>

                    </form>
                </div>
            </div>
        </center>-->
    </div>
</body>

</html>