<?php

include("../../jwt/validate-jwt.php");
include("../../connect.php");

$rawData = file_get_contents('php://input');
$data_req = json_decode($rawData, true);
$jwt_token = $data_req['token'] ?? null;
$public_token_req = $data_req['public_token'] ?? null;

if ($jwt_token != null && $public_token_req != null) {
    $data = json_decode(is_jwt_valid($jwt_token, true));
    if ($data->status) {
        if ($data->code == 200) {
            echo_json(200, "Success", "Token is vaild");
        } else if ($data->code == 400) {

            $tokenParts = explode('.', $jwt_token);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signature_provided = $tokenParts[2];
            $payload_decoded = json_decode($payload);

            $public_token = random_bytes(32);
            $public_hexString = bin2hex($public_token);

            $updateData = updatePublicToken($public_hexString, $public_token_req, $payload_decoded->users_id);
            if ($updateData != null) {
                // echo $payload;
                // $ipAddress = curl_get_contents('https://api.ipify.org');
                $ipAddress = $_SERVER['REMOTE_ADDR'];
                $userAgent = $_SERVER['HTTP_USER_AGENT'];
                    // $data['ip_address'] = $ipAddress;

                    $headers = array(
                        "ip_address" => $ipAddress,
                        "header_token" => "123456789123456789123456789123456",
                        "user_agent" => $userAgent
                    );
                    
                $jwt = generate_jwt(json_decode($payload, true), $headers, true);
                if ($jwt != null) {
                    echo_json(200, "Success", "Updated successfully", array("token", $jwt));
                } else {
                    http_response_code(501);
                    echo json_encode(array("Code" => 501, "Status" => "Error", "Error code" => "772003x", "Message" => "Something went wrong!"));
                    // echo_json(501, "Server error", "Something went wrong.");
                }
            } else {
                http_response_code(401);
                echo json_encode(array("Code" => 401, "Status" => "Error", "Error code" => "772002x", "Message" => "Invalid public token provided."));
                // echo_json(400, "Request error", "Invalid public token provided.");
            }
        }
    } else {
        http_response_code(501);
        echo json_encode(array("Code" => 501, "Status" => "Error", "Error code" => "772003x", "Message" => "Something went wrong! (please login again)"));
        // echo_json(401, "Request error", "Something went wrong with your request!!! Please login again.");
    }
} else {
    http_response_code(400);
    echo json_encode(array("Code" => 400, "Status" => "Error", "Error code" => "772010x", "Message" => "Some data is empty."));
    // echo_json(400, "Error", "Some data is empty.");
}

function updatePublicToken($publicNewToken, $publicToken, $userID)
{
    global $con;

    $stmt = $con->prepare("SELECT * FROM refreshtoken WHERE public_token = ? AND users_id = ?");
    $stmt->execute([
        $publicToken,
        $userID
    ]);
    $stmt->fetch(PDO::FETCH_ASSOC);
    $count  = $stmt->rowCount();
    if ($count > 0) {
        $stmtUpdate = $con->prepare("UPDATE refreshtoken SET public_token = ? WHERE users_id = ?");
        $stmtUpdate->execute([
            $publicNewToken,
            $userID
        ]);
        // $data = $stmtUpdate->fetch(PDO::FETCH_ASSOC);
        $countUpdate  = $stmtUpdate->rowCount();
        if ($countUpdate > 0) {
            // return $data;
            $stmtSelect = $con->prepare("SELECT * FROM refreshtoken WHERE users_id = ?");
            $stmtSelect->execute([$userID]);
            $data = $stmtSelect->fetch(PDO::FETCH_ASSOC);
            return $data;
        } else {
            return null;
        }
    } else {
        return null;
    }
}
