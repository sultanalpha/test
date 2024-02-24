<?php
include "../../connect.php";
include "../../jwt/generate-jwt.php";
include "../../jwt/check-csrf.php";


$received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
if (!checkCSRF($received_csrf_token)) {
    return;
}

$rawData = file_get_contents('php://input');
$dataJson = json_decode($rawData, true);

if (isset($dataJson['enc_'])) {
    $encData = $dataJson['enc_'];
    $privateKey = $_SESSION['private_rsa_key'];
    if (!openssl_private_decrypt(base64_decode($encData), $decrypted, $privateKey)) {
        $error = openssl_error_string();
        echo "Decryption error: $error";
    } else {

        $jsonObj = json_decode($decrypted);

        $username = $dataJson['username'] ?? null;
        // $password = $jsonObj->password ?? null;
        $password = $decrypted ?? null;
        $email = $dataJson['email'] ?? null;

        if ($username != null || $email != null) {
            if ($password != null) {
                if (checkLength($username) || checkLength($email)) {
                    if (checkLength($password)) {

                        $hash_password = hash("sha256", $password);

                        if ($username != null) {
                            $stmt = $con->prepare("SELECT users_id, users_name, users_email, created_time FROM users WHERE users_name = ? AND users_password = ?");
                            $stmt->execute([
                                $username,
                                $hash_password
                            ]);
                        } else if ($email != null) {
                            $stmt = $con->prepare("SELECT users_id, users_name, users_email, created_time FROM users WHERE users_email = ? AND users_password = ?");
                            $stmt->execute([
                                $email,
                                $hash_password
                            ]);
                        }
                        $data = $stmt->fetch(PDO::FETCH_ASSOC);
                        $count  = $stmt->rowCount();

                        if ($count > 0) {
                            // $ipAddress = curl_get_contents('https://api.ipify.org');
                            $ipAddress = $_SERVER['REMOTE_ADDR'];
                            $userAgent = $_SERVER['HTTP_USER_AGENT'];
                            // $data['ip_address'] = $ipAddress;

                            $headers = array(
                                "ip_address" => $ipAddress,
                                "header_token" => "123456789123456789123456789123456",
                                "user_agent" => $userAgent
                            );
                            $jwt = generate_jwt($data, $headers);
                            // echo "jwt token is: " . $jwt . "<br>";
                            if ($jwt != null) {
                                echo_json(200, "Success", "Logged in successfully.", array("token", $jwt), $dataJson);
                                $_SESSION['isLoggedin'] = true;
                            } else {
                                echo_json(501, "Server error", "Something went wrong.");
                            }
                        } else {
                            echo_json(400, "Bad request", "Username or password is incorrect.");
                        }
                    } else {
                        echo_json(400, "Bad request", "password is too short.");
                    }
                } else {
                    echo_json(400, "Bad request", "Username or email is too short.");
                }
            } else {
                echo_json(400, "Very bad request", "password cannot be empty!.");
            }
        } else {
            echo_json(400, "Very bad request", "Username or email cannot be empty!.");
        }
    }
    return;
}
