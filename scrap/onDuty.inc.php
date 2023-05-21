<?php

session_start();

require_once 'dbh.inc.php';
require_once 'functions.inc.php';

$query = "
UPDATE onduty 
SET last_activity = now() 
WHERE userID = '".$_SESSION["userID"]."'
";

$statement = $connect->prepare($query);

$statement->execute();

?>