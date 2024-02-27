<?php
include "../../connect.php";
include "../../jwt/generate-jwt.php";
include "../../jwt/check-csrf.php";

// $received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
// if (!checkCSRF($received_csrf_token)) {
//     return;
// }

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;
$user_email = strtolower($data['email']) ?? null;

$confirm_password = $data['confirm_password'] ?? null;
// $public_ip = $data['public_ip'] ?? null;
// $ipAddress = curl_get_contents('https://api.ipify.org');
$ipAddress = $_SERVER['REMOTE_ADDR'];

if(checkNigga($user_email)) {
    echo_json(400, "Bad request bro", "No nigga bro please :) you are not nigga");
    return;
}

if ($username != null && $password != null && $user_email != null && $confirm_password != null) {
    if (checkLength($username) && checkLength($password) && checkLength(($confirm_password))) {
        if (validateEmail($user_email)) {
            $hash_password = hash("sha256", $password);
            $confirm_hash_password = hash("sha256", $confirm_password);
            if ($hash_password == $confirm_hash_password) {
                try {
                    $check = $con->prepare("SELECT * FROM users WHERE users_name = ? OR users_email = ?");
                    $check->execute(
                        [
                            $username,
                            $user_email
                        ]
                    );
                    $check_data = $check->fetchAll(PDO::FETCH_ASSOC);
                    if (empty($check_data)) {
                        $stmt = $con->prepare("INSERT INTO users (users_id, users_name, users_email, users_password, ip_address) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([
                            null,
                            $username,
                            $user_email,
                            $hash_password,
                            $ipAddress
                        ]);
                        $data = $stmt->fetch(PDO::FETCH_ASSOC);
                        $count  = $stmt->rowCount();
                        if ($count > 0) {

                            $ipAddress = $_SERVER['REMOTE_ADDR'];
                            $userAgent = $_SERVER['HTTP_USER_AGENT'];
                            $headers = array(
                                "ip_address" => $ipAddress,
                                "header_token" => "123456789123456789123456789123456",
                                "user_agent" => $userAgent
                            );

                            $currentDateTime = date('Y-m-d H:i:s', strtotime('now'));
                            $payload_data = array(
                                "users_id" => $con->lastInsertId(),
                                "users_name" => $username,
                                "users_email" => $user_email,
                                "created_time" => $currentDateTime
                            );
                            $jwt = generate_jwt($payload_data, $headers);
                            if ($jwt != null) {
                                echo_json(200, "Success", "Created successfully :D", array("token", $jwt));
                                $_SESSION['isLoggedin'] = true;
                            } else {
                                echo_json(501, "Server error", "Something went wrong.");
                            }
                        } else {
                            echo_json(400, "Bad request", "Something went wrong!!!");
                        }
                    } else {
                        echo_json(400, "Bad request", "Username or email already taken. Please choose a different username.");
                    }
                } catch (PDOException $e) {
                    if ($e->getCode() === '23000' && $e->errorInfo[1] === 1062) {
                        echo_json(400, "Bad request", "Username or email already taken. Please choose a different username.");
                    } else {
                        echo_json(400, "Bad request", "Database error: " . $e->getMessage());
                    }
                }
            } else {
                echo_json(400, "Bad request", "Password and confirm password doesn't match!");
            }
        } else {
            echo_json(400, "Bad request", "Please enter a vaild email");
        }
    } else {
        echo_json(400, "Bad request", "Username or password length is too short like abdullah.");
    }
} else {
    echo_json(400, "Very bad request!", "Some data cannot be empty!.");
}


function checkNigga($string)
{
    $specificWord = "nigga";
    if (strpos($string, $specificWord) !== false) {
        return true;
    } else {
        return false;
    }
}
