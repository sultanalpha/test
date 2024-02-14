<?php
require_once 'vendor/autoload.php';

use DeviceDetector\ClientHints;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\AbstractDeviceParser;
function generate_jwt($payload, $header, $isUpdate = false)
{
    // echo json_encode($payload) . "\n";

    $private_token = random_bytes(32);
    $private_hexString = bin2hex($private_token);

    $public_token = random_bytes(32);
    $public_hexString = bin2hex($public_token);

    // $signing_key = "wZN6PSK2ANMw0q1EYmoB+ktnN+QuRdZc3ETPmHJfiz1i0St3Ml7vo+ZkW90TO/sCkva4ciBWjmRwt/JmOvZ2mtQOTsJ69RJJ3Z7tSiBZ2nU=";

    //Add current time data to the payload.
    $data = $payload;
    $current_time = time();
    $newTimestamp = strtotime('+3600 seconds', $current_time);
    $deadTimestamp = strtotime('+1 day', $current_time);
    $newDate = date('Y-m-d H:i:s', $newTimestamp);
    $newDead = date('Y-m-d H:i:s', $deadTimestamp);
    $data['exp_time'] = $newDate;
    $data['ref_token'] = $private_hexString;
    $data['public_token'] = $public_hexString;
    $payload = $data;

    if (empty($payload) || empty($header)) {
        throw new Exception("Payload and header are required.");
    }

    $encodedHeader = base64_url_encode(json_encode($header));
    $encodedPayload = base64_url_encode(json_encode($payload));


    $signature = hash_hmac('sha512', $encodedHeader . "." . $encodedPayload, SECRET, true);
    $encodedSignature = base64_url_encode($signature);

    $jwt = $encodedHeader . "." . $encodedPayload . "." . $encodedSignature;
    // echo "ip address from header is: " . $header['ip_address'];
    if (createRefreshToken($data['users_id'], $newDate, $private_hexString, $newDead, $public_hexString, $isUpdate, $header['ip_address'], $header['user_agent'])) {
        return $jwt;
    } else {
        return null;
    }
}


function base64_url_encode($text)
{
    return strtr(base64_encode($text), '+/', '-_');
}

function getDeviceInfo($userID, $userAgent, $refreshTokenId)
{
    AbstractDeviceParser::setVersionTruncation(AbstractDeviceParser::VERSION_TRUNCATION_NONE);

    // $userAgent = $_SERVER['HTTP_USER_AGENT'];
    
    $clientHints = ClientHints::factory($_SERVER); 
    
    $dd = new DeviceDetector($userAgent, $clientHints);
    
    $dd->parse();
    $clientInfo = $dd->getClient();
    $osInfo = $dd->getOs();
    // echo json_encode($userAgent, $refreshTokenId);
    $device = $dd->getDeviceName();
    $brand = $dd->getBrandName();
    $model = $dd->getModel();

    if ($device == "desktop") {
        global $con;
        $stmtCheck = $con->prepare("INSERT INTO devices_info (device_id, device_type, device_model, device_brand, device_version, device_platform, refresh_id, users_id, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtCheck->execute([
            null,
            $device,
            null,
            null,
            $osInfo['version'],
            $osInfo['platform'],
            $refreshTokenId,
            $userID,
            $userAgent
        ]);
        $stmtCheck->fetch(PDO::FETCH_ASSOC);
        $countCheck = $stmtCheck->rowCount();
        if ($countCheck > 0) {
            return true;
        } else {
            return false;
        }
    } else {
        global $con;
        $stmtCheck = $con->prepare("INSERT INTO devices_info (device_id, device_type, device_model, device_brand, device_version, device_platform, refresh_id, users_id, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmtCheck->execute([
            null,
            $device,
            $brand,
            $model,
            $osInfo['version'],
            $osInfo['platform'],
            $refreshTokenId,
            $userID,
            $userAgent
        ]);
        $stmtCheck->fetch(PDO::FETCH_ASSOC);
        $countCheck = $stmtCheck->rowCount();
        if ($countCheck > 0) {
            return true;
        } else {
            return false;
        }
    }
}

function createRefreshToken($userID, $exp, $hexString, $newDead, $public_token, $isUpdate, $ipAddress, $userAgent)
{
    global $con;
    $stmtCheck = $con->prepare("SELECT * FROM refreshtoken WHERE users_id = ? AND user_ip = ?");
    $stmtCheck->execute([
        $userID,
        $ipAddress
    ]);
    $stmtCheck->fetch(PDO::FETCH_ASSOC);
    $countCheck = $stmtCheck->rowCount();
    if ($countCheck > 0) {
        if (!$isUpdate) {
            $stmtUpdate = $con->prepare("UPDATE refreshtoken SET refresh_token = ?, refresh_dead = ?, refresh_exp = ?, public_token = ? WHERE users_id = ? AND user_ip = ?");
            $stmtUpdate->execute([
                $hexString,
                $newDead,
                $exp,
                $public_token,
                $userID,
                $ipAddress
            ]);

            $stmtUpdate->fetch(PDO::FETCH_ASSOC);
            $countUpdate  = $stmtUpdate->rowCount();
            if ($countUpdate > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $stmtUpdate = $con->prepare("UPDATE refreshtoken SET refresh_token = ?, refresh_exp = ?, public_token = ? WHERE users_id = ? AND user_ip = ?");
            $stmtUpdate->execute([
                $hexString,
                $exp,
                $public_token,
                $userID,
                $ipAddress
            ]);

            $stmtUpdate->fetch(PDO::FETCH_ASSOC);
            $countUpdate  = $stmtUpdate->rowCount();
            if ($countUpdate > 0) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        $stmt = $con->prepare("INSERT INTO refreshtoken (refresh_id, refresh_token, refresh_exp, public_token, refresh_dead, users_id, user_ip) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            null,
            $hexString,
            $exp,
            $public_token,
            $newDead,
            $userID,
            $ipAddress
        ]);

        $stmt->fetch(PDO::FETCH_ASSOC);
        $count  = $stmt->rowCount();
        if ($count > 0) {
            $refTokenId  = $con->lastInsertId();
            if(getDeviceInfo($userID, $userAgent, $refTokenId)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}