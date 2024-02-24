<?php
include "../../connect.php";
include_once "../../jwt/validate-jwt.php";

include "../../jwt/check-csrf.php";

if (isset($_SERVER['HTTP_AUTH'])) {
    $jwt = $_SERVER['HTTP_AUTH'];
    $data = json_decode(is_jwt_valid($jwt));
    if (checkCode($data)) {
        $cmd = $con->prepare("SELECT test_id, test_name, test_desc, test_created, test_link FROM test WHERE users_id = ?");
        $cmd->execute([
            $data->user_id
        ]);
        $data = $cmd->fetchAll(PDO::FETCH_ASSOC);
        if ($data) {
            echo_json("200", "Success", "Vaild request", array("test_info", $data));
        } else {
            echo_json_error(400, "Error", "772012x", "No results were found!");
            return null;
        }
    }
} else {
    echo_json_error(400, "Error", "772011x", "Auth token cant be empty");
}
