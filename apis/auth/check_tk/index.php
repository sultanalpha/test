<?php
include_once "../../jwt/validate-jwt.php";
include "../../connect.php";

if(isset($_SERVER["HTTP_AUTH"])){
    $jwt = $_SERVER["HTTP_AUTH"];
    $data = json_decode(is_jwt_valid($jwt));
    if(checkCode($data)){
        echo_json("200", "Success", "Vaild token");
    }
} else {
    echo_json("400", "Request error", "Auth token cant be empty");
}

?>