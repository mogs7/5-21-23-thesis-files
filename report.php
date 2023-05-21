<?php
    include_once 'header.php';
?>

<center class="mx-1">
        <div class="mb-1 border">
            <h1 class="h2 text-dark p-2 pb-1">
                Ticketing Form
            </h1>
            <p class="paragraph text-dark px-2 pb-2">Please review the information before proceeding to submit. Thank
                you!</p>
            <div class="w-60 shadow border p-3 mob-w-100">
                <form class="px-2" action="includes/report.inc.php" method="post">
                    <div class="block-weighted block-vweighted mb-2 mob-mb-1">
                        <div class="weight-25" id="order-1">
                            <div class=" text-justify">
                                <label for="last-name" class=" text-dark">Last Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="last-name" data-name="last name"
                                    placeholder="" id="input" />
                            </div>
                        </div>
                        <div class="weight-25 mx-1 mob-m-0" id="order-2">
                            <div class="text-justify">
                                <label for="first-name" class="text-dark ">First Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="first-name" data-name="first name"
                                    placeholder="" id="input" />
                            </div>
                        </div>
                        <div class="weight-25" id="order-3">
                            <div class=" text-justify">
                                <label for="middle-name" class="text-dark ">Middle Name</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="middle-name" data-name="middle name"
                                    placeholder="" id="input" />
                            </div>
                        </div>
                        <div class="ml-1 weight-25 mob-m-0" id="order-4">
                            <div class=" text-justify">
                                <label for="birthday" class="text-dark ">Birthday</label>
                            </div>
                            <div class="">
                                <input style="width: 100%;" type="date" class="" maxlength="256" name="birthday"
                                    data-name="birthday" placeholder="" id="date" />
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
                                <label for="license-plate" class="text-dark ">License Plate Number</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="license-plate"
                                    data-name="License Plate" placeholder="" id="input" />
                            </div>
                        </div>
                        <div class="weight-33" id="order-7">
                            <div class=" text-justify">
                                <label for="registration-number" class="text-dark ">Registration Number</label>
                            </div>
                            <div class="">
                                <input type="text" class="" maxlength="256" name="registration-number"
                                    data-name="Registration Number" placeholder="" id="input" />
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
                                    <select id="violation" name="violation">
                                        <option value="Overspeeding">Overspeeding</option>
                                        <option value="Beating the Red Light">Beating the Red Light</option>
                                        <option value="Illegal Lane Change">Illegal Lane Change</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="weight-30 absolute right no-absolute" id="order-9">
                            <div class="">
                                <div class="absolute content-hcenter no-absolute">
                                    <input type="submit" class="button-dark button-dark-main button-radius right button-mob" name="report"
                                        value="Submit" data-name="report" placeholder="" id="report" />
                                </div>
                            </div>
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
        </div>
    </center>
</body>

</html>