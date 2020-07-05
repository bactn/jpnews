<?php
 $servername = "";
 $username = "";
 $password = "";
 $dbname = "";
 // Parsing connnection string
 foreach ($_SERVER as $key => $value) {
    if (strpos($key, "MYSQLCONNSTR_") !== 0) {
       continue;
    }

    $servername = preg_replace("/^.*Data Source=(.+?);.*$/", "\\1", $value);
    $dbname = preg_replace("/^.*Database=(.+?);.*$/", "\\1", $value);
    $username = preg_replace("/^.*User Id=(.+?);.*$/", "\\1", $value);
    $password = preg_replace("/^.*Password=(.+?)$/", "\\1", $value);
 }

 // Create connection
 $con = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
 } else {
    // echo "connection successful<br/>";
 }
 ?>