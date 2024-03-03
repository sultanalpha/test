<?php
include "../../../connect.php";
include_once "../../../jwt/validate-jwt.php";

include "../../../jwt/check-csrf.php";

if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        try {
            $cmd = $con->prepare("DELETE FROM refreshtoken WHERE users_id = ? AND refresh_token = ?");
            $cmd->execute([
                $data->user_id,
                $data->refresh_token
            ]);
            session_destroy();
            echo_json("200", "Success", "Logged out successfully");
        } catch (PDOException  $e) {
            echo_json_error(501, "Error", "772069x", $e->getMessage());
            // return print('Error!: ' . $e->getMessage());
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}
