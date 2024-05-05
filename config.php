<?php
$username = "root";
$password = "";
$server = 'localhost';
$db = "localdata";
$domain = "http://localhost/img-store/";

// $domain = "https://desirestore-23.000webhostapp.com/";
// $host = "localhost"; // Use the IP address as the host name
// $username = "id21503930_ayush";
// $password = "Ayush2901@";
// $db = "id21503930_desirestore"; 

$conn = mysqli_connect($server, $username, $password, $db);

// Check if the connection was successful
if (!$conn) {
    echo "Connection unsuccessful";
    die("Not Connected: " . mysqli_connect_error()); 
}
?>