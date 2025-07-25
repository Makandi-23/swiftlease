<?php

include 'db.php';
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
session_start(); 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $input = json_decode(file_get_contents("php://input"), true);

    
    if (isset($input['email']) && isset($input['password'])) {
        $email = trim($input['email']);
        $password = trim($input['password']);
        
       
        if (empty($email) || empty($password)) {
            http_response_code(400); 
            echo json_encode(["error" => "Both fields are required!"]);
            exit();
        }

        
        $getUserQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($getUserQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            http_response_code(401); 
            echo json_encode(["error" => "Invalid email or password!"]);
            exit();
        }

        
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            
            http_response_code(200); 
            echo json_encode([
                "message" => "Sign in successful",
                "user" => [
                    "id" => $user['id'],
                    "name" => $user['name'],
                    "email" => $user['email']
                ]
            ]);
            exit();
        } else {
            http_response_code(401); 
            echo json_encode(["error" => "Invalid email or password!"]);
            exit();
        }
    } else {
        http_response_code(400); 
        echo json_encode(["error" => "Both fields are required!"]);
        exit();
    }

    
    $stmt->close();
    $conn->close();
}
?>
