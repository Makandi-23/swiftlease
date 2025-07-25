<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');


include('db.php');
require 'db.php';


$sql = "SELECT id, brand, model, size, estimated_daily_charge, image_url, owner_contact, available, created_at FROM cars WHERE available = 1";
$result = $conn->query($sql);


$cars = [];

while ($row = $result->fetch_assoc()) {
    $cars[] = $row;
}


echo json_encode($cars);
?>
