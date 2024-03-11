<?php
// setting variables 
$host = "localhost";
$username = "root";
$password = "";
$dbname = "web_technologies";
// connecting database 
$conn = mysqli_connect($host, $username, $password, $dbname);
// check if connection fail
if (!$conn) {
    die("Connection Failed:" . mysqli_connect_error());
}
?>