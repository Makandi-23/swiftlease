<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $input = json_decode(file_get_contents("php://input"), true);

    
    if (isset($input['name'], $input['email'], $input['phone'], $input['password'])) {
        $name = trim($input['name']);
        $email = trim($input['email']);
        $phone = trim($input['phone']);
        $password = trim($input['password']);

        
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            echo json_encode(["error" => "All fields are required!"]);
            exit();
        }

        
        $checkUserQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkUserQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(["error" => "Email is already registered! Please sign in."]);
            exit();
        }

        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $insertUserQuery = "INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertUserQuery);
        $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Registration successful!"]);
        } else {
            echo json_encode(["error" => $stmt->error]);
        }

       
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["error" => "Invalid input!"]);
    }
} else {
    echo json_encode(["error" => "Invalid request method!"]);
}
?>
