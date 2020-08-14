<?php

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$database = "users";

$conn = new mySqli($servername, $dbUsername, $dbPassword, $database);

if($conn->connect_error){
  die("connection failed". mysqli_connect_error());

}

?>
