<?php
include "../connect.php";
include_once "../jwt/validate-jwt.php";

include "../jwt/check-csrf.php";


// $received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
// if(!checkCSRF($received_csrf_token)){
//     return;
// }


if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        $cmd = $con->prepare("SELECT users_id, users_name, users_email, created_time FROM users WHERE users_id = ?");
        $cmd->execute([
            $data->user_id
        ]);
        $data = $cmd->fetch(PDO::FETCH_ASSOC);
        if ($data) {

            echo_json("200", "Success", "Vaild request", array("user_info", $data));
        } else {
            echo_json_error(400, "Error", "772012x", "No results were found!");
            return null;
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}

?>