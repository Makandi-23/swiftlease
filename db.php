<?php
$servername = "localhost";
$username = "root";  
$password = ""; 
$dbname = "swiftlease_car_rental";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
