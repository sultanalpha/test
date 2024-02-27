<?php
include "../../connect.php";
include_once "../../jwt/validate-jwt.php";

include "../../jwt/check-csrf.php";


// $received_csrf_token = $_SERVER['HTTP_X_CSRFTOKEN'] ?? null;
// if (!checkCSRF($received_csrf_token)) {
//     return;
// }


if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        $cmd = $con->prepare("SELECT * FROM refreshtoken WHERE refresh_token = ?");
        $cmd->execute([
            $data->refresh_token
        ]);
        $results = $cmd->fetch(PDO::FETCH_ASSOC);
        if ($results) {
            $cmd2 = $con->prepare("SELECT * FROM devices_info WHERE users_id = ?");
            $cmd2->execute([
                $data->user_id
            ]);
            $results2 = $cmd2->fetchAll(PDO::FETCH_ASSOC);
            if ($results2) {
                echo_json("200", "Success", "Vaild request", array("devices_info", $results2), $results['refresh_id'], "Currect device session");
            } else {
                echo_json_error(400, "Error", "772012x", "No results were found!");
                return null;
            }
        } else {
            echo_json_error(400, "Error", "772012x", "No results were found!");
            return null;
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}
