<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ldksports";  

// Create connection
$conn =  new mysqli(hostname: $host,
username: $username,
password: $password,
database: $dbname);
// Check connection

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

?>