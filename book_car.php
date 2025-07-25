<?php
require_once 'db.php'; 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo "Unauthorized. Please log in.";
    exit();
}

$user_id = $_SESSION['user_id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = $_POST['car_id'];
    $rental_days = $_POST['rental_days'];
    $daily_charge = $_POST['daily_charge'];
    $pickup_location = $_POST['pickup_location'];
    $return_location = $_POST['return_location'];
    $pickup_datetime = $_POST['pickup_datetime'];
    $return_datetime = $_POST['return_datetime'];

    // Calculate total
    $total_charge = $rental_days * $daily_charge;

    try {
        $stmt = $conn->prepare("INSERT INTO bookings (
            user_id, car_id, rental_days, total_charge, pickup_location, return_location, pickup_datetime, return_datetime
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "iiidssss", 
            $user_id, 
            $car_id, 
            $rental_days, 
            $total_charge, 
            $pickup_location, 
            $return_location, 
            $pickup_datetime, 
            $return_datetime
        );

        $stmt->execute();

        echo "
        <div class='confirmation-message'>
            <div class='message-icon'>&#9989;</div>
            <div class='message-text'>
                <h2>Booking Confirmed!</h2>
                <p>Your booking has been successfully confirmed.</p>
                <p><strong>Total Charge: KSh {$total_charge}</strong></p>
            </div>
        </div>

        <script>
            setTimeout(function() {
                window.location.href = 'index.html'; 
            }, 2000); 
        </script>
        ";
    } catch (mysqli_sql_exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>
