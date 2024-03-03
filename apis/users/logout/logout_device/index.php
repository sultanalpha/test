<?php
include "../../../connect.php";
include_once "../../../jwt/validate-jwt.php";

include "../../../jwt/check-csrf.php";

$rawData = file_get_contents('php://input');
$dataJson = json_decode($rawData, true);

if (isset($_SERVER['HTTP_AUTH'])) {
    if (isset($dataJson['enc_'])) {
        $encData = $dataJson['enc_'];
        $privateKey = $_SESSION['private_rsa_key'];
        if (!openssl_private_decrypt(base64_decode($encData), $decrypted, $privateKey)) {
            $error = openssl_error_string();
            echo "Decryption error: $error";
        } else {
            
            $password = $decrypted ?? null;
            if ($password == null) {
                echo_json_error(400, "Error", "772011x", "Password cant be empty");
                return;
            }
            $refresh_id = $dataJson['refresh_id'] ?? null;
            $jwt = $_SERVER['HTTP_AUTH'];
            $data = json_decode(is_jwt_valid($jwt));
            if (checkCode($data)) {
                $hash_password = hash("sha256", $password);
                $stmt = $con->prepare("SELECT * FROM users WHERE users_id = ? AND users_password = ?");
                $stmt->execute([
                    $data->user_id,
                    $hash_password
                ]);
                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($results) {
                    try {
                        $cmd = $con->prepare("DELETE FROM refreshtoken WHERE users_id = ? AND refresh_id = ?");
                        $cmd->execute([
                            $data->user_id,
                            $refresh_id
                        ]);
                        session_destroy();
                        echo_json("200", "Success", "Logged out successfully");
                    } catch (PDOException  $e) {
                        echo_json_error(501, "Error", "772069x", $e->getMessage());
                    }
                } else {
                    echo_json_error(400, "Error", "772066x", "Incorrect password provided!");
                }
            }
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}
