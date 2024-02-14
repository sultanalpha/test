<?php
// include "../connect.php";
include "generate-jwt.php";
//$secret = 'wZN6PSK2ANMw0q1EYmoB+ktnN+QuRdZc3ETPmHJfiz1i0St3Ml7vo+ZkW90TO/sCkva4ciBWjmRwt/JmOvZ2mtQOTsJ69RJJ3Z7tSiBZ2nU='


function is_jwt_valid($jwt, $returnValue = false)
{
    $isValidToken = false;
    $message = null;
    $tokenParts = explode('.', $jwt);
    $header = base64_decode($tokenParts[0]);
    $payload = base64_decode($tokenParts[1]);
    $signature_provided = $tokenParts[2];
    $payload_decoded = json_decode($payload);
    $header_decoded = json_decode($header);

    $refreshData = checkRefreshToken($payload_decoded->users_id, $payload_decoded->ref_token, $header_decoded->ip_address ?? null);
    if ($refreshData != null) {
        $refreshExpire = $refreshData['refresh_exp'];
        $refreshDead = $refreshData['refresh_dead'];
        // echo strtotime($refreshExpire) . " " . strtotime($refreshDead) . " " . time() . " ";

        if ($payload_decoded->ref_token == $refreshData['refresh_token']) {
            $current_time = strtotime(date('Y-m-d H:i:s'));

            if (strtotime($refreshDead) > $current_time) {

                // $public_token = random_bytes(32);
                // $public_hexString = bin2hex($public_token);

                if ($payload_decoded->exp_time > $current_time) {
                    if (strtotime($refreshExpire) > $current_time) {
                        $code = 200;
                        $http_code = 200;
                        $message = "Vaild token";
                        $isValidToken = true;
                    } else {
                        $code = 400;
                        $http_code = 401;
                        $message = checkPublicToken($payload_decoded->users_id)['public_token'];
                        $isValidToken = $returnValue;
                    }
                } else {
                    $code = 400;
                    $http_code = 401;
                    $message = checkPublicToken($payload_decoded->users_id)['public_token'];
                    $isValidToken = $returnValue;
                }
            } else {
                $code = 401;
                $http_code = 401;
                $message = "Token expired (dead) please login again!";
                $isValidToken = false;
            }
        } else {
            $code = 402;
            $http_code = 400;
            $message = "Invalid token provided";
            $isValidToken = false;
        }
    } else {
        $code = 402;
        $http_code = 400;
        $isValidToken = false;
        $message = "Invalid token provided";
    }

    // echo " . " . $current_time . " . " . strtotime($refreshExpire) . " . " . $code . " . ";


    $base64_url_header = base64_url_encode($header);
    $base64_url_payload = base64_url_encode($payload);
    $signature = hash_hmac('sha512', $base64_url_header . "." . $base64_url_payload, SECRET, true);
    $base64_url_signature = base64_url_encode($signature);

    $is_signature_valid = ($base64_url_signature === $signature_provided);

    if (!$is_signature_valid) {
        return json_encode(array("status" => false, "message" => "Invalid token provided!", "code" => 401, "http_code" => 400));
    } else {
        if (!$isValidToken) {
            return json_encode(array("status" => false, "message" => $message ?? "Something went wrong in server", "code" => $code, "http_code" => $http_code));
        } else {
            return json_encode(array("status" => true, "message" => $message ?? "Something went wrong in server", "code" => $code, "http_code" => $http_code, "user_id" => $payload_decoded->users_id));
        }
    }
}

function checkPublicToken($userID)
{
    global $con;
    $stmt = $con->prepare("SELECT public_token FROM refreshtoken WHERE users_id = ?");
    $stmt->execute([
        $userID
    ]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        return $data;
    } else {
        return false;
    }
}

function checkRefreshToken($userID, $refreshToken, $ipAddress)
{
    global $con;
    $stmtCheck = $con->prepare("SELECT * FROM refreshtoken WHERE users_id = ? AND refresh_token = ? AND user_ip = ?");
    $stmtCheck->execute([
        $userID,
        $refreshToken,
        $ipAddress
    ]);
    $data = $stmtCheck->fetch(PDO::FETCH_ASSOC);
    if ($data) {
        return $data;
    } else {
        return null;
    }
}
